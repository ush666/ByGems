<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../User-Pages/home.php");
    exit();
}

// Check for success message in URL
if (isset($_GET['success'])) {
    $success_message = urldecode($_GET['success']);
    // Store in session to show after page loads
    $_SESSION['success_message'] = $success_message;
    // Remove from URL to prevent showing again on refresh
    header("Location: packages&services.php");
    exit();
}

$user_role = $_SESSION['role'];
$error = "";
$success = "";

// Handle form submission for adding new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_name'])) {
    $service_name = trim($_POST['service_name']);
    $category = trim($_POST['category_id']);
    $description = trim($_POST['description']);
    $status = "enabled";
    $entertainer_duration_options = $_POST['entertainerDuration'] ?? null;
    $price = ($_SESSION['role'] !== 'customer' && isset($_POST['price'])) ? trim($_POST['price']) : null;
    $image = null;

    if (empty($service_name) || empty($category) || empty($description)) {
        $error = "All fields except price and image are required!";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../uploads/";
            $imageFileName = time() . "_" . basename($_FILES["image"]["name"]);
            $targetFilePath = $targetDir . $imageFileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileType, $allowedTypes)) {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = "File size must be less than 5MB.";
            } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                $error = "Error uploading image.";
            } else {
                $image = $imageFileName;
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO services (service_name, category, entertainer_duration_options, price, description, image, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $service_name,
                    $category,
                    $entertainer_duration_options,
                    $price,
                    $description,
                    $image,
                    $status
                ]);

                header("Location: ../Staff-Pages/packages&services.php?success=Service+added+successfully");
                exit();
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all services
$query = "SELECT * FROM services";
$stmt = $pdo->prepare($query);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all possible ENUM category values
$stmt = $pdo->query("SHOW COLUMNS FROM services LIKE 'category'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
$categories = explode("','", $matches[1]); // Extract ENUM values into an array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .main-content {
            padding: 20px;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 30px;
        }

        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 5px 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 5px;
            width: 70px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin-left: 2px;
            border: 1px solid transparent;
            border-radius: 4px;
            font-weight: bold;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #343a40;
            color: white !important;
            border: 1px solid #343a40;
        }

        .active>.page-link,
        .page-link.active {
            z-index: 3;
            color: var(--bs-pagination-active-color);
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
        }

        .custom-swal-image {
            object-fit: cover;
            border-radius: 10px;
            width: 90% !important;
            background-color: #f8f8f8;
        }

        #imagePreview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }

        /*
        .table-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }*/

        .btn-warning {
            padding: 4px 16px !important;
        }
    </style>
</head>

<body>
    <?php
    $packageServices = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="mt-5 pt-4 mb-3">
                <div class="card mt-3 p-3 me-3" style="border-radius: 10px; border: none !important;">
                    <h1 class="h2">Packages & Services</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">
                            Packages & Services
                        </div>
                    </nav>
                </div>
            </div>
            <!-- Main content -->
            <div class="main-content p-0 pe-3">

                <!-- Users Table -->
                <div class="table-container">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        + Add
                    </button>

                    <table id="servicesTable" class="table table-hover table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td><?php echo str_pad($service['service_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($service['category']); ?></td>
                                    <td>₱<?php echo number_format($service['price'], 2); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm text-white bold" onclick='showDescription(<?php echo json_encode($service["description"]); ?>)'>View Description</button>
                                    </td>
                                    <td>
                                        <?php if (!empty($service['image'])): ?>
                                            <button
                                                type="button"
                                                class="btn btn-purple btn-sm view-image-btn"
                                                data-image="<?php echo htmlspecialchars($service['image']); ?>">
                                                View
                                            </button>
                                        <?php else: ?>
                                            No Image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-status" type="checkbox" role="switch"
                                                id="statusSwitch<?php echo $service['service_id']; ?>"
                                                data-id="<?php echo $service['service_id']; ?>"
                                                <?php echo ($service['status'] == 'enabled') ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                    <td class="d-flex flex-column justify-content-stretch gap-3 h-100">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-warning text-white" onclick="openEditServiceModal(<?= $service['service_id']; ?>)">Edit</a>
                                        <button class="btn btn-danger btn-sm delete-service"
                                            data-id="<?php echo $service['service_id']; ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content" id="editServiceModalContent">
                <!-- Content will be loaded here via AJAX -->
            </div>
        </div>
    </div>


    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addServiceModalLabel">Create</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Error / Success Messages -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <!-- Form Fields -->
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="service_name" class="form-label">Service Name</label>
                                <input type="text" class="form-control" id="service_name" name="service_name" required>
                            </div>

                            <div class="mb-3 col-6">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- Hidden Special Input for Entertainers -->
                        <div class="mb-3 d-none" id="entertainersOptions">
                            <label for="entertainerDuration" class="form-label">Duration / Type</label>
                            <input type="hidden" class="form-control" id="entertainerDuration" name="entertainerDuration" placeholder="Type or select durations">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2" required></textarea>
                        </div>

                        <?php if ($_SESSION['role'] === 'staff'): ?>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (PHP)</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01">
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <p class="form-text text-muted">Only admins can set prices.</p>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Service</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let input = document.getElementById('entertainerDuration');
        let tagify = new Tagify(input, {
            whitelist: ["15 mins", "30 mins", "1 hour", "Appearance"],
            dropdown: {
                enabled: 1, // shows suggestions as soon as typing starts
                maxItems: 5
            }
        });

        document.getElementById('category_id').addEventListener('change', function() {
            const entertainerOptions = document.getElementById('entertainersOptions');
            if (this.value === 'Entertainers') {
                entertainerOptions.classList.remove('d-none');
            } else {
                entertainerOptions.classList.add('d-none');
                document.getElementById('entertainerDuration').value = ''; // clear if switching category
            }
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openEditServiceModal(serviceId) {
            // Use AJAX to load the edit form into the modal
            $.ajax({
                url: '../events/edit_service.php', // your original edit page
                type: 'GET',
                data: {
                    id: serviceId
                },
                success: function(response) {
                    $('#editServiceModalContent').html(response); // Inject the form inside the modal
                    $('#editServiceModal').modal('show'); // Then open the modal
                },
                error: function(xhr, status, error) {
                    alert('Failed to load the form: ' + error);
                }
            });
        }
        $(document).ready(function() {
            // DataTables init
            $('#servicesTable').DataTable({
                responsive: true,
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                pageLength: 5,
                "ordering": false,
                dom: '<"top"lf>rt<"bottom"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search services...",
                    lengthMenu: "Show _MENU_ services per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ services",
                    infoEmpty: "No services found",
                    infoFiltered: "(filtered from _MAX_ total services)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Toggle status
            $('.toggle-status').on('change', function() {
                var serviceId = $(this).data('id');
                var status = $(this).is(':checked') ? 'enabled' : 'disabled';

                $.ajax({
                    url: 'update_service_status.php',
                    type: 'POST',
                    data: {
                        service_id: serviceId,
                        status: status
                    },
                    success: function(response) {
                        let res = JSON.parse(response);
                        if (res.success) {
                            console.log('Status updated successfully');
                        } else {
                            alert('Failed to update status.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });

            // View image button
            $(document).on('click', '.view-image-btn', function() {
                var imagePath = $(this).data('image');
                Swal.fire({
                    title: '',
                    imageUrl: '../uploads/' + imagePath,
                    imageAlt: 'Service Image',
                    imageHeight: 400,
                    customClass: {
                        image: 'custom-swal-image'
                    },
                    confirmButtonText: 'Close'
                });
            });
        });

        // Image preview function
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

    <script>
        $(document).ready(function() {
            // Check for success message in session
            <?php if (isset($_SESSION['success_message'])): ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?php echo addslashes($_SESSION['success_message']); ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                <?php unset($_SESSION['success_message']); // Clear the message after showing 
                ?>
            <?php endif; ?>
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showDescription(description) {
            Swal.fire({
                title: 'Service Description',
                html: `<div style="max-height: 500px; overflow-y: auto;">${description}</div>`,

                width: 450,
                confirmButtonText: 'Close'
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-service');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const serviceId = this.getAttribute('data-id');
                    const row = this.closest('tr'); // find the nearest table row

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send request to server
                            fetch(`delete_service.php?id=${serviceId}`, {
                                    method: 'GET',
                                })
                                .then(response => response.text())
                                .then(data => {
                                    console.log('Deleted successfully:', data);

                                    // Remove row only after successful deletion
                                    row.remove();

                                    // Show success message
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The service has been deleted.',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                })
                                .catch(error => {
                                    console.error('Error deleting:', error);

                                    // Show error message
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'There was a problem deleting the service.',
                                        icon: 'error',
                                        confirmButtonColor: '#d33'
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
<?php
    if (isset($_GET['message'])) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {";

        if ($_GET['message'] === 'success') {
            echo "
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Service updated successfully!',
                confirmButtonText: 'OK'
            });
            ";
        } elseif ($_GET['message'] === 'error' && isset($_GET['error_message'])) {
            $errorMessage = htmlspecialchars($_GET['error_message']);
            echo "
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{$errorMessage}',
                confirmButtonText: 'OK'
            });
            ";
        }

        echo "});
        </script>";
    }
?>




</body>

</html>
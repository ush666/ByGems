<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../User-Pages/home.php");
    exit();
}
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
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

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
    $discount = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="mt-5 pt-4 mb-3">
                <div class="card mt-3 p-3 me-3" style="border-radius: 10px; border: none !important;">
                    <h1 class="h2">Discounts</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">
                            Discounts
                        </div>
                    </nav>
                </div>
            </div>
            <!-- Main content -->
            <div class="main-content p-0 pe-3">

                <!-- Users Table -->
                <div class="table-container">
                    <button class="btn btn-purple mb-3 text-white bold" data-bs-toggle="modal" data-bs-target="#addDiscountModal">Add Discount</button>
                    <table id="discountTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Application</th>
                                <th>Active</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM discounts");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                    <td>{$row['discount_name']}</td>
                                    <td>{$row['discount_code']}</td>
                                    <td>{$row['discount_type']}</td>
                                    <td>{$row['discount_value']}</td>
                                    <td>{$row['discount_application']}</td>
                                    <td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>
                                    <td>" . date('M j, Y g:i A', strtotime($row['start_date'])) . "</td>
                                    <td>" . date('M j, Y g:i A', strtotime($row['end_date'])) . "</td>
                                    <td>
                                        <a class='text-warning edit-discount font-1 bold p-0 text-decoration-none' 
                                            data-id='{$row['id']}'
                                            data-name='" . htmlspecialchars($row['discount_name'], ENT_QUOTES) . "'
                                            data-code='" . htmlspecialchars($row['discount_code'], ENT_QUOTES) . "'
                                            data-type='{$row['discount_type']}'
                                            data-value='{$row['discount_value']}'
                                            data-application='{$row['discount_application']}'
                                            data-active='{$row['is_active']}'
                                            data-description='" . htmlspecialchars($row['discount_description'], ENT_QUOTES) . "'
                                            data-start='" . date('Y-m-d\TH:i', strtotime($row['start_date'])) . "'
                                            data-end='" . date('Y-m-d\TH:i', strtotime($row['end_date'])) . "'
                                            data-specific-services='" . htmlspecialchars($row['specific_service_ids'], ENT_QUOTES) . "'>
                                            <ion-icon name='clipboard' class='admin-btn' data-bs-toggle='tooltip' data-bs-placement='left' title='Edit'></ion-icon>
                                        </a>
                                        <a class='text-danger delete-discount font-1 bold p-0' data-id='{$row['id']}'><ion-icon name='ban' class='admin-btn' data-bs-toggle='tooltip' data-bs-placement='left' title='Delete'></ion-icon></a>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Discount Modal -->
    <div class="modal fade" id="editDiscountModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="../backend/edit_discount.php" method="POST" class="modal-content">
                <input type="hidden" name="id" id="editDiscountId">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editModalLabel">Edit Discount</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="discount_name" id="editDiscountName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Code</label>
                            <input type="text" name="discount_code" id="editDiscountCode" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Type</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_type" id="editPercentage" value="percentage">
                                <label class="form-check-label" for="editPercentage">Percentage (%)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_type" id="editFixed" value="fixed">
                                <label class="form-check-label" for="editFixed">Fixed (₱)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Value</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="discount_value" id="editDiscountValue" class="form-control" required>
                                <span class="input-group-text" id="valueSymbol">%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Application</label>
                            <select name="discount_application" id="editDiscountApplication" class="form-select">
                                <option value="all">All Services</option>
                                <option value="specific">Specific Services</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="editStatusActive" value="1">
                                <label class="form-check-label" for="editStatusActive">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" id="editStatusInactive" value="0">
                                <label class="form-check-label" for="editStatusInactive">Inactive</label>
                            </div>
                        </div>
                        <div class="col-md-12" id="editSpecificServicesContainer" style="display: none;">
                            <label class="form-label">Select Services</label>
                            <div class="row g-2">
                                <?php
                                $stmt = $pdo->prepare("SELECT service_id, service_name FROM services WHERE status = 'enabled'");
                                $stmt->execute();
                                $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Get the current discount's specific_service_ids (if editing)
                                $currentServiceIds = [];
                                if (isset($_GET['edit_id'])) {
                                    $editStmt = $pdo->prepare("SELECT specific_service_ids FROM discounts WHERE id = ?");
                                    $editStmt->execute([$_GET['edit_id']]);
                                    $discountData = $editStmt->fetch();
                                    $currentServiceIds = $discountData ? explode(',', $discountData['specific_service_ids']) : [];
                                }

                                foreach ($services as $service) {
                                    $checked = in_array($service['service_id'], $currentServiceIds) ? 'checked' : '';
                                    echo '<div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input service-checkbox" type="checkbox" name="specific_service_ids[]" 
                                                id="service_' . $service['service_id'] . '" value="' . $service['service_id'] . '" ' . $checked . '>
                                            <label class="form-check-label" for="service_' . $service['service_id'] . '">
                                                ' . htmlspecialchars($service['service_name']) . '
                                            </label>
                                        </div>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="discount_description" id="editDiscountDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="start_date" id="editDiscountStart" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" name="end_date" id="editDiscountEnd" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Discount Modal -->
    <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="../backend/add_discount.php" method="POST" class="modal-content">
                <div class="modal-header bg-purple text-white">
                    <h5 class="modal-title">Add New Discount</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" name="discount_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Code</label>
                        <input type="text" name="discount_code" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">Type</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="discount_type" id="percentage" value="percentage" checked>
                            <label class="form-check-label" for="percentage">Percentage (%)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="discount_type" id="fixed" value="fixed">
                            <label class="form-check-label" for="fixed">Fixed (₱)</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Value</label>
                        <input type="number" step="0.01" name="discount_value" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Application</label>
                        <select name="discount_application" class="form-select" id="discount_application">
                            <option value="all">All Services</option>
                            <option value="specific">Specific Services</option>
                        </select>
                    </div>
                    <div class="col-md-12" id="specific_services_container" style="display: none;">
                        <label>Select Services</label>
                        <div class="row g-2">
                            <?php
                            $stmt = $pdo->prepare("SELECT service_id, service_name FROM services WHERE status = 'enabled'");
                            $stmt->execute();
                            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($services as $service) {
                                echo '<div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input service-checkbox" type="checkbox" name="specific_service_ids[]" 
                                            id="add_service_' . $service['service_id'] . '" value="' . $service['service_id'] . '">
                                        <label class="form-check-label" for="add_service_' . $service['service_id'] . '">
                                            ' . htmlspecialchars($service['service_name']) . '
                                        </label>
                                    </div>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Description</label>
                        <textarea name="discount_description" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer p-0">
                    <button type="submit" class="btn btn-purple text-white bold">Add Discount</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Scripts-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Only once! -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appSelect = document.getElementById('discount_application');
            if (appSelect) {
                appSelect.addEventListener('change', function() {
                    const specContainer = document.getElementById('specific_services_container');
                    specContainer.style.display = this.value === 'specific' ? 'block' : 'none';
                });
            }

            // Initialize DataTable
            new DataTable('#discountTable');
        });


        //Sweet Alert
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-discount').forEach(button => {
                button.addEventListener('click', function() {
                    const discountId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This discount will be permanently deleted.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to delete_discount.php with the ID
                            window.location.href = `../backend/delete_discount.php?id=${discountId}`;
                        }
                    });
                });
            });
        });

        // For add modal
        document.getElementById('discount_application').addEventListener('change', function() {
            document.getElementById('specific_services_container').style.display =
                this.value === 'specific' ? 'block' : 'none';
        });

        // For edit modal
        $(document).on('click', '.edit-discount', function() {
            // Get all data attributes
            const discountId = $(this).data('id');
            const discountName = $(this).data('name');
            const discountCode = $(this).data('code');
            const discountType = $(this).data('type');
            const discountValue = $(this).data('value');
            const discountApplication = $(this).data('application');
            const discountActive = $(this).data('active');
            const discountDescription = $(this).data('description');
            const discountStart = $(this).data('start');
            const discountEnd = $(this).data('end');
            let specificServiceIds = $(this).data('specific-services') || '';

            // Populate modal fields
            $('#editDiscountId').val(discountId);
            $('#editDiscountName').val(discountName);
            $('#editDiscountCode').val(discountCode);
            $(`input[name="discount_type"][value="${discountType}"]`).prop('checked', true);
            $('#editDiscountValue').val(discountValue);
            $('#editDiscountApplication').val(discountApplication);
            $(`input[name="is_active"][value="${discountActive}"]`).prop('checked', true);
            $('#editDiscountDescription').val(discountDescription);
            $('#editDiscountStart').val(discountStart);
            $('#editDiscountEnd').val(discountEnd);

            // Update currency symbol
            $('#valueSymbol').text(discountType === 'percentage' ? '%' : '₱');

            // Handle services checkboxes
            $('#editDiscountModal').on('shown.bs.modal', function() {
                if (discountApplication === 'specific') {
                    $('#editSpecificServicesContainer').show();

                    // Uncheck all checkboxes first
                    $('.service-checkbox').prop('checked', false);

                    // Check the specific services
                    if (specificServiceIds) {
                        // Ensure we're working with a string
                        specificServiceIds = specificServiceIds.toString();

                        // Split into array (works for both single ID and multiple IDs)
                        const serviceIdsArray = specificServiceIds.split(',').map(id => id.trim()).filter(id => id !== '');

                        // Check each service checkbox
                        serviceIdsArray.forEach(serviceId => {
                            const checkbox = $(`#service_${serviceId}`);
                            if (checkbox.length) {
                                checkbox.prop('checked', true);
                            } else {
                                console.warn(`Checkbox not found for service ID: ${serviceId}`);
                            }
                        });
                    }
                } else {
                    $('#editSpecificServicesContainer').hide();
                }
            });

            // Update modal title
            $('#editModalLabel').text('Edit Discount: ' + discountName);

            // Show the modal
            $('#editDiscountModal').modal('show');
        });

        // Handle application type change for edit modal
        $('#editDiscountApplication').change(function() {
            if ($(this).val() === 'specific') {
                $('#editSpecificServicesContainer').show();
            } else {
                $('#editSpecificServicesContainer').hide();
            }
        });



        $('#discountTable').DataTable({
            ordering: false,
            lengthChange: true,
            "lengthMenu": [5, 10, 25, 50, 100],
            pageLength: 5
        });
    </script>

    <?php
    if (isset($_GET['message'])) {
        $message = $_GET['message'];

        if ($message == 'success') {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Discount added successfully!',
                confirmButtonText: 'OK'
            });
        </script>";
        } elseif ($message == 'error') {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was an error adding the discount. Please try again.',
                confirmButtonText: 'OK'
            });
        </script>";
        }
    }

    if (isset($_GET['editMessage'])) {
        $message = $_GET['editMessage'];

        if ($message == 'success') {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Discount updated successfully!',
                confirmButtonText: 'OK'
            });
        </script>";
        } elseif ($message == 'error') {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was an error updating the discount. Please try again.',
                confirmButtonText: 'OK'
            });
        </script>";
        }
    }
    ?>

</body>

</html>
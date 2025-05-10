<?php include('../includes/db.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-4">Discounts</h2>
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
                    <button class='btn btn-sm btn-warning edit-discount text-white bold' 
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
                            data-bs-toggle='modal' 
                            data-bs-target='#editDiscountModal'>
                        Edit
                    </button>
                    <button class='btn btn-sm btn-danger delete-discount text-white bold' data-id='{$row['id']}'>Delete</button>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


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
                            <input type="text" name="discount_code" id="editDiscountCode" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type</label>
                            <select name="discount_type" id="editDiscountType" class="form-select">
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
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
                            <label class="form-label">Status</label>
                            <select name="is_active" id="editDiscountStatus" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                    <div class="col-md-6" id="specific_services_container" style="display: none;">
                        <label>Select Services</label>
                        <select name="specific_service_ids[]" class="form-select" multiple>
                            <?php
                            $stmt = $pdo->prepare("SELECT service_id, service_name FROM services WHERE status = 'enabled'");
                            $stmt->execute();
                            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($services as $service) {
                                echo "<option value='{$service['service_id']}'>{$service['service_name']}</option>";
                            }
                            ?>
                        </select>
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

        $(document).ready(function() {
            // Handle edit button click
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

                // Populate modal fields
                $('#editDiscountId').val(discountId);
                $('#editDiscountName').val(discountName);
                $('#editDiscountCode').val(discountCode);
                $('#editDiscountType').val(discountType);
                $('#editDiscountValue').val(discountValue);
                $('#editDiscountApplication').val(discountApplication);
                $('#editDiscountStatus').val(discountActive ? '1' : '0');
                $('#editDiscountDescription').val(discountDescription);
                $('#editDiscountStart').val(discountStart);
                $('#editDiscountEnd').val(discountEnd);

                // Update currency symbol
                $('#valueSymbol').text(discountType === 'percentage' ? '%' : '$');

                // Update modal title
                $('#editModalLabel').text('Edit Discount: ' + discountName);
            });

            // Update currency symbol when type changes
            $('#editDiscountType').change(function() {
                $('#valueSymbol').text($(this).val() === 'percentage' ? '%' : '$');
            });
        });

        $('#discountTable').DataTable({
            ordering: false
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
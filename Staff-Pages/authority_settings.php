<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

// Fetch all users (DataTables will handle pagination client-side)
$query = "SELECT user_id, username, email, last_login, phone FROM account";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    </style>
</head>

<body>
    <?php
    $authority_settings = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="mt-5 pt-4 mb-3">
                <div class="card mt-3 p-3 me-3" style="border-radius: 10px; border: none !important;">
                    <h1 class="h2">User Accounts</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">
                            Total of User Accounts: <?php echo count($users); ?>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- Main content -->
            <div class="main-content p-0 pe-3">

                <!-- Users Table -->
                <div class="table-container">
                    <table id="usersTable" class="table table-hover table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Acc ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last Login</th>
                                <th>Phone number</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo str_pad($user['user_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php echo $user['last_login'] ? date('d/M/Y h:i A', strtotime($user['last_login'])) : 'Never'; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                                    <td class="text-center">
                                        <a class="text-purple view-user-btn font-1"
                                            data-user-id="<?php echo $user['user_id']; ?>">
                                            <ion-icon name="list-circle" class="admin-btn"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="left"
                                                title="Edit"></ion-icon>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">User ID:</label>
                                <p id="viewUserId" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Name:</label>
                                <p id="viewName" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Username:</label>
                                <p id="viewUsername" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Email:</label>
                                <p id="viewEmail" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Phone:</label>
                                <p id="viewPhone" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Address:</label>
                                <p id="viewAddress" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Gender:</label>
                                <p id="viewGender" class="text-muted"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Role:</label>
                                <p id="viewRole" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Last Login:</label>
                                <p id="viewLastLogin" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Email Verified:</label>
                                <p id="viewEmailVerified" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Account Created:</label>
                                <p id="viewCreatedAt" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Last Updated:</label>
                                <p id="viewUpdatedAt" class="text-muted"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Profile Picture:</label>
                                <img id="viewProfilePicture" src="" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
                                <p id="noProfilePicture" class="text-muted">No profile picture</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                responsive: true,
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                pageLength: 5,
                order: [
                    [0, 'asc']
                ],
                "ordering": false,
                dom: '<"top"lf>rt<"bottom"ip>',
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search users...",
                    lengthMenu: "Show _MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users found",
                    infoFiltered: "(filtered from _MAX_ total users)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Handle View More button click
            $(document).on('click', '.view-user-btn', function() {
                const userId = $(this).data('user-id');

                // Show loading state
                $('#viewUserModalLabel').text('Loading...');

                // Fetch user details via AJAX
                $.ajax({
                    url: '../backend/get_user_details.php',
                    method: 'POST',
                    data: {
                        user_id: userId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const user = response.data;

                            // Populate modal with user data
                            $('#viewUserModalLabel').text('User Details - ' + user.username);
                            $('#viewUserId').text(user.user_id);
                            $('#viewName').text(user.name || 'N/A');
                            $('#viewUsername').text(user.username);
                            $('#viewEmail').text(user.email);
                            $('#viewPhone').text(user.phone || 'N/A');
                            $('#viewAddress').text(user.address || 'N/A');
                            $('#viewGender').text(user.gender || 'N/A');
                            $('#viewRole').text(user.role);
                            $('#viewLastLogin').text(user.last_login ?
                                new Date(user.last_login).toLocaleString() : 'Never');
                            $('#viewEmailVerified').text(user.email_verified ? 'Yes' : 'No');
                            $('#viewCreatedAt').text(new Date(user.created_at).toLocaleString());
                            $('#viewUpdatedAt').text(user.updated_at ?
                                new Date(user.updated_at).toLocaleString() : 'Never');

                            // Handle profile picture
                            if (user.profile_picture) {
                                $('#viewProfilePicture').attr('src', '../' + user.profile_picture).show();
                                $('#noProfilePicture').hide();
                            } else {
                                $('#viewProfilePicture').hide();
                                $('#noProfilePicture').show();
                            }

                            // Show modal
                            $('#viewUserModal').modal('show');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching user details: ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>
<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../User-Pages/home.php");
    exit();
}

try {
    // Get all orders with user information
    $stmt = $pdo->prepare("
        SELECT o.*, u.username, u.email 
        FROM orders o
        JOIN account u ON o.user_id = u.user_id
        ORDER BY o.order_date DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
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
    $request = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="container-fluid mt-5">
                <div class="card">
                    <div class="card-header">
                        <h2>Order Management</h2>
                    </div>
                    <div class="card-body">
                        <table id="ordersTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['order_id']; ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($order['username']); ?><br>
                                            <small><?php echo htmlspecialchars($order['email']); ?></small>
                                        </td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?></td>
                                        <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge 
                                    <?php echo $order['status'] === 'pending' ? 'bg-warning' : ($order['status'] === 'paid' ? 'bg-info' : ($order['status'] === 'completed' ? 'bg-success' : 'bg-danger')); ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="order-details.php?id=<?php echo $order['order_id']; ?>"
                                                class="btn btn-sm btn-primary">View</a>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    Status
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item status-change"
                                                            href="#" data-status="pending">Pending</a></li>
                                                    <li><a class="dropdown-item status-change"
                                                            href="#" data-status="paid">Paid</a></li>
                                                    <li><a class="dropdown-item status-change"
                                                            href="#" data-status="completed">Completed</a></li>
                                                    <li><a class="dropdown-item status-change"
                                                            href="#" data-status="cancelled">Cancelled</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#ordersTable').DataTable();

                    $('.status-change').click(function(e) {
                        e.preventDefault();
                        const newStatus = $(this).data('status');
                        const orderId = $(this).closest('tr').find('td:first').text();

                        $.post('update-order-status.php', {
                            order_id: orderId,
                            status: newStatus
                        }, function(data) {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error: ' + (data.message || 'Failed to update status'));
                            }
                        });
                    });
                });
            </script>
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
        });
    </script>
</body>

</html>
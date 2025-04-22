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
    <title>Order Management</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php include("../components/admin-header.php"); ?>
    
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
                                    <?php echo $order['status'] === 'pending' ? 'bg-warning' : 
                                          ($order['status'] === 'paid' ? 'bg-info' : 
                                          ($order['status'] === 'completed' ? 'bg-success' : 'bg-danger')); ?>">
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
</body>
</html>
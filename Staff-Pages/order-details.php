<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../User-Pages/home.php");
    exit();
}

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    header("Location: orders.php");
    exit();
}

try {
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, u.username, u.email, u.phone,
               od.*
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_details od ON o.order_id = od.order_id
        WHERE o.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        throw new Exception("Order not found");
    }
    
    // Get order items
    $stmt = $pdo->prepare("
        SELECT oi.*, s.service_name, s.image
        FROM order_items oi
        JOIN services s ON oi.service_id = s.service_id
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    $items = $stmt->fetchAll();
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("../components/admin-header.php"); ?>
    
    <div class="container-fluid mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Order Details #<?php echo $orderId; ?></h2>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Order Information</h4>
                        <p><strong>Status:</strong> 
                            <span class="badge 
                                <?php echo $order['status'] === 'pending' ? 'bg-warning' : 
                                      ($order['status'] === 'paid' ? 'bg-info' : 
                                      ($order['status'] === 'completed' ? 'bg-success' : 'bg-danger')); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </p>
                        <p><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($order['order_date'])); ?></p>
                        <p><strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>Customer Information</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h4>Order Items</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="../uploads/<?php echo htmlspecialchars($item['image']); ?>" 
                                                     width="50" class="me-2">
                                                <?php echo htmlspecialchars($item['service_name']); ?>
                                            </div>
                                        </td>
                                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>₱<?php echo number_format($order['total_amount'], 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h4>Event Details</h4>
                        <p><strong>Celebrant:</strong> 
                            <?php echo htmlspecialchars($order['celebrant_first_name'] . ' ' . $order['celebrant_last_name']); ?>
                        </p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($order['celebrant_age']); ?></p>
                        <p><strong>Theme:</strong> <?php echo htmlspecialchars($order['event_theme']); ?></p>
                        <p><strong>Theme Color:</strong> <?php echo htmlspecialchars($order['event_theme_color']); ?></p>
                        <p><strong>Event Type:</strong> <?php echo htmlspecialchars($order['event_type']); ?></p>
                        <p><strong>Date & Time:</strong> 
                            <?php echo date('F j, Y g:i A', strtotime($order['event_datetime'])); ?>
                        </p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($order['event_location']); ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <h4>Client Details</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['client_name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['client_phone']); ?></p>
                        <p><strong>Address:</strong><br>
                            <?php echo htmlspecialchars($order['street']); ?>, 
                            <?php echo htmlspecialchars($order['barangay']); ?>, 
                            <?php echo htmlspecialchars($order['city']); ?>, 
                            <?php echo htmlspecialchars($order['province']); ?> - 
                            <?php echo htmlspecialchars($order['zip']); ?>
                        </p>
                        
                        <?php if ($order['payment_proof']): ?>
                        <h4 class="mt-4">Payment Proof</h4>
                        <img src="../uploads/payments/<?php echo htmlspecialchars($order['payment_proof']); ?>" 
                             class="img-fluid" style="max-height: 300px;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
            </div>
        </div>
    </div>
</body>
</html>
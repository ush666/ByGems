<?php
session_start();
require_once '../includes/db.php';

if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    header("Location: ../User-Pages/home.php");
    exit();
}

$orderId = $_GET['order_id'];
$userId = $_SESSION['user_id'];

try {
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, od.* 
        FROM orders o
        JOIN order_details od ON o.order_id = od.order_id
        WHERE o.order_id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $userId]);
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
    <title>Order Confirmation</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("../components/header.php"); ?>
    
    <div class="container my-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h2>Order Confirmation</h2>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h4>Thank you for your order!</h4>
                    <p>Your order #<?php echo $orderId; ?> has been received and is being processed.</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Summary</h4>
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
                    
                    <div class="col-md-6">
                        <h4>Event Details</h4>
                        <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['event_datetime'])); ?></p>
                        <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($order['event_datetime'])); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($order['event_location']); ?></p>
                        
                        <h4 class="mt-4">Contact Information</h4>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['client_name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['client_phone']); ?></p>
                    </div>
                </div>
                
                <?php if ($order['payment_proof']): ?>
                <div class="mt-4">
                    <h4>Payment Proof</h4>
                    <img src="../uploads/payments/<?php echo htmlspecialchars($order['payment_proof']); ?>" 
                         class="img-fluid" style="max-height: 300px;">
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="../User-Pages/home.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
    
    <?php include("../components/footer.php"); ?>
</body>
</html>
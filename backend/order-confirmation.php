<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_GET['order_id']) || !isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$orderId = $_GET['order_id'];

try {
    // Get order details
    $stmt = $pdo->prepare("
        SELECT * FROM event_request 
        WHERE order_id = :order_id AND user_id = :user_id
    ");
    $stmt->execute([':order_id' => $orderId, ':user_id' => $_SESSION['user_id']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Get order items
    $itemsStmt = $pdo->prepare("
        SELECT * FROM order_items 
        WHERE order_id = :order_id
    ");
    $itemsStmt->execute([':order_id' => $orderId]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

include("../components/header.php");
?>

<div class="container my-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2>Order Confirmation</h2>
                <p class="mb-0">Order ID: <?= htmlspecialchars($order['order_id']) ?></p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Details</h4>
                        <p><strong>Status:</strong> <?= ucfirst($order['request_status']) ?></p>
                        <p><strong>Payment Status:</strong> <?= ucfirst($order['payment_status']) ?></p>
                        <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
                        <p><strong>Total Amount:</strong> ₱<?= number_format($order['total_amount'], 2) ?></p>
                        <?php if ($order['payment_status'] === 'partial'): ?>
                            <p><strong>Deposit Paid:</strong> ₱<?= number_format($order['deposit_amount'], 2) ?></p>
                            <p><strong>Remaining Balance:</strong> ₱<?= number_format($order['remaining_balance'], 2) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h4>Event Details</h4>
                        <p><strong>Celebrant:</strong> <?= htmlspecialchars($order['celebrant_name']) ?></p>
                        <p><strong>Event Type:</strong> <?= htmlspecialchars($order['event_type']) ?></p>
                        <p><strong>Event Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['event_date'])) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($order['event_location']) ?></p>
                    </div>
                </div>

                <hr>

                <h4>Order Items</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['service_name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₱<?= number_format($item['price'], 2) ?></td>
                                    <td>₱<?= number_format($item['total_price'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th>₱<?= number_format($order['total_amount'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-center mt-4">
                    <a href="../user/dashboard.php" class="btn btn-primary">View in Dashboard</a>
                    <a href="../index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include("../components/footer.php"); ?>

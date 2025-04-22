<?php
session_start();
require_once '../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit;
}

// Validate event_id
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    echo "<p>Invalid event selected.</p>";
    exit;
}

$event_id = (int)$_GET['event_id'];

// Step 1: Get the order_id from event_request using event_id
$query = "SELECT order_id FROM event_request WHERE event_id = :event_id";
$stmt = $pdo->prepare($query);
$stmt->execute([':event_id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if ($event && $event['order_id']) {
    $order_id = $event['order_id'];

    // Step 2: Fetch full order details
    $orderStmt = $pdo->prepare("
        SELECT 
            o.order_id,
            o.user_id,
            o.order_date,
            o.total_amount AS order_total,
            o.payment_status,
            o.payment_reference,
            o.payment_image,
            er.event_id,
            er.celebrant_name,
            er.event_location,
            er.event_date,
            er.request_status,
            er.discounted_price,
            er.discount_percentage,
            er.total_amount AS event_total,
            er.deposit_amount,
            er.remaining_balance,
            er.payment_method,
            er.payment_proof,
            er.client_name,
            er.client_phone,
            er.client_address,
            er.event_theme,
            er.event_type,
            er.celebrant_age,
            er.celebrant_gender,
            er.created_at
        FROM orders o
        JOIN event_request er ON o.order_id = er.order_id
        WHERE o.order_id = :order_id
        LIMIT 1
    ");
    $orderStmt->execute([':order_id' => $order_id]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "<p>Order not found.</p>";
        exit;
    }

    // Step 3: Fetch all items of this order
    $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
    $itemsStmt->execute([':order_id' => $order_id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Step 4: Calculate amounts
    $subtotal = (float)$order['event_total'];
    $depositAmount = (float)$order['deposit_amount'];
    $remainingBalance = (float)$order['remaining_balance'];
    $discountAmount = $order['discounted_price'] ? ($subtotal - (float)$order['discounted_price']) : 0;
    $grandTotal = $subtotal - $discountAmount;

    // Step 5: Format dates
    $orderDate = !empty($order['order_date']) ? date('F j, Y', strtotime($order['order_date'])) : 'N/A';
    $eventDate = !empty($order['event_date']) ? date('F j, Y', strtotime($order['event_date'])) : 'N/A';

    // Step 6: Output the full HTML invoice
?>

    <!-- Modal -->
    <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="invoiceModalLabel">Invoice - Order #<?= htmlspecialchars($order['order_id']) ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body p-5 py-4">

        <!-- Start of Invoice Content -->
        <div class="row">
            <div class="invoice-header col-md-7">
                <div class="invoice-info">
                    <p><strong>Date:</strong> <?= $orderDate ?></p>
                    <p>
                        <strong>Status:</strong>
                        <span class="payment-status status-<?= strtolower($order['payment_status']) ?>">
                            <?= ucfirst($order['payment_status']) ?>
                        </span>
                    </p>
                </div>
            </div>

            <div class="company-info col-md-5">
                <?php if (!empty($order['payment_proof'])): ?>
                    <p class="d-flex justify-content-between align-items-center"><strong>Payment Proof:</strong>
                        <a href="javascript:void(0);" class="btn btn-purple text-white" onclick="showPaymentProof('<?= htmlspecialchars($order['payment_proof']) ?>')">View Receipt</a>
                    </p>
                <?php endif; ?>

                <p><strong class="me-3">Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
            </div>
        </div>

        <div class="section mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="section-title">Billing Information</h3>
                    <p><strong><?= htmlspecialchars($order['client_name']) ?></strong></p>
                    <p><?= htmlspecialchars($order['client_phone']) ?></p>
                    <p><?= htmlspecialchars($order['client_address']) ?></p>
                </div>
                <div class="col-md-6">
                    <h3 class="section-title">Event Information</h3>
                    <p class="d-flex justify-content-between"><strong>Celebrant:</strong> <?= htmlspecialchars($order['celebrant_name']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Event Type:</strong> <?= htmlspecialchars($order['event_type']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Event Date:</strong> <?= $eventDate ?></p>
                    <p class="d-flex justify-content-between"><strong>Location:</strong> <?= htmlspecialchars($order['event_location']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Theme:</strong> <?= htmlspecialchars($order['event_theme']) ?></p>
                </div>
            </div>
        </div>

        <div class="section mb-4">
            <h3 class="section-title">Order Details</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
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
            </table>
        </div>

        <div class="section mb-4">
            <table class="table">
                <tr>
                    <td>Subtotal:</td>
                    <td>₱<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php if ($discountAmount > 0): ?>
                    <tr>
                        <td>Discount:</td>
                        <td>-₱<?= number_format($discountAmount, 2) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td>Deposit Paid:</td>
                    <td>₱<?= number_format($depositAmount, 2) ?></td>
                </tr>
                <tr class="table-warning">
                    <td>Remaining Balance:</td>
                    <td>₱<?= number_format($remainingBalance, 2) ?></td>
                </tr>
                <tr class="table-success">
                    <td><strong>Grand Total:</strong></td>
                    <td><strong>₱<?= number_format($grandTotal, 2) ?></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
<?php
} else {
    echo "<p>Order not found for this event.</p>";
}
?>
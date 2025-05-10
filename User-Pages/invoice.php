<?php
$showSuccessAlert = false;
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $showSuccessAlert = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $orderId ?></title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <style>
        body {
            background-color: #f2f2f2;
        }

        .body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            border-top: 3px solid #4D3474;
        }

        .invoice-title {
            font-size: 28px;
            color: #2c3e50;
            margin: 0;
        }

        .invoice-info {
            text-align: right;
        }

        .company-info {
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 24px;
            color: #ffc107;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .two-columns {
            display: flex;
            justify-content: space-between;
        }

        .column {
            width: 40%;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
        }

        .highlight {
            background-color: #f8f9fa;
        }

        .payment-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-fullypaid {
            background-color: rgba(25, 135, 84, 0.77);
            color: #fff;
        }

        .status-partial {
            background-color: rgba(255, 193, 7, 0.79);
            color: #fff;
        }

        .print-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .print-btn:hover {
            background-color: #2980b9;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="pt-5 body">
        <div>
            <?php
            include("../components/header.php");
            ?>
            <?php
            // Check if order_id is provided
            if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
                header("Location: /orders.php");
                exit;
            }

            $orderId = (int)$_GET['order_id'];

            // Verify the order belongs to the logged-in user and get all data
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
                er.created_at,
                u.name AS user_name,
                u.email AS user_email,
                u.phone AS user_phone
            FROM orders o
            JOIN event_request er ON o.order_id = er.order_id
            JOIN account u ON o.user_id = u.user_id
            WHERE o.order_id = :order_id AND o.user_id = :user_id
            LIMIT 1
        ");
            $orderStmt->execute([
                ':order_id' => $orderId,
                ':user_id' => $_SESSION['user_id']
            ]);
            $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                header("Location: /orders.php");
                exit;
            }

            // Get order items
            $itemsStmt = $pdo->prepare("
            SELECT * FROM order_items 
            WHERE order_id = :order_id
        ");
            $itemsStmt->execute([':order_id' => $orderId]);
            $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate totals - using values from event_request table
            $subtotal = (float)$order['event_total'];
            $depositAmount = (float)$order['deposit_amount'];
            $remainingBalance = (float)$order['remaining_balance'];
            $discountAmount = $order['discounted_price'] ? ($subtotal - (float)$order['discounted_price']) : 0;
            $grandTotal = $subtotal - $discountAmount;

            // Format dates
            $orderDate = date('F j, Y', strtotime($order['order_date']));
            $eventDate = date('F j, Y', strtotime($order['event_date']));
            ?>
        </div>
        <div class="invoice-header my-5 pt-3">
            <div>
                <h1 class="invoice-title">Invoice</h1>
                <p>Order #<?= $order['order_id'] ?></p>
                <p>Request Status: <strong><?= $order['request_status'] ?></p></strong>

            </div>
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

        <div class="company-info">
            <h2 class="company-name">BYGEMS Party Kingdom</h2>
            <p>2F JBS Bldg, Mayor Jaldon Street, Canelar, <br> Zamboanga City, Zamboanga del Sur, Philippines</p>
            <p>Phone: 0917-857-4514 | Email: bygemspartykingdom@gmail.com</p>
        </div>

        <div class="section">
            <div class="two-columns">
                <div class="column">
                    <h3 class="section-title">Billing Information</h3>
                    <p><strong><?= htmlspecialchars($order['client_name']) ?></strong></p>
                    <p><?= htmlspecialchars($order['client_phone']) ?></p>
                    <p><?= htmlspecialchars($order['client_address']) ?></p>
                </div>
                <div class="column">
                    <h3 class="section-title">Event Information</h3>
                    <p class="d-flex justify-content-between"><strong>Celebrant:</strong> <?= htmlspecialchars($order['celebrant_name']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Event Type:</strong> <?= htmlspecialchars($order['event_type']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Event Date:</strong> <?= $eventDate ?></p>
                    <p class="d-flex justify-content-between"><strong>Location:</strong> <?= htmlspecialchars($order['event_location']) ?></p>
                    <p class="d-flex justify-content-between"><strong>Theme:</strong> <?= htmlspecialchars($order['event_theme']) ?></p>
                </div>
            </div>
        </div>

        <div class="section pt-3" style="border-top: 5px dashed #4D3474;">
            <h3 class="section-title">Order Details</h3>
            <table class="items-table">
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

        <div class="section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td>₱<?= number_format($subtotal, 2) ?></td>
                </tr>
                <?php if ($discountAmount > 0): ?>
                    <tr class="discount-row">
                        <td>
                            Discount <?= $order['discount_percentage'] ? "({$order['discount_percentage']}%)" : '' ?>:
                        </td>
                        <td>-₱<?= number_format($discountAmount, 2) ?></td>
                    </tr>
                <?php endif; ?>

                <?php if ($order['payment_status'] === 'partial'): ?>
                    <tr>
                        <td>Deposit Paid:</td>
                        <td>₱<?= number_format($depositAmount, 2) ?></td>
                    </tr>
                    <tr class="highlight">
                        <td>Remaining Balance:</td>
                        <td>₱<?= number_format($remainingBalance, 2) ?></td>
                    </tr>
                <?php elseif ($order['payment_status'] === 'fullypaid'): ?>
                    <tr class="highlight">
                        <td>Amount Paid:</td>
                        <td>₱<?= number_format($grandTotal, 2) ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong>Grand Total:</strong></td>
                    <td><strong>₱<?= number_format($grandTotal, 2) ?></strong></td>
                </tr>
            </table>

            <?php if ($order['payment_status'] === 'fullypaid'): ?>
                <div class="alert alert-success mt-3">
                    <i class="fas fa-check-circle"></i> Payment completed on <?= date('F j, Y', strtotime($order['order_date'])) ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="section">
            <h3 class="section-title">Payment Information</h3>
            <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
            <?php if ($order['payment_proof']): ?>
                <p><strong>Payment Proof:</strong>
                    <a href="javascript:void(0);" class="btn btn-purple text-white" onclick="showPaymentProof('<?= $order['payment_proof'] ?>')">View Receipt</a>
                </p>
            <?php endif; ?>
            <?php if ($order['payment_status'] === 'partial'): ?>
                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 10px;">
                    <h4 style="margin-top: 0;">Payment Instructions</h4>
                    <p>Please pay the remaining balance of <strong>₱<?= number_format($remainingBalance, 2) ?></strong> before the event date.</p>
                    <p>Payment methods: GCash, Bank Transfer, or Cash</p>
                    <p>For GCash payments, send to: 0917-857-4514 (ByGems)</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="no-print">
            <button class="print-btn" onclick="window.print()">Print Invoice</button>
            <a href="../User-Pages/invoice-list.php" class="btn btn-warning text-white" style="margin-left: 10px;">Back to Orders</a>
        </div>

    </div>
    <script>
        // Automatically print if ?print=1 is in URL
        if (window.location.search.includes('print=1')) {
            window.print();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showPaymentProof(imageUrl) {
            Swal.fire({
                title: 'Payment Proof',
                html: `<img src="${imageUrl}" style="max-width:300px; height:auto; border-radius:8px;">`,
                showConfirmButton: true,
                confirmButtonText: 'Close',
                width: '400px'
            });
        }
    </script>
    <?php include("../components/footer.php"); ?>
    <!-- Load SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($showSuccessAlert): ?>
        <script>
            Swal.fire({
                icon: "success",
                title: "Order placed successfully!",
                text: "Thank you for your order!",
                confirmButtonText: "OK",
                width: '450px'
            });
        </script>
    <?php endif; ?>
</body>

</html>
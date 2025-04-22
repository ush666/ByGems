<?php
session_start();
require_once __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Validate order_id
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit;
}

$orderId = (int) $_GET['order_id'];

try {
    // Fetch order (basic order) + fetch FIRST matching event_request
    $orderStmt = $pdo->prepare("
        SELECT o.order_id, o.user_id, o.order_date, o.total_amount, o.payment_status, o.payment_reference, o.payment_image,
               er.client_name, er.client_phone, er.client_address, er.celebrant_name, er.event_type, er.event_date, er.event_location, er.event_theme,
               er.payment_method, er.payment_proof, er.discounted_price, er.discount_percentage, er.deposit_amount, er.remaining_balance, er.created_at
        FROM orders o
        LEFT JOIN event_request er ON o.order_id = er.order_id
        WHERE o.order_id = :order_id AND o.user_id = :user_id
        LIMIT 1
    ");
    $orderStmt->execute([
        ':order_id' => $orderId,
        ':user_id' => $_SESSION['user_id']
    ]);

    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }

    // Fetch order items
    $itemsStmt = $pdo->prepare("
        SELECT service_name, quantity, price, total_price 
        FROM order_items 
        WHERE order_id = :order_id
    ");
    $itemsStmt->execute([':order_id' => $orderId]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculations
    $subtotal = (float) $order['total_amount'];
    $depositAmount = (float) $order['deposit_amount'];
    $remainingBalance = (float) $order['remaining_balance'];

    $discountAmount = 0;
    if (!empty($order['discounted_price'])) {
        $discountAmount = $subtotal - (float) $order['discounted_price'];
    }
    $grandTotal = $subtotal - $discountAmount;

    $orderDate = date('F j, Y', strtotime($order['created_at']));
    $eventDate = date('F j, Y', strtotime($order['event_date']));

    // Format response
    $response = [
        'success' => true,
        'order' => [
            'order_id' => $orderId,
            'created_at' => $orderDate,
            'payment_status' => $order['payment_status'],
            'payment_method' => $order['payment_method'],
            'payment_proof' => $order['payment_proof'],
            'client' => [
                'name' => $order['client_name'],
                'phone' => $order['client_phone'],
                'address' => $order['client_address']
            ],
            'event' => [
                'celebrant' => $order['celebrant_name'],
                'event_type' => $order['event_type'],
                'event_date' => $eventDate,
                'location' => $order['event_location'],
                'theme' => $order['event_theme'],
            ],
            'items' => $items,
            'financials' => [
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'deposit_paid' => $depositAmount,
                'remaining_balance' => $remainingBalance,
                'grand_total' => $grandTotal,
                'discount_percentage' => $order['discount_percentage'] ?? null,
            ]
        ]
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Something went wrong: ' . $e->getMessage()
    ]);
}
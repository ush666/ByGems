<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Get and validate input
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['cart_item_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

try {
    // Update status only if item belongs to user's cart
    $stmt = $pdo->prepare("
        UPDATE cart_items ci
        JOIN cart c ON ci.cart_id = c.id
        SET ci.status = :status
        WHERE ci.cart_item_id = :item_id
        AND c.user_id = :user_id
    ");
    
    $stmt->execute([
        ':status' => $data['status'] === 'active' ? 'active' : 'inactive',
        ':item_id' => $data['cart_item_id'],
        ':user_id' => $_SESSION['user_id']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Cart item not found or not owned by user'
        ]);
    }
} catch (PDOException $e) {
    error_log("Cart update error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
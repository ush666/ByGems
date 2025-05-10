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
if (empty($data['cart_item_id']) || empty($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

try {
    // Update status only if item belongs to user's cart
    $stmt = $pdo->prepare("
        UPDATE cart_items ci
        INNER JOIN cart c ON ci.cart_id = c.id
        SET ci.status = :status
        WHERE ci.cart_item_id = :item_id AND c.user_id = :user_id
    ");
    
    $stmt->execute([
        ':status' => $data['status'] === 'active' ? 'active' : 'inactive',
        ':item_id' => $data['cart_item_id'],
        ':user_id' => $_SESSION['user_id']
    ]);

    // Always return valid JSON, even if no rows were updated
    echo json_encode([
        'success' => true,
        'updated' => $stmt->rowCount() > 0,
        'message' => $stmt->rowCount() > 0 ? 'Status updated successfully' : 'No changes made (item may already have this status)'
    ]);
    
} catch (PDOException $e) {
    error_log("Cart update error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
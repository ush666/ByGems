<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ? AND cart_id IN (SELECT id FROM cart WHERE user_id = ?)");
    $stmt->execute([
        $data['cart_item_id'],
        $_SESSION['user_id']
    ]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
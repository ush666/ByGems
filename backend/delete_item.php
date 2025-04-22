<?php
// delete_item.php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM packages_services WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        echo json_encode(['success'=>true,'message'=>'Item deleted']);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
    }
    exit;
}

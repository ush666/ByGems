<?php
session_start();
require_once '../includes/db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = intval($_POST['service_id']);
    $status = ($_POST['status'] === 'enabled') ? 'enabled' : 'disabled';

    $query = "UPDATE services SET status = :status WHERE service_id = :service_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':status' => $status,
        ':service_id' => $service_id
    ]);

    echo json_encode(['success' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>

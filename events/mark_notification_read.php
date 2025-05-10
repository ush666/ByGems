<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['error' => 'Notification ID missing']);
    exit();
}

$notifId = $_POST['id'];

try {
    $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->execute([$notifId]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../User-Pages/home.php");
    exit();
}

if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);

    // Delete the service
    $stmt = $pdo->prepare("DELETE FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);

    header("Location: packages&services.php?success=" . urlencode("Service deleted successfully"));
    exit();
} else {
    header("Location: packages&services.php?success=" . urlencode("Invalid request"));
    exit();
}
?>

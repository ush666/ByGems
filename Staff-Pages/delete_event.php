<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../User-Pages/customer_dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete event query
    $stmt = $pdo->prepare("DELETE FROM event_request WHERE event_id = ?");
    if ($stmt->execute([$event_id])) {
        $_SESSION['success'] = "Event deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete event!";
    }
}

header("Location: event_management.php");
exit();
?>

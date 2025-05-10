<?php
session_start();
require_once '../includes/db.php'; // or your actual database connection

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);

    // Verify ownership first for safety
    $stmt = $pdo->prepare("
        SELECT er.request_status 
        FROM event_request er
        JOIN orders o ON er.order_id = o.order_id
        WHERE er.order_id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $_SESSION['user_id']]);
    $request = $stmt->fetch();

    if ($request && strtolower($request['request_status']) === 'pending') {
        // Cancel the event request
        $update = $pdo->prepare("UPDATE event_request SET request_status = 'cancelled' WHERE order_id = ?");
        $update->execute([$orderId]);

        $_SESSION['success_message'] = "Request canceled successfully.";
    } else {
        $_SESSION['error_message'] = "Unable to cancel. Request might already be processed.";
    }
}

header('Location: ../User-Pages/invoice-list.php'); // or wherever the orders list is
exit;

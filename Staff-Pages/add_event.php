<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

$event_datetime = $_POST['event_datetime'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $celebrant_name = $_POST['celebrant_name'];
    $event_location = $_POST['event_location'];
    $event_date = $_POST['event_date'];
    $payment_status = $_POST['payment_status'];
    $request_status = $_POST['request_status'];

    try {
        // Generate a new order_id (assuming order_id is numeric and auto-incrementing)
        $stmt = $pdo->query("SELECT MAX(order_id) AS max_order FROM event_request");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_order_id = ($row['max_order'] ?? 0) + 1; // If null, start from 1

        // Insert the new event with the generated order_id
        $stmt = $pdo->prepare("INSERT INTO event_request (order_id, user_id, celebrant_name, event_location, event_date, payment_status, request_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$new_order_id, $customer_id, $celebrant_name, $event_location, $event_date, $payment_status, $request_status]);

        header("Location: event_management.php?success=Event+added+successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

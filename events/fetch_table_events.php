<?php
session_start();
require_once '../includes/db.php';

// Only allow access if the user is logged in and not a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Validate input
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
}

$user_id = intval($_GET['user_id']);

$query = "SELECT 
            event_id,
            celebrant_name,
            event_location,
            event_date,
            request_status
          FROM event_request
          WHERE user_id = :user_id
          ORDER BY event_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Send JSON response
header('Content-Type: application/json');
echo json_encode($events);

<?php
require_once '../includes/db.php';  // Ensure correct database connection

// Fetch approved events from the database
$query = $pdo->query("
    SELECT event_id, celebrant_name, event_date 
    FROM event_request 
    WHERE request_status = 'Approved'
");

$events = [];

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['event_id'],
        'title' => $row['celebrant_name'],
        'start' => $row['event_date'],
        'backgroundColor' => '#28a745',    // Green color for approved events
        'borderColor' => '#28a745',
        'textColor' => '#fff'
    ];
}

// Return events as JSON
header('Content-Type: application/json');
echo json_encode($events);
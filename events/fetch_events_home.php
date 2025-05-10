<?php
require_once '../includes/db.php';  // Ensure correct database connection

// Fetch and count approved events grouped by date only (ignore time)
$query = $pdo->query("
    SELECT DATE(event_date) as event_date_only, COUNT(*) as event_count
    FROM event_request 
    WHERE request_status = 'approved'
    GROUP BY DATE(event_date)
");

$events = [];

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'title' => $row['event_count'] . ' event' . ($row['event_count'] > 1 ? 's' : ''),
        'start' => $row['event_date_only'], // Already in YYYY-MM-DD format
        'backgroundColor' => '#28a745',
        'borderColor' => '#28a745',
        'textColor' => '#fff',
        'allDay' => true
    ];
}

// Return events as JSON
header('Content-Type: application/json');
echo json_encode($events);
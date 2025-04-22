<?php
session_start();
require_once '../includes/db.php';

// Restrict access to staff and admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: login.php");
    exit();
}

// Maximum requests per day
$maxRequestsPerDay = 2;

// Query to fetch the count of requests per day
$query = "
    SELECT DATE(event_date) AS event_date, COUNT(*) as request_count
    FROM event_request
    GROUP BY event_date
    ORDER BY event_date ASC
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare the response data
    $orders = [];

    foreach ($result as $row) {
        // Store the count of requests for each date
        $orders[$row['event_date']] = $row['request_count'];
    }

    // Return the data as JSON
    echo json_encode(['orders' => $orders]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error fetching booking data: ' . $e->getMessage()]);
}

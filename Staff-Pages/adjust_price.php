<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: event_management.php");
    exit();
}

$event_id = $_GET['id'];

// Fetch total price from services_to_events
$stmt = $pdo->prepare("
    SELECT IFNULL(SUM(s.price * s.quantity), 0) AS total_price
    FROM services_to_events s
    WHERE s.event_id = ?
");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['error'] = "Event not found!";
    header("Location: event_management.php");
    exit();
}

$total_price = $event['total_price'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $discount = $_POST['discount'];

    if (!is_numeric($discount) || $discount < 0 || $discount > 100) {
        $_SESSION['error'] = "Invalid discount percentage! (0-100 allowed)";
        header("Location: adjust_price.php?id=" . $event_id);
        exit();
    }

    // Store the discount percentage in event_request
    $updateStmt = $pdo->prepare("UPDATE event_request SET discount_percentage = ? WHERE event_id = ?");
    if ($updateStmt->execute([$discount, $event_id])) {
        $_SESSION['success'] = "Discount applied successfully!";
    } else {
        $_SESSION['error'] = "Failed to apply discount!";
    }

    header("Location: event_management.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Discount</title>
    <link rel="stylesheet" href="cms_style.css">
</head>
<body>
    <div class="content">
        <h1>Apply Discount</h1>
        <p>Original Price: <strong>₱<?= number_format($total_price, 2); ?></strong></p>
        <form action="" method="post">
            <label for="discount">Discount Percentage:</label>
            <input type="number" name="discount" value="0" min="0" max="100" step="1" required> %

            <button type="submit">Apply Discount</button>
        </form>
    </div>
</body>
</html>

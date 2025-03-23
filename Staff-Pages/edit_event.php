<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: event_management.php?error=No+event+selected");
    exit();
}

$event_id = $_GET['id'];

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM event_request WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header("Location: event_management.php?error=Event+not+found");
    exit();
}

// Fetch customers for dropdown
$customers = $pdo->query("SELECT user_id, username FROM account WHERE role = 'customer'")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $celebrant_name = $_POST['celebrant_name'];
    $event_location = $_POST['event_location'];
    $event_datetime = $_POST['event_datetime'];
    $payment_status = $_POST['payment_status'];
    $request_status = $_POST['request_status'];

    try {
        // Update event details
        $stmt = $pdo->prepare("UPDATE event_request 
                               SET user_id = ?, celebrant_name = ?, event_location = ?, event_date = ?, payment_status = ?, request_status = ? 
                               WHERE event_id = ?");
        $stmt->execute([$customer_id, $celebrant_name, $event_location, $event_datetime, $payment_status, $request_status, $event_id]);

        header("Location: event_management.php?success=Event+updated+successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="cms_style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .edit-event-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="edit-event-container">
        <div class="card shadow-lg p-4">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">Edit Event</h3>
            </div>
            <div class="card-body">
                <form action="edit_event.php?id=<?= $event_id ?>" method="post">
                    <!-- Customer Dropdown -->
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer:</label>
                        <select name="customer_id" class="form-select" required>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['user_id'] ?>" <?= ($event['user_id'] == $customer['user_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($customer['username']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Celebrant Name -->
                    <div class="mb-3">
                        <label for="celebrant_name" class="form-label">Celebrant Name:</label>
                        <input type="text" class="form-control" name="celebrant_name" value="<?= htmlspecialchars($event['celebrant_name']) ?>" required>
                    </div>

                    <!-- Event Location -->
                    <div class="mb-3">
                        <label for="event_location" class="form-label">Location:</label>
                        <input type="text" class="form-control" name="event_location" value="<?= htmlspecialchars($event['event_location']) ?>" required>
                    </div>

                    <!-- Event Date & Time -->
                    <div class="mb-3">
                        <label for="event_datetime" class="form-label">Event Date & Time:</label>
                        <input type="datetime-local" class="form-control" id="event_datetime" name="event_datetime" 
                               value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
                    </div>

                    <!-- Payment Status -->
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status:</label>
                        <select name="payment_status" class="form-select">
                            <option value="Pending" <?= ($event['payment_status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="Partial" <?= ($event['payment_status'] == 'Partial') ? 'selected' : '' ?>>Partial</option>
                            <option value="Paid" <?= ($event['payment_status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
                        </select>
                    </div>

                    <!-- Request Status -->
                    <div class="mb-3">
                        <label for="request_status" class="form-label">Request Status:</label>
                        <select name="request_status" class="form-select">
                            <option value="Pending" <?= ($event['request_status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="Approved" <?= ($event['request_status'] == 'Approved') ? 'selected' : '' ?>>Approved</option>
                            <option value="Rejected" <?= ($event['request_status'] == 'Rejected') ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end">
                        <a href="event_management.php" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ensure the event datetime cannot be in the past
            document.getElementById("event_datetime").setAttribute("min", new Date().toISOString().slice(0, 16));
        });
    </script>
</body>
</html>

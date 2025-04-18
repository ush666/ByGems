<?php
session_start();
require_once '../includes/db.php';

// Check if this is an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    if ($isAjax) {
        http_response_code(403);
        echo "<div class='alert alert-danger'>Unauthorized access.</div>";
    } else {
        header("Location: ../index.php");
    }
    exit();
}

if (!isset($_GET['id'])) {
    if ($isAjax) {
        http_response_code(400);
        echo "<div class='alert alert-danger'>No event selected.</div>";
    } else {
        header("Location: ../Staff-Pages/event_management.php?error=No+event+selected");
    }
    exit();
}

$event_id = $_GET['id'];

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM event_request WHERE event_id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    if ($isAjax) {
        http_response_code(404);
        echo "<div class='alert alert-danger'>Event not found.</div>";
    } else {
        header("Location: ../Staff-Pages/event_management.php?error=Event+not+found");
    }
    exit();
}

// Fetch customers for dropdown
$customers = $pdo->query("SELECT user_id, username FROM account WHERE role = 'customer'")->fetchAll(PDO::FETCH_ASSOC);

// Return form only for AJAX
if ($isAjax):
?>

<form action="../events/edit_table_event.php?id=<?= $event['event_id'] ?>" method="POST">
    <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">

    <div class="mb-3">
        <label for="customer_id" class="form-label">Customer</label>
        <select name="customer_id" class="form-select" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['user_id'] ?>" <?= $customer['user_id'] == $event['user_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($customer['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="celebrant_name" class="form-label">Celebrant Name</label>
        <input type="text" class="form-control" name="celebrant_name" value="<?= htmlspecialchars($event['celebrant_name']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="event_location" class="form-label">Event Location</label>
        <input type="text" class="form-control" name="event_location" value="<?= htmlspecialchars($event['event_location']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="event_datetime" class="form-label">Event Date & Time</label>
        <input type="datetime-local" class="form-control" name="event_datetime"
               value="<?= date('Y-m-d\TH:i', strtotime($event['event_date'])) ?>" required>
    </div>

    <div class="mb-3">
        <label for="payment_status" class="form-label">Payment Status</label>
        <select name="payment_status" class="form-select">
            <option value="pending" <?= $event['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="paid" <?= $event['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="request_status" class="form-label">Request Status</label>
        <select name="request_status" class="form-select">
            <option value="approved" <?= $event['request_status'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="pending" <?= $event['request_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="rejected" <?= $event['request_status'] === 'Rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Event</button>
</form>

<?php
exit;
endif;

// If not AJAX, process form submission and redirect
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $celebrant_name = $_POST['celebrant_name'];
    $event_location = $_POST['event_location'];
    $event_datetime = $_POST['event_datetime'];
    $payment_status = $_POST['payment_status'];
    $request_status = $_POST['request_status'];

    try {
        $stmt = $pdo->prepare("UPDATE event_request 
                               SET user_id = ?, celebrant_name = ?, event_location = ?, event_date = ?, payment_status = ?, request_status = ? 
                               WHERE event_id = ?");
        $stmt->execute([$customer_id, $celebrant_name, $event_location, $event_datetime, $payment_status, $request_status, $event_id]);

        header("Location: ../Staff-Pages/event_management.php?success=Event+updated+successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

$user_role = $_SESSION['role'];

// Fetch all event requests
$query = "SELECT e.event_id, e.order_id, c.username, e.celebrant_name, e.event_location, e.event_date, 
            e.payment_status, e.request_status, 
            IFNULL(SUM(s.price * s.quantity), 0) AS total_price,
            IFNULL(e.discounted_price, 0) AS discounted_price,
            IFNULL(e.discount_percentage, 0) AS discount_percentage,
            IFNULL(ROUND(SUM(s.price * s.quantity) * (1 - e.discount_percentage / 100), 2), 0) AS final_price
        FROM event_request e
        JOIN account c ON e.user_id = c.user_id
        LEFT JOIN services_to_events s ON e.event_id = s.event_id
        GROUP BY e.event_id, e.order_id, c.username, e.celebrant_name, e.event_location, e.event_date, 
                 e.payment_status, e.request_status, e.discounted_price, e.discount_percentage
        ORDER BY e.event_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - ByGems CMS</title>
    <link rel="stylesheet" href="cms_style.css?v=<?= time(); ?>">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>ByGems CMS</h2>
        <ul>
            <li><a href="cms_dashboard.php">Dashboard</a></li>
            <li><a href="event_management.php" class="active">Manage Events</a></li>
            <?php if ($user_role == 'admin') : ?>
                <li><a href="services_management.php">Manage Services</a></li>
                <li><a href="content_management.php">Manage Content</a></li>
                <li><a href="user_management.php">Manage Users</a></li>
            <?php endif; ?>
            <li><a href="../includes/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Manage Events</h1>
        <button id="addEventBtn">+ Add Event</button>

        <!-- Add Event Modal -->
        <div id="addEventForm" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add New Event</h2>
                <form action="add_event.php" method="post">
                    <label for="customer_id">Customer:</label>
                    <select name="customer_id" required>
                        <?php
                        $customers = $pdo->query("SELECT user_id, username FROM account WHERE role = 'customer'")->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($customers as $customer) {
                            echo "<option value='{$customer['user_id']}'>{$customer['username']}</option>";
                        }
                        ?>
                    </select>

                    <label for="celebrant_name">Celebrant Name:</label>
                    <input type="text" name="celebrant_name" required>

                    <label for="event_location">Location:</label>
                    <input type="text" name="event_location" required>

                    <label for="event_datetime">Event Date:</label>
                    <input type="datetime-local" name="event_datetime" id="event_datetime" required>

                    <label for="payment_status">Payment Status:</label>
                    <select name="payment_status">
                        <option value="Pending">Pending</option>
                        <option value="Partial">Partial</option>
                        <option value="Paid">Paid</option>
                    </select>

                    <label for="request_status">Request Status:</label>
                    <select name="request_status">
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>

                    <button type="submit">Add Event</button>
                </form>
            </div>
        </div>

        <table id="eventTable" class="display">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Celebrant</th>
                    <th>Location</th>
                    <th>Event Date</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Final Price</th>
                    <th>Payment Status</th>
                    <th>Request Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event) : ?>
                <tr>
                    <td><?= htmlspecialchars($event['order_id']); ?></td>
                    <td><?= htmlspecialchars($event['username']); ?></td>
                    <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                    <td><?= htmlspecialchars($event['event_location']); ?></td>
                    <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                    <td>₱<?= number_format($event['total_price'], 2); ?></td>
                    <td><?= number_format($event['discount_percentage'], 2); ?>%</td>
                    <td>₱<?= number_format($event['final_price'], 2); ?></td>
                    <td><?= htmlspecialchars($event['payment_status']); ?></td>
                    <td><?= htmlspecialchars($event['request_status']); ?></td>
                    <td>
                        <a href="edit_event.php?id=<?= $event['event_id']; ?>">Edit</a>
                        <?php if ($user_role == 'admin') : ?>
                            | <a href="adjust_price.php?id=<?= $event['event_id']; ?>">Adjust Discount</a>
                        <?php endif; ?>
                        | <a href="delete_event.php?id=<?= $event['event_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('#eventTable').DataTable();

            const modal = document.getElementById("addEventForm");
            const btn = document.getElementById("addEventBtn");
            const span = document.getElementsByClassName("close")[0];

            // ✅ Ensure modal is hidden on page load
            modal.style.display = "none";  

            btn.onclick = function () { 
                modal.style.display = "block"; 
            };
            
            span.onclick = function () { 
                modal.style.display = "none"; 
            };

            window.onclick = function (event) { 
                if (event.target === modal) modal.style.display = "none"; 
            };

            // ✅ Set min datetime for event scheduling
            document.getElementById("event_datetime").setAttribute("min", new Date().toISOString().slice(0, 16));
        });

        document.addEventListener("DOMContentLoaded", function () {
            $('#eventTable').DataTable();

            const modal = document.getElementById("addEventForm");
            const btn = document.getElementById("addEventBtn");
            const span = document.getElementsByClassName("close")[0];

            btn.onclick = function () { modal.style.display = "block"; };
            span.onclick = function () { modal.style.display = "none"; };
            window.onclick = function (event) { if (event.target === modal) modal.style.display = "none"; };

            document.getElementById("event_datetime").setAttribute("min", new Date().toISOString().slice(0, 16));
        });
    </script>
</body>
</html>

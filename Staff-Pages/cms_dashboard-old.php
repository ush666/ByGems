<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../Staff-Pages/staff_login.php");
    exit();
}

$user_role = $_SESSION['role'];

$bookedEvents = $pdo->query("
    SELECT event_id, celebrant_name, event_date 
    FROM event_request 
    WHERE request_status = 'Approved'
")->fetchAll(PDO::FETCH_ASSOC);

if ($user_role == 'staff') {
    $total_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE payment_status = 'Paid'")->fetchColumn() ?: 0;
    $monthly_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE MONTH(paid_date) = MONTH(CURRENT_DATE) AND YEAR(paid_date) = YEAR(CURRENT_DATE) AND payment_status = 'Paid'")->fetchColumn() ?: 0;
    $yearly_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE YEAR(paid_date) = YEAR(CURRENT_DATE) AND payment_status = 'Paid'")->fetchColumn() ?: 0;
    $pending_payments = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE payment_status != 'Paid'")->fetchColumn() ?: 0;
}

$eventRequests = $pdo->query("
    SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status 
    FROM event_request e 
    JOIN account c ON e.user_id = c.user_id 
    ORDER BY e.event_date DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems CMS Dashboard</title>
        <link rel="stylesheet" href="cms_style.css?v=<?= time(); ?>">

        <!-- DataTables & FullCalendar -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

        <!-- jQuery & DataTables -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- FullCalendar -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    </head>

    <body>
        <div class="sidebar">
            <h2>ByGems CMS</h2>
            <ul>
                <li><a href="cms_dashboard.php">Dashboard</a></li>
                <li><a href="event_management.php">Manage Events</a></li>
                <?php if ($user_role == 'admin') : ?>
                <li><a href="services_management.php">Manage Services</a></li>
                <li><a href="content_management.php">Manage Content</a></li>
                <li><a href="user_management.php">Manage Users</a></li>
                <?php endif; ?>
                <li><a href="../includes/logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Unknown'); ?></h1>
            <p>Your role: <strong><?= ucfirst(htmlspecialchars($user_role)); ?></strong></p>

            <?php if ($user_role == 'admin') : ?>
            <div class="dashboard-summary">
                <div class="box">
                    <h3>Monthly Revenue</h3>
                    <p>₱<?= number_format($monthly_revenue, 2); ?></p>
                </div>
                <div class="box">
                    <h3>Yearly Revenue</h3>
                    <p>₱<?= number_format($yearly_revenue, 2); ?></p>
                </div>
                <div class="box">
                    <h3>Total Revenue</h3>
                    <p>₱<?= number_format($total_revenue, 2); ?></p>
                </div>
            </div>
            <button onclick="window.location.href='export_pdf.php'">Export Finances to PDF</button>
            <?php endif; ?>

            <h2>Recent Event Requests</h2>
            <table id="eventTable" class="display">
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Customer</th>
                        <th>Celebrant</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventRequests as $event) : ?>
                    <tr>
                        <td><?= htmlspecialchars($event['event_id']); ?></td>
                        <td><?= htmlspecialchars($event['username']); ?></td>
                        <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                        <td><?= htmlspecialchars(date('F d, Y', strtotime($event['event_date']))); ?></td>
                        <td><?= htmlspecialchars($event['request_status']); ?></td>
                        <td><a href="edit_event.php?id=<?= $event['event_id']; ?>">Manage</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button onclick="window.location.href='export_events.php'">Export Event Requests to PDF</button><br><br>

            <h2>Booked Dates</h2>
            <div id="calendar"></div><br><br>
        </div>


        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 400,
                contentHeight: 'auto',
                events: [
                    <?php 
                        $eventCount = count($bookedEvents);
                        $counter = 0;
                        foreach ($bookedEvents as $event) : 
                            $counter++;
                        ?> {
                        title: "<?= htmlspecialchars($event['celebrant_name']); ?>",
                        start: "<?= $event['event_date']; ?>",
                        backgroundColor: "#1cff1c",
                        borderColor: "#1cff1c",
                        textColor: "#ffffff"
                    }
                    <?= $counter < $eventCount ? ',' : '' ?> < !--Avoid trailing comma-- >
                    <?php endforeach; ?>
                ]
            });
            calendar.render();
        });

        $(document).ready(function() {
            $('#eventTable').DataTable();
        });
        </script>

    </body>

</html>
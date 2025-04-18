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

if ($user_role == 'admin') {
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


$filter = $_GET['filter'] ?? 'all'; // default to today

switch ($filter) {
    case 'week':
        $eventRequests = $pdo->query("
            SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status 
            FROM event_request e 
            JOIN account c ON e.user_id = c.user_id 
            WHERE WEEK(e.event_date) = WEEK(CURDATE()) 
              AND YEAR(e.event_date) = YEAR(CURDATE())
            ORDER BY e.event_date DESC
            LIMIT 100
        ")->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'month':
        $eventRequests = $pdo->query("
            SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status 
            FROM event_request e 
            JOIN account c ON e.user_id = c.user_id 
            WHERE MONTH(e.event_date) = MONTH(CURDATE()) 
              AND YEAR(e.event_date) = YEAR(CURDATE())
            ORDER BY e.event_date DESC
            LIMIT 100
        ")->fetchAll(PDO::FETCH_ASSOC);
        break;

    case 'today':
        $eventRequests = $pdo->query("
            SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status 
            FROM event_request e 
            JOIN account c ON e.user_id = c.user_id 
            WHERE DATE(e.event_date) = CURDATE()
            ORDER BY e.event_date DESC
            LIMIT 100
        ")->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 'all':
    default:
        $eventRequests = $pdo->query("
            SELECT e.event_id, c.username, e.celebrant_name, e.event_date, e.request_status 
            FROM event_request e 
            JOIN account c ON e.user_id = c.user_id
            ORDER BY e.event_date DESC
            LIMIT 100
        ")->fetchAll(PDO::FETCH_ASSOC);
        break;
}
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body style="overflow-x: hidden;">
    <?php
    $dashboard = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="row mb-1 mt-5 p-0 pe-3 pt-4 me-3">
                <div class="col-12 card mt-3 p-3 ms-3" style="border-radius: 10px; border: none !important;">
                    <h1 class="h2">Dashboard</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">Dashboard</div>
                    </nav>
                </div>
            </div>
            <div class="container mt-3">
                <!-- Analytics -->
                <h2 class="section-title mb-3">
                    <div class="card text-white label-section">Analytics</div>
                </h2>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Online Visitors <br> <strong>689</strong></p>
                                </div>
                                <div class="icon-people">
                                    <ion-icon name="people-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs"><ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon> 8.5% Up from yesterday</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Booked Events <br> <strong>29</strong></p>
                                </div>
                                <div class="icon-box">
                                    <ion-icon name="cube-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs"><ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon> 1.3% Up from last month</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Sales for the <br> Week <strong>₱39,000</strong></p>
                                </div>
                                <div class="icon-analytics">
                                    <ion-icon name="analytics-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-danger tabs"><ion-icon name="trending-down-outline" style="font-size: large;"></ion-icon> 4.3% Down from last week</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Event Requests <br> <strong>40</strong></p>
                                </div>
                                <div class="icon-timer">
                                    <ion-icon name="timer-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs"><ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon> 1.8% Up from yesterday</small>
                        </div>
                    </div>
                </div>

                <!-- Chart and Top Services -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h4 class="mb-2 mt-1">Most Availed Services</h4>
                            <img src="../img/Image-0.png" alt="Party Host" class="img-fluid rounded">
                            <p class="mt-2 mb-0 d-flex flex-column align-items-center">Party Host <strong class="text-primary">$89.00</strong></p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card p-3">
                            <h5>Top Services for the Week</h5>
                            <canvas id="servicesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- UPCOMING EVENT SECTION -->
            <section id="table" class="p-4 bg-white shadow rounded-4 mb-4 me-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="section-title m-0">
                        <div class="card text-white label-section">Upcoming Event</div>
                    </h2>
                    <span class="text-muted">Total payable amount:
                        <strong class="text-primary">
                            ₱<?= number_format($total_revenue ?? 0, 2) ?>
                        </strong>
                    </span>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link <?= ($filter == 'all') ? 'active' : '' ?>" href="?filter=all#table">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($filter == 'today') ? 'active' : '' ?>" href="?filter=today#table">Today</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($filter == 'week') ? 'active' : '' ?>" href="?filter=week#table">For The Week</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($filter == 'month') ? 'active' : '' ?>" href="?filter=month#table">For The Month</a>
                    </li>
                </ul>

                <!-- Search + Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="input-group" style="width: 35%;">
                        <input type="text" class="form-control" placeholder="Search Users by Name, Email or Date">
                        <button class="btn btn-outline-secondary">Filter</button>
                    </div>
                    <button class="btn btn-primary">Add New</button>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle table-striped table-hover" id="eventTable">
                        <thead class="table-light">
                            <tr>
                                <!--<th><input type="checkbox"></th>-->
                                <th>Event ID</th>
                                <th>Client Name</th>
                                <th>Celebrant</th>
                                <th>Event Date</th>
                                <th>Status</th>
                                <!--<th>Amount Paid</th>-->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($eventRequests) > 0): ?>
                                <?php foreach ($eventRequests as $event): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($event['event_id']) ?></td>
                                        <td><?= htmlspecialchars($event['username']) ?></td>
                                        <td><?= htmlspecialchars($event['celebrant_name']) ?></td>
                                        <td><?= htmlspecialchars(date('F d, Y', strtotime($event['event_date']))) ?></td>
                                        <td>
                                            <span style="width: 55%;" class="badge 
                                    <?= $event['request_status'] === 'Approved' ? 'bg-success' : ($event['request_status'] === 'Pending' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                                <?= htmlspecialchars($event['request_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_event.php?id=<?= urlencode($event['event_id']) ?>" class="btn btn-sm btn-outline-primary">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No upcoming events found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="text-end text-muted small">Rows per page: 10 &nbsp; | &nbsp; 1-100 of <?= count($eventRequests) ?></div>
                </div>
            </section>
            <!-- NOTIFICATIONS AND CALENDAR SECTION -->
            <div class="row g-4 mb-4 pe-3">
                <div class="col-md-6">
                    <section class="bg-white shadow rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3">Notifications</h5>
                        <div class="mb-3 p-3 rounded-3 bg-light">
                            <p class="mb-1">New Chat from Jeyward</p>
                            <small class="text-muted">Date yesterday</small>
                            <div class="text-end"><button class="btn btn-sm btn-outline-primary">View Details</button></div>
                        </div>
                        <div class="p-3 rounded-3 bg-light">
                            <p class="mb-1">Jeyward Paid in Full</p>
                            <small class="text-muted">Date yesterday</small>
                            <div class="text-end"><button class="btn btn-sm btn-outline-primary">View Details</button></div>
                        </div>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="bg-white shadow rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3">Calendar</h5>
                        <div id="calendar"></div>
                        <div class="mb-3 p-3 rounded-3 bg-light">
                            <p class="mb-1">Meeting with new Applicants</p>
                            <small>Date Thu May 18, 2024</small>
                            <div class="text-end"><button class="btn btn-sm btn-outline-primary">View Details</button></div>
                        </div>
                        <div class="p-3 rounded-3 bg-light">
                            <p class="mb-1">Meeting with MR Perez</p>
                            <small>Date Thu Mar 20, 2024</small>
                            <div class="text-end"><button class="btn btn-sm btn-outline-primary">View Details</button></div>
                        </div>
                        <div class="text-end"><button class="btn btn-success btn-sm">Add New</button></div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php
    //include("../components/footer.php");
    ?>

    <script>
        const ctx = document.getElementById('servicesChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Party Host', 'Magician', 'Mascot', 'Gemster', 'Magician', 'Mascot', 'Gemster', 'Magician', 'Mascot', 'Gemster'],
                datasets: [{
                    label: 'Top Services',
                    data: [70, 55, 50, 40, 40, 35, 35, 30, 20, 20],
                    backgroundColor: ['#C06DBF', '#65629B', '#9D7A99', '#A2D2FC'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                contentHeight: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: '../events/fetch_events.php', // Dynamic events
                eventColor: '#6366f1',
                eventTextColor: '#fff',
                selectable: true,
                dateClick: function(info) {
                    alert('You clicked on: ' + info.dateStr);
                }
            });

            calendar.render();
        });
    </script>
    <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
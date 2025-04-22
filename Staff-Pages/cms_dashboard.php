<?php
session_start();
require_once '../includes/db.php';

// Redirect if not logged in or if customer
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Staff-Pages/staff_login.php");
    exit();
}

$user_role = $_SESSION['role'];

// Get analytics data
$total_visitors = $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitor_logs")->fetchColumn() ?: 0;
$today_visitors = $pdo->query("SELECT COUNT(DISTINCT ip_address) FROM visitor_logs WHERE DATE(visit_time) = CURDATE()")->fetchColumn() ?: 0;

$total_booked_events = $pdo->query("SELECT COUNT(*) FROM event_request WHERE request_status = 'Approved'")->fetchColumn() ?: 0;
$monthly_booked_events = $pdo->query("SELECT COUNT(*) FROM event_request WHERE request_status = 'Approved' AND MONTH(event_date) = MONTH(CURRENT_DATE)")->fetchColumn() ?: 0;

$total_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE payment_status = 'Paid'")->fetchColumn() ?: 0;
$monthly_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE MONTH(paid_date) = MONTH(CURRENT_DATE) AND payment_status = 'Paid'")->fetchColumn() ?: 0;
$weekly_revenue = $pdo->query("SELECT SUM(amount_paid) FROM payment WHERE WEEK(paid_date) = WEEK(CURRENT_DATE) AND payment_status = 'Paid'")->fetchColumn() ?: 0;

$total_requests = $pdo->query("SELECT COUNT(*) FROM event_request")->fetchColumn() ?: 0;
$pending_requests = $pdo->query("SELECT COUNT(*) FROM event_request WHERE request_status = 'Pending'")->fetchColumn() ?: 0;

// Get top services
$top_services = $pdo->query("
    SELECT s.service_name, s.image, COUNT(oi.service_id) as service_count 
    FROM order_items oi
    JOIN services s ON oi.service_id = s.service_id
    GROUP BY oi.service_id 
    ORDER BY service_count DESC 
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Get service prices for chart
$service_prices = [];
foreach ($top_services as $service) {
    $price = $pdo->query("SELECT price FROM services WHERE service_name = '{$service['service_name']}'")->fetchColumn();
    $service_prices[] = $price;
}

// Get event requests based on filter
$filter = $_GET['filter'] ?? 'all';

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

// Get notifications
$notifications = $pdo->query("
    SELECT * FROM notifications 
    WHERE recipient_id = {$_SESSION['user_id']} OR recipient_id IS NULL
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Get calendar events
$calendar_events = $pdo->query("
    SELECT event_id as id, celebrant_name as title, event_date as start, 
           CONCAT(event_date, ' 23:59:59') as end, '#6366f1' as color 
    FROM event_request 
    WHERE request_status = 'Approved'
    UNION
    SELECT appointment_id as id, CONCAT('Meeting with ', client_name) as title, 
           appointment_date as start, CONCAT(appointment_date, ' 23:59:59') as end, '#10b981' as color 
    FROM appointments
")->fetchAll(PDO::FETCH_ASSOC);

// Prepare calendar events for JSON
$calendar_events_json = json_encode($calendar_events);
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
    <link rel="stylesheet" href="../css/admin.css">
</head>

<style>
    @media print {

        /* Hide header and sidebar */
        .admin-header,
        .admin-sidebar {
            display: none !important;
        }

        /* Adjust the main content to take full width */
        .body-container {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }

        /* Remove padding and margins for printing */
        .container-fluid {
            width: 100% !important;
            padding-left: 15px !important;
            padding-right: 15px !important;
            margin-left: 0 !important;
        }

        /* Make sure cards don't break across pages */
        .card {
            page-break-inside: avoid;
        }

        /* Add some padding to the top of the printed page */
        body {
            padding-top: 20px !important;
        }

        /* Hide any buttons or interactive elements */
        .dropdown,
        .btn {
            display: none !important;
        }
    }
</style>

<body style="overflow-x: hidden;">
    <?php
    $dashboard = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php include("../components/admin-sidebar.php"); ?>
        <div class="d-flex flex-column" style="width: 100vw; padding-left: 300px;">
            <div class="row mb-1 mt-5 p-0 pe-3 pt-4 me-3">
                <div class="col-12 card mt-3 p-3 ms-3" style="border-radius: 10px; border: none !important;">
                    <div class="row">
                        <h1 class="h2 col-6">Dashboard</h1>
                        <div class="text-end mb-3 col-6">
                            <button onclick="window.print()" class="btn btn-purple">
                                <i class="fas fa-print me-2"></i>Print Dashboard
                            </button>
                        </div>
                    </div>
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
                                    <p class="mb-1">Online Visitors <br><strong><?= $today_visitors ?></strong></p>
                                    <small>Total: <?= $total_visitors ?></small>
                                </div>
                                <div class="icon-people">
                                    <ion-icon name="people-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs">
                                <ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon>
                                <?= round(($today_visitors / max(1, $total_visitors)) * 100) ?>% Today
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Booked Events <strong><?= $total_booked_events ?></strong></p>
                                    <small>This Month: <?= $monthly_booked_events ?></small>
                                </div>
                                <div class="icon-box">
                                    <ion-icon name="cube-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs">
                                <ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon>
                                <?= round(($monthly_booked_events / max(1, $total_booked_events)) * 100) ?>% Monthly
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Revenue <strong>₱<?= number_format($total_revenue, 2) ?></strong></p>
                                    <small>This Week: ₱<?= number_format($weekly_revenue, 2) ?></small>
                                </div>
                                <div class="icon-analytics">
                                    <ion-icon name="analytics-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs">
                                <ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon>
                                <?= round(($weekly_revenue / max(1, $total_revenue)) * 100) ?>% Weekly
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between">
                                <div class="flex-column">
                                    <p class="mb-1">Total Event Requests <strong><?= $total_requests ?></strong></p>
                                    <small>Pending: <?= $pending_requests ?></small>
                                </div>
                                <div class="icon-timer">
                                    <ion-icon name="timer-outline" size="large"></ion-icon>
                                </div>
                            </div>
                            <small class="text-success tabs">
                                <ion-icon name="trending-up-outline" style="font-size: large;"></ion-icon>
                                <?= round(($pending_requests / max(1, $total_requests)) * 100) ?>% Pending
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Chart and Top Services -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card p-3">
                            <h4 class="mb-2 mt-1">Most Availed Services</h4>
                            <?php if (!empty($top_services)): ?>
                                <img src="../uploads/<?= htmlspecialchars(strtolower(str_replace(' ', ' ', $top_services[0]['image']))) ?>"
                                    alt="<?= htmlspecialchars($top_services[0]['service_name']) ?>"
                                    class="img-fluid rounded">
                                <p class="mt-2 mb-0 d-flex flex-column align-items-center">
                                    <?= htmlspecialchars($top_services[0]['service_name']) ?>
                                    <strong class="text-primary">₱<?= number_format($service_prices[0], 2) ?></strong>
                                </p>
                            <?php else: ?>
                                <p class="text-muted">No services data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card p-3">
                            <h5>Top Services</h5>
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
                        <strong class="text-primary">₱<?= number_format($total_revenue, 2) ?></strong>
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
                    <div class="input-group" style="width: 42%;">
                        <input type="text" class="form-control" placeholder="Search Events by Name or Date">
                        <button class="btn btn-outline-secondary">Filter</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle table-striped table-hover" id="eventTable">
                        <thead class="table-light">
                            <tr>
                                <th>Event ID</th>
                                <th>Client Name</th>
                                <th>Celebrant</th>
                                <th>Event Date</th>
                                <th>Status</th>
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
                                            <span style="width: 75%;" class="badge 
                                                <?= $event['request_status'] === 'approved' ? 'bg-success' : ($event['request_status'] === 'pending' ? 'bg-warning text-white' : 'bg-secondary') ?>">
                                                <?= htmlspecialchars($event['request_status']) ?>
                                            </span>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <a class="text-warning font-1-6" data-toggle="modal" data-target="#invoiceModal" onclick="fetchInvoiceData(<?= $event['event_id']; ?>)">
                                                <ion-icon name="information-circle" class="admin-btn"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="left"
                                                    title="Details"></ion-icon>
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
                    <div class="text-end text-muted small">Rows per page: 10 &nbsp; | &nbsp; 1-<?= count($eventRequests) ?> of <?= count($eventRequests) ?></div>
                </div>
            </section>

            <!-- NOTIFICATIONS AND CALENDAR SECTION -->
            <div class="row g-4 mb-4 pe-3">
                <div class="col-md-6">
                    <section class="bg-white shadow rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3">Notifications</h5>
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $notification): ?>
                                <div class="mb-3 p-3 rounded-3 bg-light">
                                    <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                                    <small class="text-muted"><?= date('M j, Y', strtotime($notification['created_at'])) ?></small>
                                    <?php if ($notification['link']): ?>
                                        <div class="text-end">
                                            <a href="<?= htmlspecialchars($notification['link']) ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No new notifications</p>
                        <?php endif; ?>
                    </section>
                </div>

                <div class="col-md-6">
                    <section class="bg-white shadow rounded-4 p-4 h-100">
                        <h5 class="fw-bold mb-3">Calendar</h5>
                        <div id="calendar"></div>
                        <div class="text-end mt-3">
                            <!--<a href="add_appointment.php" class="btn btn-success btn-sm">Add New Appointment</a>-->
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal HTML -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="modal-content">
                <!-- Dynamic invoice content will be injected here -->
            </div>
        </div>
    </div>
    <script>
        // Services Chart
        const ctx = document.getElementById('servicesChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($top_services, 'service_name')) ?>,
                datasets: [{
                    label: 'Top Services',
                    data: <?= json_encode(array_column($top_services, 'service_count')) ?>,
                    backgroundColor: ['#C06DBF', '#65629B', '#9D7A99', '#A2D2FC', '#FFD166', '#06D6A0', '#118AB2', '#073B4C', '#EF476F', '#FFC43D'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Service Name'
                        }
                    }
                }
            }
        });

        // Calendar
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
                events: <?= $calendar_events_json ?>,
                eventColor: '#6366f1',
                eventTextColor: '#fff',
                selectable: true,
                dateClick: function(info) {
                    alert('Clicked on: ' + info.dateStr);
                }
            });
            calendar.render();
        });
    </script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function fetchInvoiceData(eventId) {
            console.log("Fetching invoice for event ID:", eventId); // Debugging
            $.ajax({
                url: '../events/fetch_invoice.php',
                type: 'GET',
                data: {
                    event_id: eventId
                },
                success: function(response) {
                    console.log("Response:", response); // Debugging
                    document.getElementById('modal-content').innerHTML = response;
                    $('#invoiceModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error:", status, error); // Debugging
                    alert('Error fetching data!');
                }
            });
        }

        function showPaymentProof(imageUrl) {
            Swal.fire({
                title: 'Payment Proof',
                imageAlt: 'Payment Proof',
                html: `<img src="${imageUrl}" style="max-width:100%; height:auto; border-radius:5px;">`,
                imageHeight: 'auto',
                showConfirmButton: true,
                width: '400px',
                customClass: {
                    popup: 'rounded'
                }
            });
        }
    </script>
</body>

</html>
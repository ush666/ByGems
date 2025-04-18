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
    <title>Event Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/event-management.css">
</head>

<body>
    <?php
    $event_manager = "active";
    include("../components/admin-header.php");
    ?>
    <div class="d-flex position-relative body-container">
        <?php
        include("../components/admin-sidebar.php");
        ?>
        <div class="d-flex flex-column me-3" style="width: 100vw; padding-left: 300px;">
            <div class="mt-5 ms-0 pt-4 ">
                <div class="card mt-3 p-3" style="border-radius: 10px; border: none !important;">
                    <h1 class="h2">Event Manager</h1>
                    <nav aria-label="breadcrumb">
                        <div class="breadcrumb-item active p-2 pt-1 pb-1 rounded-2" aria-current="page">Event Management</div>
                    </nav>
                </div>
            </div>
            <div class="card container ms-0 me-3 p-3 pt-4 rounded-4 shadow-lg my-3">
                <!--<h3 class="mb-4 fw-bold">Events</h3>-->
                <ul class="nav nav-tabs mb-3" id="eventTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="booked-tab" data-bs-toggle="tab" data-bs-target="#booked" type="button" role="tab">Booked Events</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab">Event Request</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="booked-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">Approved Event Request</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Booked Events Tab -->
                    <div class="tab-pane fade show active" id="booked" role="tabpanel">
                        <div class="table-responsive">
                            <table id="bookedEventsTable" class="table table-hover align-middle table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Celebrant</th>
                                        <th>Location</th>
                                        <th>Event Date</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: normal;">
                                    <?php foreach ($events as $event) : ?>
                                        <tr class="<?= strtolower($event['request_status']) ?>">
                                            <td><?= htmlspecialchars($event['order_id']); ?></td>
                                            <td><?= htmlspecialchars($event['username']); ?></td>
                                            <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                                            <td><?= htmlspecialchars($event['event_location']); ?></td>
                                            <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                            <td>₱<?= number_format($event['total_price'], 2); ?></td>
                                            <td><?= number_format($event['discount_percentage'], 0); ?>%</td>
                                            <td>₱<?= number_format($event['final_price'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?= $event['payment_status'] === 'Paid' ? 'success' : ($event['payment_status'] === 'Pending' ? 'warning' : 'secondary') ?>">
                                                    <?= htmlspecialchars($event['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $event['request_status'] === 'Approved' ? 'success' : ($event['request_status'] === 'Pending' ? 'warning' : 'danger') ?>"><?= htmlspecialchars($event['request_status']); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#editEventModal" data-event-id="<?= $event['event_id']; ?>">
                                                        Edit
                                                    </button>
                                                    <?php if ($user_role == 'admin') : ?>
                                                        <a href="adjust_price.php?id=<?= $event['event_id']; ?>" class="btn btn-sm btn-outline-warning">Adjust</a>
                                                    <?php endif; ?>
                                                    <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= $event['event_id']; ?>')">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Event Request Tab -->
                    <div class="tab-pane fade" id="request" role="tabpanel">
                        <div class="table-responsive">
                            <table id="eventRequestTable" class="table table-hover align-middle table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Celebrant</th>
                                        <th>Location</th>
                                        <th>Event Date</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: normal;">
                                    <?php
                                    $has_requests = false;
                                    foreach ($events as $event) :
                                        if ($event['request_status'] !== 'Approved') :
                                            $has_requests = true;
                                    ?>
                                            <tr class="<?= strtolower($event['request_status']) ?>">
                                                <td><?= htmlspecialchars($event['order_id']); ?></td>
                                                <td><?= htmlspecialchars($event['username']); ?></td>
                                                <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                                                <td><?= htmlspecialchars($event['event_location']); ?></td>
                                                <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                                <td>₱<?= number_format($event['total_price'], 2); ?></td>
                                                <td><?= number_format($event['discount_percentage'], 2); ?>%</td>
                                                <td>₱<?= number_format($event['final_price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $event['payment_status'] === 'Paid' ? 'success' : ($event['payment_status'] === 'Partial' ? 'warning text-dark' : 'secondary') ?>">
                                                        <?= htmlspecialchars($event['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?= $event['request_status'] === 'Pending' ? 'warning text-dark' : 'danger' ?>">
                                                        <?= htmlspecialchars($event['request_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#editEventModal" data-event-id="<?= $event['event_id']; ?>">
                                                            Edit
                                                        </button>
                                                        <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= $event['event_id']; ?>')">Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        endif;
                                    endforeach;
                                    if (!$has_requests) {
                                        echo '<tr><td colspan="11" class="text-muted">No event requests yet.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Booked Events Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        <div class="table-responsive">
                            <table id="approvedEventsTable" class="table table-hover align-middle table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Celebrant</th>
                                        <th>Location</th>
                                        <th>Event Date</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Final Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: normal;">
                                    <?php foreach ($events as $event) : ?>
                                        <?php if ($event['request_status'] === 'Approved') : ?>
                                            <tr class="<?= strtolower($event['request_status']) ?>">
                                                <td><?= htmlspecialchars($event['order_id']); ?></td>
                                                <td><?= htmlspecialchars($event['username']); ?></td>
                                                <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                                                <td><?= htmlspecialchars($event['event_location']); ?></td>
                                                <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                                <td>₱<?= number_format($event['total_price'], 2); ?></td>
                                                <td><?= number_format($event['discount_percentage'], 0); ?>%</td>
                                                <td>₱<?= number_format($event['final_price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $event['payment_status'] === 'Paid' ? 'success' : ($event['payment_status'] === 'Partial' ? 'warning text-dark' : 'secondary') ?>">
                                                        <?= htmlspecialchars($event['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?= htmlspecialchars($event['request_status']); ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#editEventModal" data-event-id="<?= $event['event_id']; ?>">
                                                            Edit
                                                        </button>
                                                        <?php if ($user_role == 'admin') : ?>
                                                            <a href="adjust_price.php?id=<?= $event['event_id']; ?>" class="btn btn-sm btn-outline-warning">Adjust</a>
                                                        <?php endif; ?>
                                                        <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('<?= $event['event_id']; ?>')">Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editEventModalBody">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editModal = document.getElementById('editEventModal');
            const modalBody = document.getElementById('editEventModalBody');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const eventId = button.getAttribute('data-event-id');

                // Load the form
                modalBody.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                fetch(`../events/edit_table_event.php?id=${eventId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        cache: 'no-store' // 👈 prevents caching problems
                    })
                    .then(response => response.text())
                    .then(data => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const form = doc.querySelector('form');
                        if (form) {
                            document.getElementById('editEventModalBody').innerHTML = '';
                            document.getElementById('editEventModalBody').appendChild(form);
                        } else {
                            document.getElementById('editEventModalBody').innerHTML = '<p class="text-danger">Failed to load the form.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading form:', error);
                        document.getElementById('editEventModalBody').innerHTML = '<p class="text-danger">Failed to load the form.</p>';
                    });

            });
        });
    </script>

    <script>
        function confirmDelete(eventId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This event will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `delete_event.php?id=${eventId}`;
                }
            });
        }
    </script>



    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookedEventsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                "lengthMenu": [5, 10, 25, 50, 100],
                pageLength: 5,
                order: [
                    [4, 'desc']
                ],
                "ordering": false,
            });

            $('#eventRequestTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                "lengthMenu": [5, 10, 25, 50, 100],
                pageLength: 5,
                order: [
                    [4, 'desc']
                ],
                "ordering": false,
            });

            $('#approvedEventsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                "lengthMenu": [5, 10, 25, 50, 100],
                pageLength: 5,
                order: [
                    [4, 'desc']
                ],
                "ordering": false,
            });
        });
    </script>
</body>

</html>
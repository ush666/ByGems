<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    header("Location: ../index.php");
    exit();
}

$user_role = $_SESSION['role'];

// Fetch all event requests
$query = "SELECT e.event_id, e.order_id, c.username, e.celebrant_name, e.event_location, e.event_date, e.total_amount,
            e.payment_status, e.request_status, 
            IFNULL(SUM(s.price * s.quantity), 0) AS total_price,
            IFNULL(e.discounted_price, 0) AS discounted_price,
            IFNULL(e.discount_percentage, 0) AS total,
            IFNULL(ROUND(SUM(s.price * s.quantity) * (1 - e.discount_percentage / 100), 2), 0) AS final_price
        FROM event_request e
        JOIN account c ON e.user_id = c.user_id
        LEFT JOIN services_to_events s ON e.event_id = s.event_id
        GROUP BY e.event_id, e.order_id, c.username, e.celebrant_name, e.event_location, e.event_date, 
                 e.payment_status, e.request_status, e.discounted_price, e.discount_percentage
        ORDER BY e.event_date ASC";


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
                                        <!--<th>Location</th>-->
                                        <th>Event Date</th>
                                        <!--<th>Price</th>
                                        <th>Discount</th>-->
                                        <th>Total Price</th>
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
                                            <!--<td><?= htmlspecialchars($event['event_location']); ?></td>-->
                                            <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                            <!--<td>₱<?= number_format($event['total_price'], 2); ?></td>
                                            <td><?= number_format($event['discount_percentage'], 0); ?>%</td>-->
                                            <td>₱<?= number_format($event['discounted_price'], 2); ?></td>
                                            <td>
                                                <span class="badge bg-<?= $event['payment_status'] === 'fullypaid' ? 'success' : ($event['payment_status'] === 'partial' ? 'secondary' : 'warning') ?>">
                                                    <?= ucfirst($event['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                            <span class="badge bg-<?= $event['request_status'] === 'approved' ? 'success' : ($event['request_status'] === 'completed' ? 'purple' : ($event['request_status'] === 'pending' ? 'warning' : 'danger')) ?>">
                                                    <?= ucfirst($event['request_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-row justify-content-start gap-2">
                                                    <a class="text-warning font-1" data-toggle="modal" data-target="#invoiceModal" onclick="fetchInvoiceData(<?= $event['event_id']; ?>)">
                                                        <ion-icon name="information-circle" class="admin-btn"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="left"
                                                            title="Details"></ion-icon>
                                                    </a>

                                                    <a class="text-primary font-1-5"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editEventModal"
                                                        data-event-id="<?= $event['event_id']; ?>">
                                                        <ion-icon name="clipboard" class="admin-btn"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="left"
                                                            title="Edit"></ion-icon>
                                                    </a>

                                                    <?php if ($event['request_status'] == 'pending') : ?>
                                                        <a class="text-danger font-1-5" onclick="openDeclineModal('<?= $event['event_id']; ?>')">
                                                            <ion-icon name="ban" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Decline"></ion-icon>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($event['request_status'] == 'pending') : ?>
                                                        <a class="text-success font-1" onclick="openApproveAlert('<?= $event['event_id']; ?>')">
                                                            <ion-icon name="checkmark-circle" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Approve"></ion-icon>
                                                        </a>
                                                    <?php endif; ?>
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
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Celebrant</th>
                                        <!--<th>Location</th>-->
                                        <th>Event Date</th>
                                        <!--<th>Price</th>
                                        <th>Discount</th>-->
                                        <th>Total Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: normal;">
                                    <?php
                                    $has_requests = false;
                                    foreach ($events as $event) :
                                        if ($event['request_status'] == 'pending') :
                                            $has_requests = true;
                                    ?>
                                            <tr class="<?= strtolower($event['request_status']) ?>">
                                                <td><?= htmlspecialchars($event['order_id']); ?></td>
                                                <td><?= htmlspecialchars($event['username']); ?></td>
                                                <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                                                <!--<td><?= htmlspecialchars($event['event_location']); ?></td>-->
                                                <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                                <!--<td>₱<?= number_format($event['total_price'], 2); ?></td>
                                                <td><?= number_format($event['discount_percentage'], 2); ?>%</td>-->
                                                <td>₱<?= number_format($event['discounted_price'], 2); ?></td>
                                                <td>
                                                    <span class="badge text-white bg-<?= $event['payment_status'] === 'fullypaid' ? 'success' : ($event['payment_status'] === 'partial' ? 'secondary' : 'warning') ?>">
                                                        <?= ucfirst($event['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge text-white bg-<?= $event['request_status'] === 'pending' ? 'warning text-dark' : 'danger' ?>">
                                                        <?= ucfirst($event['request_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-row justify-content-start gap-2">
                                                        <a class="text-warning font-1" data-toggle="modal" data-target="#invoiceModal" onclick="fetchInvoiceData(<?= $event['event_id']; ?>)">
                                                            <ion-icon name="information-circle" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Details"></ion-icon>
                                                        </a>

                                                        <a class="text-primary font-1-5"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editEventModal"
                                                            data-event-id="<?= $event['event_id']; ?>">
                                                            <ion-icon name="clipboard" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Edit"></ion-icon>
                                                        </a>

                                                        <?php if ($event['request_status'] == 'pending') : ?>
                                                            <a class="text-danger font-1-5" onclick="openDeclineModal('<?= $event['event_id']; ?>')">
                                                                <ion-icon name="ban" class="admin-btn"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="left"
                                                                    title="Decline"></ion-icon>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($event['request_status'] == 'pending') : ?>
                                                            <a class="text-success font-1" onclick="openApproveAlert('<?= $event['event_id']; ?>')">
                                                                <ion-icon name="checkmark-circle" class="admin-btn"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="left"
                                                                    title="Approve"></ion-icon>
                                                            </a>
                                                        <?php endif; ?>
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
                                        <!--<th>Location</th>-->
                                        <th>Event Date</th>
                                        <!--<th>Price</th>
                                        <th>Discount</th>-->
                                        <th>Total Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="font-weight: normal;">
                                    <?php foreach ($events as $event) : ?>
                                        <?php if ($event['request_status'] === 'approved') : ?>
                                            <tr class="<?= strtolower($event['request_status']) ?>">
                                                <td><?= htmlspecialchars($event['order_id']); ?></td>
                                                <td><?= htmlspecialchars($event['username']); ?></td>
                                                <td><?= htmlspecialchars($event['celebrant_name']); ?></td>
                                                <!--<td><?= htmlspecialchars($event['event_location']); ?></td>-->
                                                <td><?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_date']))); ?></td>
                                                <!--<td>₱<?= number_format($event['total_price'], 2); ?></td>
                                                <td><?= number_format($event['discount_percentage'], 0); ?>%</td>-->
                                                <td>₱<?= number_format($event['discounted_price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $event['payment_status'] === 'fullypaid' ? 'success' : ($event['payment_status'] === 'partial' ? 'secondary' : 'warning') ?>">
                                                        <?= ucfirst($event['payment_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?= ucfirst($event['request_status']); ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-row justify-content-start gap-2">
                                                        <a class="text-warning font-1" data-toggle="modal" data-target="#invoiceModal" onclick="fetchInvoiceData(<?= $event['event_id']; ?>)">
                                                            <ion-icon name="information-circle" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Details"></ion-icon>
                                                        </a>

                                                        <a class="text-primary font-1-5"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editEventModal"
                                                            data-event-id="<?= $event['event_id']; ?>">
                                                            <ion-icon name="clipboard" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Edit"></ion-icon>
                                                        </a>

                                                        <a class="text-purple font-1" onclick="openCompletedAlert('<?= $event['event_id']; ?>')">
                                                            <ion-icon name="checkmark-circle" class="admin-btn"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-placement="left"
                                                                title="Mark as Completed">
                                                            </ion-icon>
                                                        </a>

                                                        <?php if ($event['request_status'] == 'pending') : ?>
                                                            <a class="text-danger font-1-5" onclick="openDeclineModal('<?= $event['event_id']; ?>')">
                                                                <ion-icon name="ban" class="admin-btn"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="left"
                                                                    title="Decline"></ion-icon>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($event['request_status'] == 'pending') : ?>
                                                            <a class="text-success font-1" onclick="openApproveAlert('<?= $event['event_id']; ?>')">
                                                                <ion-icon name="checkmark-circle" class="admin-btn"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="left"
                                                                    title="Approve"></ion-icon>
                                                            </a>
                                                        <?php endif; ?>
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

    <!-- Invoice Modal 
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="modal-content">

                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="invoiceModalLabel">Invoice</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="invoiceContent">
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>-->

    <!-- Modal HTML -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="modal-content">
                <!-- Dynamic invoice content will be injected here -->
            </div>
        </div>
    </div>


    <!-- Decline Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="declineForm" method="POST" action="../events/decline_event.php">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="declineModalLabel">Decline Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="event_id" id="declineEventId">

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Decline</label>
                            <select class="form-select" id="reasonSelect" name="reason">
                                <option value="">-- Select a reason --</option>
                                <option value="Incomplete Details">Incomplete Details</option>
                                <option value="Schedule Conflict">Schedule Conflict</option>
                                <option value="Policy Violation">Policy Violation</option>
                                <option id="otherOption" value="Other">Other (specify below)</option>
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="customReasonDiv">
                            <label for="customReason" class="form-label">Custom Reason</label>
                            <input type="text" class="form-control" id="customReason" name="custom_reason" placeholder="Enter custom reason...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="sendDeclineBtn" onclick="confirmDecline(event)" disabled>Send Decline Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-purple text-white">
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
        //decline other reason replication
        document.getElementById('customReason').addEventListener('input', function() {
            var otherOption = document.getElementById('otherOption');
            var customReasonInput = this.value.trim();

            if (customReasonInput.length > 0) {
                otherOption.value = customReasonInput;
                otherOption.textContent = customReasonInput;
            } else {
                otherOption.value = "Other";
                otherOption.textContent = "Other (specify below)";
            }
        });

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

    <script>
        function openApproveAlert(eventId) {
            Swal.fire({
                title: 'Approve this event?',
                text: "Are you sure you want to approve this event booking?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // green
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, send approval to backend
                    window.location.href = '../events/approve_event.php?id=' + eventId;
                }
            });
        }

        function openCompletedAlert(eventId) {
            Swal.fire({
                title: 'Complete this event?',
                text: "Are you sure you want to mark this event as Completed?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // green
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Complete'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send using POST
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '../events/complete_event.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'event_id';
                    input.value = eventId;
                    form.appendChild(input);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openDeclineModal(eventId) {
            // Set the event ID in the hidden input
            document.getElementById('declineEventId').value = eventId;
            // Reset form
            document.getElementById('declineForm').reset();
            document.getElementById('sendDeclineBtn').disabled = true;
            document.getElementById('customReasonDiv').classList.add('d-none');
            // Show the modal
            var declineModal = new bootstrap.Modal(document.getElementById('declineModal'));
            declineModal.show();
        }

        // Handle enabling/disabling send button
        const reasonSelect = document.getElementById('reasonSelect');
        const customReasonDiv = document.getElementById('customReasonDiv');
        const customReasonInput = document.getElementById('customReason');
        const sendBtn = document.getElementById('sendDeclineBtn');

        reasonSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                customReasonDiv.classList.remove('d-none');
                sendBtn.disabled = true; // Wait for custom input
            } else if (this.value !== '') {
                customReasonDiv.classList.add('d-none');
                sendBtn.disabled = false;
            } else {
                customReasonDiv.classList.add('d-none');
                sendBtn.disabled = true;
            }
        });

        customReasonInput.addEventListener('input', function() {
            if (customReasonInput.value.trim() !== '') {
                sendBtn.disabled = false;
            } else {
                sendBtn.disabled = true;
            }
        });

        //SWEET ALERT confirm when clicking Send button
        function confirmDecline(event) {
            event.preventDefault(); // Prevent form from submitting immediately

            let selectedReason = reasonSelect.value;
            const customReason = customReasonInput.value.trim();

            if (selectedReason === 'Other' && customReason === '') {
                Swal.fire('Error', 'Please enter a custom reason.', 'error');
                return;
            }

            if (selectedReason === '') {
                Swal.fire('Error', 'Please select a reason.', 'error');
                return;
            }

            let finalReason = (selectedReason === 'Other') ? customReason : selectedReason;

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to decline this event. Reason: "${finalReason}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Decline it!',
                width: 400,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show spinner on the button
                    const declineBtn = document.getElementById('sendDeclineBtn');
                    declineBtn.disabled = true;
                    declineBtn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...`;

                    // Now submit the form
                    document.getElementById('declineForm').submit();
                }
            });
        }
    </script>

    <?php if (isset($_GET['declined']) && $_GET['declined'] == 'success'): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Declined!',
                text: 'The event was successfully declined and email sent.',
                timer: 5000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>


    <script>
        <?php if (isset($_GET['message'])): ?>
            <?php if ($_GET['message'] == 'approved'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Approved!',
                    text: 'The event has been approved successfully.',
                    width: 400,
                    timer: 5000,
                    showConfirmButton: false
                });
            <?php elseif ($_GET['message'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was a problem approving the event.',
                    width: 400,
                    timer: 5000,
                    showConfirmButton: false
                });
            <?php elseif ($_GET['message'] == 'invalid'): ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Request!',
                    text: 'No event ID provided.',
                    width: 400,
                    timer: 5000,
                    showConfirmButton: false
                });
            <?php elseif ($_GET['message'] == 'completed'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Event marked as Completed!',
                    text: 'The event has been successfully updated.',
                    confirmButtonColor: '#198754', // Bootstrap green
                    width: 400,
                    timer: 5000,
                    showConfirmButton: false
                });

            <?php endif; ?>
        <?php endif; ?>
    </script>

    <script>
        function fetchInvoiceData(eventId) {
            // Using AJAX to fetch data based on event_id
            $.ajax({
                url: '../events/fetch_invoice.php', // PHP file that will fetch the data
                type: 'GET',
                data: {
                    event_id: eventId
                },
                success: function(response) {
                    document.getElementById('modal-content').innerHTML = response; // Response is the injected HTML.
                    $('#invoiceModal').modal('show'); // Show the modal                
                },
                error: function() {
                    alert('Error fetching data!');
                }
            });
        }
    </script>
    <script>
        function showPaymentProof(imageUrl) {
            Swal.fire({
                title: 'Payment Proof',
                imageAlt: 'Payment Proof',
                html: `<img src="${imageUrl}" style="max-width:300px; height:auto; border-radius:5px;">`,
                imageHeight: 'auto',
                showConfirmButton: true,
                width: '400px',
                customClass: {
                    popup: 'rounded'
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>

</html>
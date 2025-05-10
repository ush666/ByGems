<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- DataTables CSS -->

    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <style>
        .body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px 60px;
            background-color: #FFF9E5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .payment-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            min-width: 100%;
            text-align: center;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-fullypaid {
            background-color: #198754;
            color: #fff;
        }

        .status-partial {
            background-color: #ffc107;
            color: #fff;
        }

        .action-btn {
            padding: 7px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            margin-right: 5px;
            border-radius: 25px !important;
            box-shadow: 5px 5px 11px #bebebe,
                -5px -5px 11px #ffffff;
        }

        .view-btn {
            background-color: #3498db;
            color: white;
        }

        .view-btn:hover {
            background-color: #2980b9;
        }

        .dataTables_wrapper {
            margin-top: 20px;
        }

        .mt-6 {
            margin-top: 6rem;
        }
    </style>
</head>

<body>
    <div class="body">
        <?php
        $home = "font-bold";
        include("../components/header.php");

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login.php");
            exit;
        }

        // Get all orders for the current user with important details
        $ordersStmt = $pdo->prepare("
            SELECT 
                o.order_id,
                o.order_date,
                o.payment_status,
                o.total_amount,
                er.event_type,
                er.event_date,
                er.client_name,
                er.celebrant_name,
                er.deposit_amount,
                er.remaining_balance,
                er.request_status
            FROM orders o
            JOIN event_request er ON o.order_id = er.order_id
            WHERE o.user_id = :user_id
            ORDER BY o.order_date DESC
        ");
        $ordersStmt->execute([':user_id' => $_SESSION['user_id']]);
        $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="container mt-6 shadow" style="background-color: white;">
            <h1>My Orders</h1>

            <table id="ordersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Client</th>
                        <!--<th>Celebrant</th>-->
                        <th>Event Type</th>
                        <th>Event Date</th>
                        <th>Total Amount</th>
                        <!--<th>Deposit</th>-->
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= date('M j, Y', strtotime($order['order_date'])) ?></td>
                            <td><?= htmlspecialchars($order['client_name']) ?></td>
                            <!--<td><?= htmlspecialchars($order['celebrant_name']) ?></td>-->
                            <td><?= htmlspecialchars($order['event_type']) ?></td>
                            <td><?= date('M j, Y', strtotime($order['event_date'])) ?></td>
                            <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                            <!--<td>₱<?= number_format($order['deposit_amount'], 2) ?></td>-->
                            <td>₱<?= number_format($order['remaining_balance'], 2) ?></td>
                            <td>
                                <span class="payment-status status-<?= strtolower($order['payment_status']) ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="../User-Pages/invoice.php?order_id=<?= $order['order_id'] ?>" class="action-btn btn-purple text-white bold">View</a>
                                <?php if (isset($order['request_status']) && $order['request_status'] === 'pending'): ?>
                                    <form id="cancel-form-<?= $order['order_id'] ?>" action="../events/cancel_request.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                                        <button type="button" class="action-btn bg-danger text-white fw-bold" style="border: none;" onclick="confirmCancel(<?= $order['order_id'] ?>)">
                                            Cancel
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Footer -->
    <?php
    include("../components/footer.php");
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'print',
                    text: 'Print Orders',
                    customize: function(win) {
                        $(win.document.body).find('h1').text('My Orders');
                        $(win.document.body).find('table').addClass('print-table');
                    }
                }],
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    }, // Order ID
                    {
                        responsivePriority: 2,
                        targets: 9
                    }, // Actions
                    {
                        responsivePriority: 3,
                        targets: 6
                    }, // Total Amount
                    {
                        responsivePriority: 4,
                        targets: 4
                    }, // Event Type
                    {
                        responsivePriority: 5,
                        targets: 5
                    } // Event Date
                ],
                pageLength: 10,
                order: [
                    [1, 'desc']
                ] // Sort by order date descending
            });
        });
    </script>
    <script>
        function confirmCancel(orderId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will cancel your request!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form-' + orderId).submit();
                }
            });
        }
    </script>
</body>

</html>
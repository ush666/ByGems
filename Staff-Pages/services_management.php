<?php
session_start();
require_once '../includes/db.php';

// Restrict access to staff and admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: ../User-Pages/customer_dashboard.php");
    exit();
}

$user_role = $_SESSION['role'];

// Fetch all services
$services = $pdo->query("SELECT * FROM services ORDER BY category, service_name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
    <link rel="stylesheet" href="cms_style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* Change delete button color to red */
        .btn-delete {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
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
    <h1>Manage Services</h1>

    <button onclick="window.location.href='add_service.php'">Add New Service</button>

    <table id="serviceTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service) : ?>
            <tr id="row-<?= $service['service_id']; ?>">
                <td><?= htmlspecialchars($service['service_id']); ?></td>
                <td><?= htmlspecialchars($service['service_name']); ?></td>
                <td><?= htmlspecialchars($service['category']); ?></td>
                <td>₱<?= number_format($service['price'], 2); ?></td>
                <td><?= htmlspecialchars($service['status'] ?? 'enabled'); ?></td>
                <td>
                    <a href="edit_service.php?id=<?= $service['service_id']; ?>" class="btn btn-edit">Edit</a>

                    <?php if ($user_role === 'admin') : ?>
                        <form action="toggle_service.php" method="POST" style="display:inline;">
                            <input type="hidden" name="service_id" value="<?= $service['service_id']; ?>">
                            <button type="submit" name="toggle_status">
                                <?= ($service['status'] === 'enabled') ? 'Disable' : 'Enable'; ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <button class="btn-delete" onclick="deleteService(<?= $service['service_id']; ?>)">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        $('#serviceTable').DataTable();
    });

    function deleteService(serviceId) {
        if (!confirm("Are you sure you want to delete this service?")) return;

        $.ajax({
            url: 'delete_service.php',
            type: 'POST',
            data: { service_id: serviceId },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    alert(response.message);
                    $("#row-" + serviceId).fadeOut("slow", function() {
                        $(this).remove();
                    });
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function() {
                alert("Failed to delete service. Please try again.");
            }
        });
    }
</script>

</body>
</html>

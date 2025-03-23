<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    $stmt = $pdo->prepare("SELECT status FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($service) {
        $new_status = ($service['status'] === 'enabled') ? 'disabled' : 'enabled';

        $updateStmt = $pdo->prepare("UPDATE services SET status = ? WHERE service_id = ?");
        $updateStmt->execute([$new_status, $service_id]);
    }
}

header("Location: services_management.php");
exit();
?>

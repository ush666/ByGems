<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $service_id = intval($_POST['service_id']);

    // Fetch service image path
    $stmt = $pdo->prepare("SELECT image FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo json_encode(["status" => "error", "message" => "Service not found."]);
        exit();
    }

    // Delete the service image if exists
    if (!empty($service['image']) && file_exists("../uploads/" . $service['image'])) {
        unlink("../uploads/" . $service['image']);
    }

    // Delete service from the database
    $stmt = $pdo->prepare("DELETE FROM services WHERE service_id = ?");
    if ($stmt->execute([$service_id])) {
        echo json_encode(["status" => "success", "message" => "Service deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete service."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>

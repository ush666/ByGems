<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['discount_name'];
    $code = $_POST['discount_code'] ?? '';
    $type = $_POST['discount_type'];
    $value = $_POST['discount_value'];
    $application = $_POST['discount_application'];
    $active = $_POST['is_active'];
    $description = $_POST['discount_description'] ?? '';
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    
    // Handle specific service IDs
    $specificServiceIds = '';
    if ($application === 'specific' && !empty($_POST['specific_service_ids'])) {
        $specificServiceIds = implode(',', $_POST['specific_service_ids']);
    }

    try {
        $stmt = $pdo->prepare("UPDATE discounts SET 
            discount_name = ?,
            discount_code = ?,
            discount_type = ?,
            discount_value = ?,
            discount_application = ?,
            specific_service_ids = ?,
            is_active = ?,
            discount_description = ?,
            start_date = ?,
            end_date = ?
            WHERE id = ?");
            
        $stmt->execute([
            $name,
            $code,
            $type,
            $value,
            $application,
            $specificServiceIds,
            $active,
            $description,
            $startDate,
            $endDate,
            $id
        ]);

        header("Location: ../Staff-Pages/discounts.php?editMessage=success");
    } catch (PDOException $e) {
        error_log("Error updating discount: " . $e->getMessage());
        header("Location: ../Staff-Pages/discounts.php?editMessage=error");
    }
    exit;
}

header("Location: ../Staff-Pages/discounts.php");
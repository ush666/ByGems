<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['discount_name'];
    $code = $_POST['discount_code'] ?? '';
    $type = $_POST['discount_type'];
    $value = $_POST['discount_value'];
    $application = $_POST['discount_application'];
    $description = $_POST['discount_description'] ?? '';
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    
    // Handle specific service IDs
    $specificServiceIds = '';
    if ($application === 'specific' && !empty($_POST['specific_service_ids'])) {
        $specificServiceIds = implode(',', $_POST['specific_service_ids']);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO discounts (
            discount_name,
            discount_code,
            discount_type,
            discount_value,
            discount_application,
            specific_service_ids,
            is_active,
            discount_description,
            start_date,
            end_date,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 'active')");
        
        $stmt->execute([
            $name,
            $code,
            $type,
            $value,
            $application,
            $specificServiceIds,
            $description,
            $startDate,
            $endDate
        ]);

        header("Location: ../Staff-Pages/discounts.php?message=success");
    } catch (PDOException $e) {
        error_log("Error adding discount: " . $e->getMessage());
        header("Location: ../Staff-Pages/discounts.php?message=error");
    }
    exit;
}

header("Location: ../Staff-Pages/discounts.php");
<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

try {
    // Get all active discounts that don't require a code and are within their date range
    $stmt = $pdo->prepare("
        SELECT * FROM discounts 
        WHERE is_active = 1
        AND (discount_code IS NULL OR discount_code = '')
        AND (start_date IS NULL OR start_date <= NOW()) 
        AND (end_date IS NULL OR end_date >= NOW())
    ");
    $stmt->execute();
    $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'discounts' => $discounts
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
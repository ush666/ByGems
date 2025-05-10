<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $stmt = $pdo->prepare("
        SELECT * FROM discounts 
        WHERE is_active = 1 
        AND (discount_application = 'all') 
        AND (start_date <= NOW() AND end_date >= NOW())
    ");
    $stmt->execute();
    
    $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'discounts' => $discounts
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error'
    ]);
}
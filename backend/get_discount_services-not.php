<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if (isset($_GET['discount_id'])) {
    $discountId = $_GET['discount_id'];
    
    try {
        // Get the comma-separated service IDs from the discounts table
        $stmt = $pdo->prepare("SELECT specific_service_ids FROM discounts WHERE id = ?");
        $stmt->execute([$discountId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['specific_service_ids'])) {
            // Split the comma-separated string into an array
            $serviceIds = explode(',', $result['specific_service_ids']);
            // Trim whitespace from each ID and filter out any empty values
            $serviceIds = array_map('trim', $serviceIds);
            $serviceIds = array_filter($serviceIds);
            
            // Convert to integers if needed
            $serviceIds = array_map('intval', $serviceIds);
        } else {
            $serviceIds = [];
        }
        
        echo json_encode($serviceIds);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

echo json_encode([]);
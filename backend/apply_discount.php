<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['discount_code'], $input['cart_items'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    // Check if discount code is valid and active
    $stmt = $pdo->prepare("
        SELECT * FROM discounts 
        WHERE discount_code = :code 
        AND is_active = TRUE
        AND (start_date IS NULL OR start_date <= NOW()) 
        AND (end_date IS NULL OR end_date >= NOW())
    ");
    $stmt->execute([':code' => $input['discount_code']]);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$discount) {
        throw new Exception('Invalid or expired discount code');
    }

    $discountedServices = [];
    $specificServices = $discount['discount_application'] === 'specific' ? 
        explode(',', $discount['specific_service_ids']) : 
        [];

    foreach ($input['cart_items'] as $item) {
        // Check if discount applies to this service
        $appliesToService = $discount['discount_application'] === 'all' || 
                           in_array($item['service_id'], $specificServices);

        if ($appliesToService) {
            $originalPrice = floatval($item['service_price']) * intval($item['quantity']);
            $discountValue = $discount['discount_type'] === 'percentage' ?
                $originalPrice * ($discount['discount_value'] / 100) :
                min($discount['discount_value'], $originalPrice);

            $discountedPrice = $originalPrice - $discountValue;

            $discountedServices[$item['cart_item_id']] = [
                'original_price' => $originalPrice,
                'discounted_price' => $discountedPrice,
                'discount_amount' => $discountValue
            ];
        }
    }

    if (empty($discountedServices)) {
        throw new Exception('Discount does not apply to any selected services');
    }

    echo json_encode([
        'success' => true,
        'discount' => $discount,
        'discounted_services' => $discountedServices
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
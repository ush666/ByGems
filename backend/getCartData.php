<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

try {
    // Get user's active cart
    $cartStmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND status = 'active'");
    $cartStmt->execute([$_SESSION['user_id']]);
    $cart = $cartStmt->fetch();
    
    if (!$cart) {
        echo json_encode([]); // Return empty array if no active cart
        exit();
    }

    // Get active discounts
    $discountQuery = "SELECT * FROM discounts 
                      WHERE is_active = 1 
                      AND start_date <= NOW() 
                      AND end_date >= NOW()";
    $discountStmt = $pdo->prepare($discountQuery);
    $discountStmt->execute();
    $activeDiscounts = $discountStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get cart items with service details
    $stmt = $pdo->prepare("
        SELECT 
            ci.cart_item_id,
            ci.status,
            ci.quantity,
            ci.price as cart_price,
            s.service_id,
            s.service_name,
            s.description,
            s.category,
            s.entertainer_duration_options,
            s.image,
            s.price as service_price
        FROM cart_items ci
        JOIN services s ON ci.service_id = s.service_id
        WHERE ci.cart_id = ?
    ");
    
    $stmt->execute([$cart['id']]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Apply discount information per item
    foreach ($cartItems as &$item) {
        $item['discount_applied'] = 0;
        $item['final_price'] = $item['cart_price']; // Default
        $item['discount_names'] = [];

        foreach ($activeDiscounts as $discount) {
            $applies = false;

            if ($discount['discount_application'] === 'all') {
                $applies = true;
            } elseif ($discount['discount_application'] === 'specific') {
                $ids = array_map('trim', explode(',', $discount['specific_service_ids']));
                if (in_array($item['service_id'], $ids)) {
                    $applies = true;
                }
            }

            if ($applies) {
                $discountAmount = 0;
                if ($discount['discount_type'] === 'percentage') {
                    $discountAmount = $item['service_price'] * ($discount['discount_value'] / 100);
                } else {
                    $discountAmount = $discount['discount_value'];
                }

                $item['discount_applied'] += $discountAmount;
                $item['discount_names'][] = $discount['discount_name'];
            }
        }

        $item['final_price'] = max(0, $item['service_price'] - $item['discount_applied']);
    }
    unset($item);

    header('Content-Type: application/json');
    echo json_encode($cartItems);

} catch (PDOException $e) {
    error_log("Full cart error: " . $e->getMessage());
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error',
        'debug' => $e->getMessage() // Only for development
    ]);
}

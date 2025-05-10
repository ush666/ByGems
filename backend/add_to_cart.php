<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to add items to cart']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$serviceId = $data['service_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

// Validate input
if (!$serviceId || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid item data']);
    exit();
}

try {
    // Get or create user's active cart
    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $cart = $stmt->fetch();
    
    if (!$cart) {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, status) VALUES (?, 'active')");
        $stmt->execute([$_SESSION['user_id']]);
        $cartId = $pdo->lastInsertId();
    } else {
        $cartId = $cart['id'];
    }

    // Get service details
    $stmt = $pdo->prepare("SELECT service_id, category, price FROM services WHERE service_id = ?");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();

    if (!$service) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Service not found']);
        exit();
    }

    $category = $service['category'];
    $basePrice = $service['price'];
    $categoriesToUpdateQuantity = ['Cakes', 'Tier Cakes', 'Dessert Packages', 'Cupcakes', 'Brownies'];

    // Fetch active discounts
    $discountQuery = "SELECT * FROM discounts 
                      WHERE is_active = 1 
                      AND start_date <= NOW() 
                      AND end_date >= NOW()";
    $discountStmt = $pdo->prepare($discountQuery);
    $discountStmt->execute();
    $activeDiscounts = $discountStmt->fetchAll(PDO::FETCH_ASSOC);

    // Apply discounts
    $totalDiscount = 0;
    foreach ($activeDiscounts as $discount) {
        $applies = false;

        if ($discount['discount_application'] === 'all') {
            $applies = true;
        } elseif ($discount['discount_application'] === 'specific') {
            $ids = array_map('trim', explode(',', $discount['specific_service_ids']));
            if (in_array($serviceId, $ids)) {
                $applies = true;
            }
        }

        if ($applies) {
            if ($discount['discount_type'] === 'percentage') {
                $totalDiscount += $basePrice * ($discount['discount_value'] / 100);
            } else {
                $totalDiscount += $discount['discount_value'];
            }
        }
    }

    $finalPrice = max(0, $basePrice - $totalDiscount);

    // Check if item already exists in cart
    $stmt = $pdo->prepare("SELECT cart_item_id, quantity, status FROM cart_items WHERE cart_id = ? AND service_id = ?");
    $stmt->execute([$cartId, $serviceId]);
    $existingItem = $stmt->fetch();
    $status = 'active';

    if ($existingItem) {
        if ($existingItem['status'] === 'inactive' || $existingItem['status'] === '') {
            $newQuantity = in_array($category, $categoriesToUpdateQuantity)
                ? $existingItem['quantity'] + $quantity
                : $existingItem['quantity'];

            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ?, price = ?, status = ? WHERE cart_item_id = ?");
            $stmt->execute([$newQuantity, $finalPrice, $status, $existingItem['cart_item_id']]);
        } else {
            if (in_array($category, $categoriesToUpdateQuantity)) {
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ?, price = ? WHERE cart_item_id = ?");
                $stmt->execute([$newQuantity, $finalPrice, $existingItem['cart_item_id']]);
            } else {
                echo json_encode(['success' => true, 'message' => 'Item already exists in cart']);
                exit();
            }
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, service_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cartId, $serviceId, $quantity, $finalPrice]);
    }

    echo json_encode(['success' => true, 'message' => 'Item added to cart']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

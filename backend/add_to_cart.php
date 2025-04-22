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
$price = $data['price'] ?? null;
$quantity = $data['quantity'] ?? 1;

// Validate input
if (!$serviceId || !$price) {
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

    // Get the service's category
    $stmt = $pdo->prepare("SELECT category FROM services WHERE service_id = ?");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();

    if (!$service) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Service not found']);
        exit();
    }

    $category = $service['category'];
    $categoriesToUpdateQuantity = ['Cakes', 'Tier Cakes', 'Dessert Packages', 'Cupcakes', 'Brownies'];

    // Check if item already exists in cart (active or inactive)
    $stmt = $pdo->prepare("SELECT cart_item_id, quantity, status FROM cart_items WHERE cart_id = ? AND service_id = ?");
    $stmt->execute([$cartId, $serviceId]);
    $existingItem = $stmt->fetch();
    $status = 'active';

    if ($existingItem) {
        if ($existingItem['status'] === 'inactive' || $existingItem['status'] === '') {
            // If item exists but is inactive, reactivate it
            $newQuantity = in_array($category, $categoriesToUpdateQuantity)
                ? $existingItem['quantity'] + $quantity
                : $existingItem['quantity']; // If not in specific categories, keep quantity same

            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ?, price = ?, status = ? WHERE cart_item_id = ?");
            $stmt->execute([$newQuantity, $price, $status, $existingItem['cart_item_id']]);
        } else {
            if (in_array($category, $categoriesToUpdateQuantity)) {
                // If category matches, update quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ?, price = ? WHERE cart_item_id = ?");
                $stmt->execute([$newQuantity, $price, $existingItem['cart_item_id']]);
            } else {
                // If service already exists but category is not in the list, do nothing
                echo json_encode(['success' => true, 'message' => 'Item already exists in cart']);
                exit();
            }
        }
    } else {
        // If service does not exist yet, insert it
        $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, service_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$cartId, $serviceId, $quantity, $price]);
    }

    echo json_encode(['success' => true, 'message' => 'Item added to cart']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Validate required fields
if (!isset($_POST['selected_services'], $_POST['event_details'], $_POST['payment_method'], $_POST['payment_type'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Decode input data
    $selectedServices = json_decode($_POST['selected_services'], true);
    $eventDetails = json_decode($_POST['event_details'], true);

    if (!is_array($selectedServices) || !is_array($eventDetails)) {
        throw new Exception('Invalid service or event data');
    }

    // Calculate total amount
    $totalAmount = 0;
    foreach ($selectedServices as $service) {
        if (!isset($service['cart_price']) || !is_numeric($service['cart_price'])) {
            throw new Exception('Invalid service price data');
        }
        $totalAmount += floatval($service['cart_price']);
    }

    $depositAmount = $totalAmount * 0.5;
    $remainingBalance = $totalAmount - $depositAmount;

    $paymentMethod = $_POST['payment_method'] ?? 'gcash';
    $paymentType = $_POST['payment_type'] ?? 'partial';

    // Handle payment proof upload
    $paymentProof = null;
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/payments/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileExt = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $fileName = 'payment_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $fileExt;
        $paymentProof = $uploadDir . $fileName; 

        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $paymentProof)) {
            throw new Exception('Failed to upload payment proof');
        }
    }

    // Insert into orders table (no manual order_id)
    $orderStmt = $pdo->prepare("
        INSERT INTO orders (user_id, total_amount, payment_status)
        VALUES (:user_id, :total_amount, :payment_status)
    ");
    $orderStmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':total_amount' => $totalAmount,
        ':payment_status' => $paymentType
    ]);

    //  Get auto-incremented order_id
    $orderId = $pdo->lastInsertId();

    if (!$orderId) {
        throw new Exception('Failed to generate order ID');
    }

    //  Prepare celebrant name and address
    $celebrantName = $eventDetails['firstName'] . ' ' .
                     (!empty($eventDetails['middleName']) ? $eventDetails['middleName'] . ' ' : '') .
                     $eventDetails['lastName'];

    $clientAddress = $eventDetails['address']['street'] . ', ' .
                     $eventDetails['address']['barangay'] . ', ' .
                     $eventDetails['address']['city'] . ', ' .
                     $eventDetails['address']['province'] . ' ' .
                     $eventDetails['address']['zip'];

    // Insert into event_request table
    $eventRequestStmt = $pdo->prepare("
        INSERT INTO event_request (
            user_id,
            order_id,
            celebrant_name,
            event_location,
            event_date,
            payment_status,
            request_status,
            discounted_price,
            discount_percentage,
            total_amount,
            deposit_amount,
            remaining_balance,
            payment_method,
            payment_proof,
            client_name,
            client_phone,
            client_address,
            event_theme,
            event_type,
            celebrant_age,
            celebrant_gender
        ) VALUES (
            :user_id,
            :order_id,
            :celebrant_name,
            :event_location,
            :event_date,
            :payment_status,
            :request_status,
            :discounted_price,
            :discount_percentage,
            :total_amount,
            :deposit_amount,
            :remaining_balance,
            :payment_method,
            :payment_proof,
            :client_name,
            :client_phone,
            :client_address,
            :event_theme,
            :event_type,
            :celebrant_age,
            :celebrant_gender
        )
    ");
    $eventRequestStmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':order_id' => $orderId,
        ':celebrant_name' => $celebrantName,
        ':event_location' => $eventDetails['eventLocation'],
        ':event_date' => $eventDetails['eventDate'],
        ':payment_status' => $paymentType,
        ':request_status' => 'pending',
        ':discounted_price' => $totalAmount,
        ':discount_percentage' => 0,
        ':total_amount' => $totalAmount,
        ':deposit_amount' => $depositAmount,
        ':remaining_balance' => $remainingBalance,
        ':payment_method' => $paymentMethod,
        ':payment_proof' => $paymentProof,
        ':client_name' => $eventDetails['clientName'],
        ':client_phone' => $eventDetails['clientPhone'],
        ':client_address' => $clientAddress,
        ':event_theme' => $eventDetails['theme'],
        ':event_type' => $eventDetails['eventType'],
        ':celebrant_age' => $eventDetails['age'],
        ':celebrant_gender' => $eventDetails['gender']
    ]);

    //  Insert into order_items table
    $orderItemStmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id,
            service_id,
            service_name,
            quantity,
            price,
            total_price
        ) VALUES (
            :order_id,
            :service_id,
            :service_name,
            :quantity,
            :price,
            :total_price
        )
    ");

    foreach ($selectedServices as $service) {
        if (!isset($service['service_id'], $service['service_name'], $service['quantity'], $service['service_price'], $service['cart_price'])) {
            throw new Exception('Invalid service data structure');
        }

        $orderItemStmt->execute([
            ':order_id' => $orderId,
            ':service_id' => $service['service_id'],
            ':service_name' => $service['service_name'],
            ':quantity' => $service['quantity'],
            ':price' => $service['service_price'],
            ':total_price' => $service['cart_price']
        ]);
    }

    //  Update cart items
    $updateCartStmt = $pdo->prepare("
        UPDATE cart_items ci
        JOIN cart c ON ci.cart_id = c.id
        SET ci.status = 'inactive', ci.order_id = :order_id
        WHERE c.user_id = :user_id AND ci.status = 'active'
    ");
    $updateCartStmt->execute([
        ':order_id' => $orderId,
        ':user_id' => $_SESSION['user_id']
    ]);

    $pdo->commit();

    header('Location: ../User-Pages/invoice.php?order_id=' . $orderId . '&success=1');
    exit();
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Order processing error: " . $e->getMessage());

    header('Location: ../User-Pages/cart.php?error=' . urlencode($e->getMessage()));
    exit;
    
}
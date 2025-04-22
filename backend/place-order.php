<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Handle file upload for payment proof
$paymentProof = null;
if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../uploads/payments/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($_FILES["payment_proof"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    // Check if image file is an actual image
    $check = getimagesize($_FILES["payment_proof"]["tmp_name"]);
    if ($check === false) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'File is not an image']);
        exit();
    }

    // Try to move the uploaded file
    if (!move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $targetFilePath)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to upload payment proof']);
        exit();
    }
    
    $paymentProof = $fileName;
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Payment proof is required']);
    exit();
}

// Get the posted data
if (!isset($_POST['selectedServices']) || !isset($_POST['eventDetails']) || 
    !isset($_POST['paymentMethod']) || !isset($_POST['paymentType']) || 
    !isset($_POST['totalAmount'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit();
}

try {
    $selectedServices = json_decode($_POST['selectedServices'], true);
    $eventDetails = json_decode($_POST['eventDetails'], true);
    $paymentMethod = $_POST['paymentMethod'];
    $paymentType = $_POST['paymentType'];
    $totalAmount = floatval($_POST['totalAmount']);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }
    
    if (!is_array($selectedServices) || empty($selectedServices)) {
        throw new Exception('No services selected');
    }

    $pdo->beginTransaction();
    
    // Determine payment status based on payment type
    $paymentStatus = $paymentType === 'full' ? 'paid' : 'partial';
    
    // 1. Create the order
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            user_id, 
            order_date, 
            status, 
            total_amount, 
            payment_proof, 
            payment_method,
            payment_status
        ) VALUES (?, NOW(), 'pending', ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $totalAmount,
        $paymentProof,
        $paymentMethod,
        $paymentStatus
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // 2. Add order items
    $stmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id, 
            service_id, 
            quantity, 
            price
        ) VALUES (?, ?, 1, ?)
    ");
    
    foreach ($selectedServices as $service) {
        $price = floatval(str_replace(['₱', ','], '', $service['price']));
        
        $serviceStmt = $pdo->prepare("SELECT service_id FROM services WHERE service_name = ? LIMIT 1");
        $serviceStmt->execute([$service['name']]);
        $serviceData = $serviceStmt->fetch();
        
        if (!$serviceData) {
            throw new Exception("Service not found: " . $service['name']);
        }
        
        $stmt->execute([$orderId, $serviceData['service_id'], $price]);
    }
    
    // 3. Add order details
    $requiredDetails = [
        'firstName', 'lastName', 'age', 'theme', 'themeColor', 'gender',
        'eventType', 'eventLocation', 'eventDate', 'clientName', 'clientPhone',
        'address' => ['street', 'barangay', 'city', 'province', 'zip']
    ];
    
    // Validate all required fields exist
    foreach ($requiredDetails as $key => $field) {
        if (is_array($field)) {
            foreach ($field as $subfield) {
                if (!isset($eventDetails[$key][$subfield])) {
                    throw new Exception("Missing required field: $key.$subfield");
                }
            }
        } else {
            if (!isset($eventDetails[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO order_details (
            order_id, 
            celebrant_first_name, 
            celebrant_last_name, 
            celebrant_middle_name,
            celebrant_age, 
            event_theme, 
            event_theme_color, 
            celebrant_gender,
            event_type, 
            event_location, 
            event_datetime, 
            client_name,
            client_phone, 
            street, 
            barangay, 
            city, 
            province, 
            zip
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $orderId,
        $eventDetails['firstName'],
        $eventDetails['lastName'],
        $eventDetails['middleName'] ?? null,
        $eventDetails['age'],
        $eventDetails['theme'],
        $eventDetails['themeColor'],
        $eventDetails['gender'],
        $eventDetails['eventType'],
        $eventDetails['eventLocation'],
        $eventDetails['eventDate'],
        $eventDetails['clientName'],
        $eventDetails['clientPhone'],
        $eventDetails['address']['street'],
        $eventDetails['address']['barangay'],
        $eventDetails['address']['city'],
        $eventDetails['address']['province'],
        $eventDetails['address']['zip']
    ]);
    
    // 4. Clear the cart
    $stmt = $pdo->prepare("
        DELETE ci FROM cart_items ci
        JOIN carts c ON ci.cart_id = c.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'orderId' => $orderId,
        'message' => 'Order placed successfully'
    ]);
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
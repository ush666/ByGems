<?php
session_start();
require_once '../includes/db.php';

// Restrict access to staff and admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// Handle form submission for adding new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_name'])) {
    $service_name = trim($_POST['service_name']);
    $category = trim($_POST['category_id']);
    $description = trim($_POST['description']);
    $status = "enabled";
    
    // Fix: Check for admin role to set price
    $price = ($_SESSION['role'] !== 'customer' && isset($_POST['price'])) 
        ? trim($_POST['price']) 
        : null;
    
    $image = null;

    if (empty($service_name) || empty($category) || empty($description)) {
        $error = "All fields except price and image are required!";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../uploads/";
            $imageFileName = time() . "_" . basename($_FILES["image"]["name"]);
            $targetFilePath = $targetDir . $imageFileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileType, $allowedTypes)) {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = "File size must be less than 5MB.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $image = $imageFileName;
                } else {
                    $error = "Error uploading image.";
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO services (service_name, category, price, description, image, status) VALUES (?, ?, ?, ?, ?, ?)");
                
                // Fix: Use the properly set price variable
                $stmt->execute([$service_name, $category, $price, $description, $image, $status]);
                
                // Return JSON response for AJAX
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Service added successfully!']);
                    exit;
                }
                
                // Regular form submission
                $success = "Service added successfully!";
                header("Location: ../Staff-Pages/packages&services.php");
                exit();
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
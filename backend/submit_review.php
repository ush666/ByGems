<?php
session_start();
require_once '../includes/db.php'; // Assumes $pdo is defined here

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    $message = $_POST['message'] ?? '';
    $imagePaths = [];

    if (!$userId) {
        echo json_encode(['success' => false, 'error' => 'User not logged in.']);
        exit;
    }

    // Image Upload
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = '../uploads/reviews/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['images']['error'][$index] === 0) {
                $originalName = basename($_FILES['images']['name'][$index]);
                $newName = uniqid('rev_') . "_" . $originalName;
                $destination = $uploadDir . $newName;

                if (move_uploaded_file($tmpName, $destination)) {
                    $imagePaths[] = 'uploads/reviews/' . $newName; // Relative path
                }
            }
        }
    }

    // Insert review with JSON-encoded images
    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, message, images) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $message, json_encode($imagePaths)]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
}
<?php
require_once '../includes/db.php';

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $name = $_POST['name'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $availability = $_POST['availability'];

        $image_name = null;
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../uploads/";
            $image_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }

        $stmt = $pdo->prepare("
            INSERT INTO packages_services (name, category_id, price, description, image, availability)
            VALUES (:name, :category_id, :price, :description, :image, :availability)
        ");
        $stmt->execute([
            ':name' => $name,
            ':category_id' => $category_id,
            ':price' => $price,
            ':description' => $description,
            ':image' => $image_name,
            ':availability' => $availability
        ]);

        echo json_encode(['success' => true, 'message' => 'Service/Package created successfully!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

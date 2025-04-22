<?php
// update_item.php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'customer') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id          = $_POST['id'];
        $type        = $_POST['type'];
        $name        = $_POST['name'];
        $price       = $_POST['price'];
        $description = $_POST['description'];
        $availability= isset($_POST['availability']) && $_POST['availability']=='on' ? 1 : 0;

        // Determine category/subcats (same logic as create)
        if ($type === 'propup') {
            $category_id = 1; $service_sub_id = null; $cake_sub_id = null;
        } elseif ($type === 'party') {
            $category_id = 2; $service_sub_id = null; $cake_sub_id = null;
            $package_list = $_POST['package_list'] ?? null;
        } else {
            $category_id = 3;
            $serviceMap = ['entertainer'=>1,'foodcart'=>2,'amenities'=>3,'decorations'=>4,'accessories'=>5,'cakes'=>6,'tier_cakes'=>6,'desserts'=>6,'cupcakes'=>6];
            $cakeMap    = ['cakes'=>1,'tier_cakes'=>2,'desserts'=>3,'cupcakes'=>4];
            $service_sub_id = $serviceMap[$type] ?? null;
            $cake_sub_id    = $cakeMap[$type] ?? null;
            $duration       = $_POST['duration'] ?? null;
        }

        // Handle image (optional replace)
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = '../uploads/';
            $image_name = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image_name);
            $imgSql = ", image = :image";
        } else {
            $imgSql = '';
        }

        $sql = "UPDATE packages_services SET
            name = :name,
            price = :price,
            description = :description,
            availability = :availability,
            category_id = :cat,
            service_subcategory_id = :sub,
            cake_subcategory_id = :cake,
            package_list = :plist,
            duration = :dur"
            . $imgSql .
            " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':name'=>$name,':price'=>$price,':description'=>$description,
            ':availability'=>$availability,':cat'=>$category_id,
            ':sub'=>$service_sub_id,':cake'=>$cake_sub_id,
            ':plist'=>$package_list ?? null,':dur'=>$duration ?? null,
            ':id'=>$id
        ];
        if (!empty($image_name)) $params[':image'] = $image_name;

        $stmt->execute($params);
        echo json_encode(['success'=>true,'message'=>'Item updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
    }
    exit;
}

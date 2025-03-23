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
$service = null;

// Fetch ENUM values for categories
$stmt = $pdo->query("SHOW COLUMNS FROM services LIKE 'category'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
$categories = explode("','", $matches[1]);

// Get service ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $service_id = $_GET['id'];

    // Fetch service details
    $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Service not found!");
    }
} else {
    die("Invalid service ID!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = trim($_POST['service_name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $image = $service['image']; // Keep existing image if no new image is uploaded

    if (empty($service_name) || empty($category) || empty($description)) {
        $error = "All fields except price and image are required!";
    } else {
        // Handle image upload (if new image is selected)
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
                    $image = $imageFileName; // Update image name if upload is successful
                } else {
                    $error = "Error uploading image.";
                }
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("UPDATE services SET service_name = ?, category = ?, price = ?, description = ?, image = ? WHERE service_id = ?");

                $final_price = ($_SESSION['role'] === 'admin' && !empty($price)) ? $price : null;

                $stmt->execute([$service_name, $category, $final_price, $description, $image, $service_id]);
                $success = "Service updated successfully!";
                // Refresh data
                $service['service_name'] = $service_name;
                $service['category'] = $category;
                $service['description'] = $description;
                $service['price'] = $final_price;
                $service['image'] = $image;
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="services_style.css">
</head>
<body>

<div class="container">
    <h2>Edit Service</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="service_name">Service Name:</label>
        <input type="text" id="service_name" name="service_name" value="<?= htmlspecialchars($service['service_name']) ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="" disabled>Select a category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= ($service['category'] == $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($service['description']) ?></textarea>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <label for="price">Price (PHP):</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($service['price']) ?>">
        <?php else: ?>
            <p>Only admins can edit prices.</p>
        <?php endif; ?>

        <label for="image">Upload New Image (Optional):</label>
        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">

        <div style="margin-top: 10px;">
            <p>Current Image:</p>
            <?php if (!empty($service['image'])): ?>
                <img id="existingImagePreview" src="../uploads/<?= htmlspecialchars($service['image']) ?>" alt="Existing Image" style="max-width: 200px; display: block;">
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 10px;">
            <p>New Image Preview:</p>
            <img id="newImagePreview" src="#" alt="New Image Preview" style="display:none; max-width: 200px;">
        </div>

        <button type="submit">Save Changes</button>
    </form>
    
    <br>
    <a href="services_management.php">Back to Services</a>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('newImagePreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>

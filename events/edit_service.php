<?php
session_start();
require_once '../includes/db.php';

// Restrict access
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    exit('Access denied');
}

$error = "";
$service = null;

// Fetch ENUM values
$stmt = $pdo->query("SHOW COLUMNS FROM services LIKE 'category'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
preg_match("/^enum\('(.*)'\)$/", $row['Type'], $matches);
$categories = explode("','", $matches[1]);

// Get service ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $service_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM services WHERE service_id = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        exit('Service not found');
    }
} else {
    exit('Invalid service ID');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = trim($_POST['service_name']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $price = isset($_POST['price']) ? trim($_POST['price']) : null;
    $image = $service['image']; // keep existing image unless replaced

    if (empty($service_name) || empty($category) || empty($description)) {
        $error = "All fields except price and image are required!";
    } else {
        // If a new image is uploaded
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
                $stmt = $pdo->prepare("UPDATE services SET service_name = ?, category = ?, price = ?, description = ?, image = ? WHERE service_id = ?");
                $final_price = ($_SESSION['role'] !== 'customer' && !empty($price)) ? $price : null;
                $stmt->execute([$service_name, $category, $final_price, $description, $image, $service_id]);

                // Set success message
                header("Location: packages&services.php?message=success");
                exit();
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}

// If there is an error, show it
if (!empty($error)) {
    echo "<script>alert('{$error}'); window.history.back();</script>";
    exit();
}
?>



<div class="modal-header">
    <h5 class="modal-title">Edit Service</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form id="editServiceForm" action="edit_service.php?id=<?= $service_id ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="mb-3 col-6">
                <label for="service_name" class="form-label">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" value="<?= htmlspecialchars($service['service_name']) ?>" required>
            </div>

            <div class="mb-3 col-6">
                <label for="category" class="form-label">Service Name</label>
                <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($service['category']) ?>" required disabled>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea>
        </div>

        <?php if ($_SESSION['role'] !== 'customer'): ?>
            <div class="mb-3">
                <label for="price" class="form-label">Price (PHP)</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">₱</span>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= htmlspecialchars($service['price']) ?>">
                </div>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="image" class="form-label">Upload New Image (Optional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewNewImage(event)">
        </div>

        <div class="row">
            <div class="mb-3 col-6">
                <p>Current Image:</p>
                <?php if (!empty($service['image'])): ?>
                    <img id="existingImagePreview" src="../uploads/<?= htmlspecialchars($service['image']) ?>" alt="Existing Image" style="max-width: 200px; display: block;">
                <?php else: ?>
                    <p>No image uploaded.</p>
                <?php endif; ?>
            </div>

            <div class="mb-3 col-6">
                <p id="newImagePreviewText" style="display:none;">New Image Preview:</p>
                <img id="newImagePreview" src="#" alt="New Image Preview" style="display:none; max-width: 200px;">
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

<script>
    function previewNewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('newImagePreview');
            var outputText = document.getElementById('newImagePreviewText');
            output.src = reader.result;
            output.style.display = 'block';
            outputText.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
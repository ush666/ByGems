<?php
session_start();
require_once '../includes/db.php';

// (Optional) Redirect non‑customers if you need a login
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../User-Pages/home.php");
//     exit();
// }

// Find the Prop‑Up Packages category ID
$wantedCategory = 'Prop-Up Packages';

// Simple query with a WHERE clause
$query = "SELECT * 
          FROM services 
          WHERE status = 'enabled'
          AND category = :category";

$stmt = $pdo->prepare($query);
$stmt->execute([
    ':category' => $wantedCategory
]);

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Prop‑Up Packages</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <?php
    $servicesNav = "font-bold";
    include("../components/header.php");
    ?>
    <div class="container" style="padding-top: 100px !important;">
        <!-- Tabs Section -->
        <div class="tabs mb-4">
            <a href="./prop-up_packages.php" class="tab-btn tab-active">Prop‑Up Packages</a>
            <a href="./party_packages.php" class="tab-btn">Party Packages</a>
            <a href="./services_entertainers.php" class="tab-btn">Our Services</a>
        </div>

        <!-- Cards Section -->
        <div class="card-container">
            <?php if (empty($services)): ?>
                <p class="text-muted">No Prop‑Up Packages available at the moment.</p>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="card">
                        <img
                            src="<?php echo htmlspecialchars('../uploads/' . $service['image']); ?>"
                            alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                        <div class="card-content">
                            <div class="card-title">
                                <?php echo htmlspecialchars($service['service_name']); ?>
                            </div>
                            <div class="card-price">
                                ₱ <?php echo number_format($service['price'], 2); ?>
                            </div>
                            <p class="card-description">
                                <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                            </p>
                            <button class="btn-cart add-to-cart"
                                data-service-id="<?php echo $service['service_id']; ?>"
                                data-price="<?php echo $service['price']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>

    <script src="../js/ajax.js"></script>

</body>

</html>
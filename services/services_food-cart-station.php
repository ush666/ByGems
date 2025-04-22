<?php
session_start();
require_once '../includes/db.php';

// Fetch services under "Food Cart Stations" category with active status
$sql = "SELECT * FROM services WHERE category = 'Food Cart Stations' AND status = 'enabled'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Food Cart Stations</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <?php
    $servicesTab = "font-bold"; // active class for nav
    include("../components/header.php");
    ?>
    <div class="container py-5" style="padding-top: 100px !important;">
        <!-- Tabs Section -->
        <div class="tabs">
            <a href="./prop-up_packages.php" class="tab-btn">Prop-Up Packages</a>
            <a href="./party_packages.php" class="tab-btn">Party Packages</a>
            <a href="./services_entertainers.php" class="tab-btn">Our Services</a>
        </div>
        <!-- Category Filters -->
        <div class="d-flex justify-content-center mb-4 flex-wrap">
            <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2">Entertainers</a>
            <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2 category-active">Food Cart Stations</a>
            <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
            <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
            <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
            <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
        </div>

        <!-- Food Cart Station Grid -->
        <div class="card-container">
            <?php if (empty($services)): ?>
                <p class="text-muted">No Food Cart Stations available right now.</p>
            <?php else: ?>
                <?php foreach ($services as $service) : ?>
                    <div class="card">
                        <img src="../uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                        <div class="card-content">
                            <div class="card-title"><?php echo htmlspecialchars($service['service_name']); ?></div>
                            <div class="card-price">
                                ₱ <?php echo number_format($service['price'], 2); ?>
                                <?php if (!empty($service['price_unit'])) : ?>
                                    <span class="card-text">/ <?php echo htmlspecialchars($service['price_unit']); ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="card-description">
                                <?php echo htmlspecialchars($service['description']); ?>
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
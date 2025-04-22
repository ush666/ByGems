<?php
session_start();
require_once '../includes/db.php';

// Fetch services under "Cupcakes and Brownies" category with active status
$sql = "SELECT * FROM services WHERE category IN ('Cupcakes', 'Brownies') AND status = 'enabled'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$servicess = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Party Packages</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <?php
    $services = "font-bold";
    include("../components/header.php");
    ?>
    <style>
        ul.ul {
            min-height: 100px;
        }

        .arrows {
            display: flex;
            gap: 8px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .arrow-btn {
            background: #28a745;
            color: #fff;
            border: 1px solid #ccc;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .arrow-btn:hover {
            color: #fff;
            background:rgb(51, 170, 79);
        }
    </style>
    <div class="container py-5" style="padding-top: 100px !important;">
        <!-- Tabs Section -->
        <div class="tabs">
            <a href="./prop-up_packages.php" class="tab-btn">Prop-Up Packages</a>
            <a href="./party_packages.php" class="tab-btn">Party Packages</a>
            <a href="./services_entertainers.php" class="tab-btn tab-active">Our Services</a>
        </div>
        <!-- Category Filters -->
        <div class="d-flex justify-content-center flex-wrap">
            <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2">Entertainers</a>
            <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2">Food Cart
                Stations</a>
            <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
            <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
            <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
            <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2 category-active">Cakes &
                Cupcakes</a>
        </div>
        <!-- Tabs -->
        <div class="container">
            <div class="d-flex justify-content-center gap-3">
                <a href="./services_cakes-and-cupcakes.php" class="tab-third-btn text-decoration-none">Cakes</a>
                <a href="./services_tier-cakes.php" class="tab-third-btn text-decoration-none">Tier Cakes</a>
                <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none">Dessert Packages</a>
                <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none third-active">Cupcakes & Brownies</a>
            </div>
        </div>

        <div class="card-container">
            <?php if (empty($servicess)): ?>
                <p class="text-muted">No Cupcakes and Brownies available right now.</p>
            <?php else: ?>
                <?php foreach ($servicess as $service) : ?>
                    <!-- Card 1 -->
                    <div class="card">
                        <img src="../uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                        <div class="card-content">
                            <div class="card-title"><?php echo htmlspecialchars($service['service_name']); ?></div>
                            <div class="card-price">
                                ₱ <?php echo number_format($service['price'], 2); ?> <span class="card-text">/ PC</span>
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
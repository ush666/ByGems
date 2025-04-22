<?php
session_start();
require_once '../includes/db.php';

// Only show enabled Party Packages
$wantedCategory = 'Entertainers';
$query = "
  SELECT service_id, service_name, price, description, image, entertainer_duration_options
  FROM services
  WHERE category = :category
    AND status = 'enabled'
  ORDER BY service_name
";
$stmt = $pdo->prepare($query);
$stmt->execute([':category' => $wantedCategory]);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Entertainers</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body>
    <?php
    $servicesNav = "font-bold";
    include("../components/header.php");
    ?>

    <div class="container py-5" style="padding-top: 100px !important;">
        <!-- Tabs Section -->
        <div class="tabs">
            <a href="./prop-up_packages.php" class="tab-btn">Prop-Up Packages</a>
            <a href="./party_packages.php" class="tab-btn">Party Packages</a>
            <a href="./services_entertainers.php" class="tab-btn tab-active">Our Services</a>
        </div>

        <!-- Category Filters -->
        <div class="d-flex justify-content-center mb-4 flex-wrap">
            <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2 category-active">Entertainers</a>
            <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2">Food Cart Stations</a>
            <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
            <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
            <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
            <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
        </div>

        <!-- Cards -->
        <div class="card-container">
            <?php if (empty($services)): ?>
                <p class="text-muted">No party packages available right now.</p>
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

                            <?php
                            // break description into list items by comma
                            $items = array_filter(array_map('trim', explode(',', $service['description'])));
                            ?>

                            <?php if (!empty($items)): ?>
                                <ul class="card-description ul" style="min-height: 0px !important;">
                                    <?php foreach ($items as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="card-description">
                                    <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                                </p>
                            <?php endif; ?>

                            <?php
                            // Handle entertainer_duration_option
                            $durationOptions = json_decode($service['entertainer_duration_options'], true);
                            if (!empty($durationOptions)):
                            ?>
                                <div class="mb-2">
                                    <select class="form-select" name="duration_option_<?php echo $service['service_id']; ?>">
                                        <option value="" disabled>Select Duration</option>
                                        <?php foreach ($durationOptions as $option): ?>
                                            <option value="<?php echo htmlspecialchars($option['value']); ?>">
                                                <?php echo htmlspecialchars($option['value']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

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
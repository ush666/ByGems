<?php
session_start();
require_once '../includes/db.php';

// Only show enabled Entertainers
$wantedCategory = 'Dessert Packages';

// Fetch active discounts
$discountQuery = "SELECT * FROM discounts 
                 WHERE is_active = 1 
                 AND start_date <= NOW() 
                 AND end_date >= NOW()";
$discountStmt = $pdo->prepare($discountQuery);
$discountStmt->execute();
$activeDiscounts = $discountStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch services
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

// Apply discounts
foreach ($services as &$service) {
    $service['total_discount'] = 0;
    $service['discount_names'] = [];

    foreach ($activeDiscounts as $discount) {
        $applies = false;

        if ($discount['discount_application'] === 'all') {
            $applies = true;
        } elseif ($discount['discount_application'] === 'specific') {
            $serviceIds = explode(',', $discount['specific_service_ids']);
            if (in_array($service['service_id'], $serviceIds)) {
                $applies = true;
            }
        }

        if ($applies) {
            if ($discount['discount_type'] === 'percentage') {
                $service['total_discount'] += $service['price'] * ($discount['discount_value'] / 100);
            } else {
                $service['total_discount'] += $discount['discount_value'];
            }
            $service['discount_names'][] = $discount['discount_name'];
        }
    }

    if ($service['total_discount'] > 0) {
        $service['discounted_price'] = max(0, $service['price'] - $service['total_discount']);
        $service['discount_summary'] = implode(', ', $service['discount_names']);
    }
}
unset($service);
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
                <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none third-active">Dessert Packages</a>
                <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none">Cupcakes & Brownies</a>
            </div>
        </div>

        <!-- Cards -->
        <div class="card-container">
            <?php if (empty($services)): ?>
                <p class="text-muted">No Dessert Packages available right now.</p>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="card">
                        <?php if (isset($service['discounted_price'])): ?>
                            <div class="discount-badge" data-bs-toggle="tooltip"
                                 title="<?php echo htmlspecialchars($service['discount_summary']); ?>">
                                Discounted!
                            </div>
                        <?php endif; ?>

                        <img
                            src="<?php echo htmlspecialchars('../uploads/' . $service['image']); ?>"
                            alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                        <div class="card-content">
                            <div class="card-title">
                                <?php echo htmlspecialchars($service['service_name']); ?>
                            </div>

                            <div class="card-price">
                                <?php if (isset($service['discounted_price'])): ?>
                                    <div class="card-original-price">
                                        ₱ <?php echo number_format($service['price'], 2); ?>
                                    </div>
                                    <div class="card-price text-danger">
                                        ₱ <?php echo number_format($service['discounted_price'], 2); ?>
                                        <small class="text-success">(<?php echo htmlspecialchars($service['discount_summary']); ?>)</small>
                                    </div>
                                <?php else: ?>
                                    ₱ <?php echo number_format($service['price'], 2); ?>
                                <?php endif; ?>
                            </div>

                            <?php
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
                                    data-price="<?php echo isset($service['discounted_price']) ? $service['discounted_price'] : $service['price']; ?>">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
    include("../components/footer.php");
    ?>
    
    <script src="../js/ajax.js"></script>
</body>

</html>
<?php
session_start();
require_once '../includes/db.php';

$wantedCategory = 'Party Packages';

// Get all active discounts
$discountQuery = "SELECT * FROM discounts 
                 WHERE is_active = 1 
                 AND start_date <= NOW() 
                 AND end_date >= NOW()";
$discountStmt = $pdo->prepare($discountQuery);
$discountStmt->execute();
$activeDiscounts = $discountStmt->fetchAll(PDO::FETCH_ASSOC);

// Get all services in the category
$serviceQuery = "SELECT service_id, service_name, price, description, image
                FROM services
                WHERE category = :category
                AND status = 'enabled'
                ORDER BY service_name";
$serviceStmt = $pdo->prepare($serviceQuery);
$serviceStmt->execute([':category' => $wantedCategory]);
$services = $serviceStmt->fetchAll(PDO::FETCH_ASSOC);

// Apply discounts to services
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
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>ByGems | Party Packages</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/service.css" rel="stylesheet">
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <?php
    $servicesNav = "font-bold";
    include("../components/header.php");
    ?>
    <div class="container py-5" style="padding-top:100px!important;">
        <!-- Tabs -->
        <div class="tabs mb-4">
            <a href="./prop-up_packages.php" class="tab-btn">Prop‑Up Packages</a>
            <a href="./party_packages.php" class="tab-btn tab-active">Party Packages</a>
            <a href="./services_entertainers.php" class="tab-btn">Our Services</a>
        </div>

        <div class="card-container">
            <?php if (empty($services)): ?>
                <p class="text-muted">No party packages available right now.</p>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <div class="card">
                        <?php if (isset($service['discounted_price'])): ?>
                            <div class="discount-badge" data-bs-toggle="tooltip"
                                title="<?php echo htmlspecialchars($service['discount_summary']); ?>">
                                Discount!
                            </div>
                        <?php endif; ?>
                        <img src="<?php echo htmlspecialchars('../uploads/' . $service['image']); ?>"
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
                                    <div class="card-price">
                                        ₱ <?php echo number_format($service['price'], 2); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            $items = array_filter(array_map('trim', explode(',', $service['description'])));
                            ?>
                            <?php if (!empty($items)): ?>
                                <ul class="card-description ul">
                                    <?php foreach ($items as $item): ?>
                                        <li><?php echo htmlspecialchars($item); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="card-description">
                                    <?php echo nl2br(htmlspecialchars($service['description'])); ?>
                                </p>
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

    <?php include("../components/footer.php"); ?>

    <script src="../js/ajax.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

</body>

</html>

<?php
session_start();
require_once '../includes/db.php';

// Only show enabled Party Packages
$wantedCategory = 'Party Packages';
$query = "
  SELECT service_id, service_name, price, description, image
  FROM services
  WHERE category = :category
    AND status   = 'enabled'
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
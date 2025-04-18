<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Entertainers</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/tiercakes.css">

</head>

<body>
    <?php
    $services = "font-bold";
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
                <a href="./services_tier-cakes.php" class="tab-third-btn text-decoration-none third-active">Tier Cakes</a>
                <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none">Dessert Packages</a>
                <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none">Cupcakes & Brownies</a>
            </div>
        </div>

        <section class="container grid-container">
            <div class="grid">
                <!-- Card 1 -->
                <div class="card">
                    <img src="./img/cake1.jpg" alt="1 Tier Cake" class="card-img">
                    <div class="card-content">
                        <h3>Classic Vanilla</h3>
                        <p>₱ 3,100</p>
                        <p>Size: 9 x 4 Round</p>
                        <p class="card-description">Cake designs are customized based on the party theme and the client's specific preferences. Book at least 5 days before the event.</p>
                        <div class="card-footer">
                            <button class="btn btn-success">Add to Cart</button>
                            <div class="arrows">
                                <button class="btn arrow-btn">&lt;</button>
                                <button class="btn arrow-btn">&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="./img/cake2.jpg" alt="Chocolate Moist Cake" class="card-img">
                    <div class="card-content">
                        <h3>1 Layer Cake Chocolate Moist</h3>
                        <p>₱ 1,650</p>
                        <p>Size: 6 x 3 Round</p>
                        <p class="card-description">Cake designs are customized based on the party theme and the client's specific preferences. Book at least 5 days before the event.</p>
                        <div class="card-footer">
                            <button class="btn btn-success">Add to Cart</button>
                            <div class="arrows">
                                <button class="btn arrow-btn">&lt;</button>
                                <button class="btn arrow-btn">&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="./img/cake3.jpg" alt="2 Tier Cake" class="card-img">
                    <div class="card-content">
                        <h3>Tier 2 Cake Flavor: Classic Vanilla</h3>
                        <p>₱ 4,560</p>
                        <p>Base: 9x5 Round<br>Top: 6x4 Round</p>
                        <p class="card-description">Cake designs are customized based on the party theme and the client's specific preferences. Book at least 5 days before the event.</p>
                        <div class="card-footer">
                            <button class="btn btn-success">Add to Cart</button>
                            <div class="arrows">
                                <button class="btn arrow-btn">&lt;</button>
                                <button class="btn arrow-btn">&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="./img/cake4.jpg" alt="3 Tier Cake" class="card-img">
                    <div class="card-content">
                        <h3>Tier 3 Cake Flavor: Classic Vanilla</h3>
                        <p>₱ 4,560</p>
                        <p>Base: 12x4 Round<br>Middle: 9x4 Round<br>Top: 6x4 Round</p>
                        <p class="card-description">Cake designs are customized based on the party theme and the client's specific preferences. Book at least 5 days before the event.</p>
                        <div class="card-footer">
                            <button class="btn btn-success">Add to Cart</button>
                            <div class="arrows">
                                <button class="btn arrow-btn">&lt;</button>
                                <button class="btn arrow-btn">&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card">
                    <img src="./img/cake5.jpg" alt="3 Tier JR Cake" class="card-img">
                    <div class="card-content">
                        <h3>Tier 3 JR Cake Flavor: Classic Vanilla</h3>
                        <p>₱ 5,450</p>
                        <p>Base: 9x4 Round<br>Middle: 7x4 Round<br>Top: 5x4 Round</p>
                        <p class="card-description">Cake designs are customized based on the party theme and the client's specific preferences. Book at least 5 days before the event.</p>
                        <div class="card-footer">
                            <button class="btn btn-success">Add to Cart</button>
                            <div class="arrows">
                                <button class="btn arrow-btn">&lt;</button>
                                <button class="btn arrow-btn">&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>




    </div>
    <?php
    include("../components/footer.php");
    ?>
    <script src="../bootstrap-5.3.2-dist\js\bootstrap.bundle.min.js"></script>

</body>

</html>
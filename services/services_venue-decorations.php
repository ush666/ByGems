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
            <div class="d-flex justify-content-center mb-4 flex-wrap">
                <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2">Entertainers</a>
                <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2">Food Cart
                    Stations</a>
                <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
                <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2 category-active">Venue
                    Decorations</a>
                <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
                <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
            </div>


            <!-- Venue Decorations Grid -->
            <div class="card-container">

                <!-- Card 1 -->
                <div class="card">
                    <img src="../img/venue-decor-1.png" alt="BDD 1">
                    <div class="card-content">
                        <div class="card-title">BDD 1</div>
                        <div class="card-price">₱ 1,840 <span class="card-text">/ Save: ₱ 320</span></div>
                        <p class="card-description">
                            20 pcs Balloons on Stick<br>
                            10 sets of Table Centerpieces
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="../img/venue-decor-2.png" alt="BDD 2">
                    <div class="card-content">
                        <div class="card-title">BDD 2</div>
                        <div class="card-price">₱ 2,180 <span class="card-text">/ Save: ₱ 320</span></div>
                        <p class="card-description">
                            2 Sets Stage Decor<br>
                            2 Sets Cake Decor<br>
                            20 pcs Balloons on Stick
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="../img/venue-decor-3.png" alt="BDD 3">
                    <div class="card-content">
                        <div class="card-title">BDD 3</div>
                        <div class="card-price">₱ 2,960 <span class="card-text">/ Save: ₱ 700</span></div>
                        <p class="card-description">
                            10 Set Table Centerpieces<br>
                            2 Sets Stage Decor<br>
                            2 Sets Cake Decor
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="../img/venue-decor-4.png" alt="BDD 4">
                    <div class="card-content">
                        <div class="card-title">BDD 4</div>
                        <div class="card-price">₱ 4,495 <span class="card-text">/ Save: ₱ 1,025</span></div>
                        <p class="card-description">
                            4 Sets Stage Decors<br>
                            2 Sets Cake Decor<br>
                            1 Set Entrance Decor
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card">
                    <img src="../img/venue-decor-5.png" alt="BDD 5">
                    <div class="card-content">
                        <div class="card-title">BDD 5</div>
                        <div class="card-price">₱ 5,400 <span class="card-text">/ Save: ₱ 1,440</span></div>
                        <p class="card-description">
                            4 Sets Stage Decors<br>
                            10 Sets Table Centerpieces<br>
                            30 Sets Ceiling Decor
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="card">
                    <img src="../img/venue-decor-6.png" alt="BDD 6">
                    <div class="card-content">
                        <div class="card-title">BDD 6</div>
                        <div class="card-price">₱ 6,950 <span class="card-text">/ Save: ₱ 2,010</span></div>
                        <p class="card-description">
                            20 Sets Ceiling Decors<br>
                            10 Sets Table Decors<br>
                            2 Sets Stage Decors<br>
                            2 Sets Cake Decors<br>
                            1 Entrance Decor
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

            </div>


        </div>
        <?php
        include("../components/footer.php");
        ?>
        <script src="../bootstrap-5.3.2-dist\js\bootstrap.bundle.min.js"></script>

    </body>

</html>
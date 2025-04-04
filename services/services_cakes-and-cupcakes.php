<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Entertainers - ByGems</title>
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
                    <a href="./services_cakes-and-cupcakes.php" class="tab-third-btn text-decoration-none third-active">Cakes</a>
                    <a href="./services_tier-cakes.php" class="tab-third-btn text-decoration-none">Tier Cakes</a>
                    <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none">Dessert Packages</a>
                    <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none">Cupcakes & Brownies</a>
                </div>
            </div>

            <!-- Cakes & Cupcakes Grid -->
            <div class="container py-5">
                <div class="row g-4">
                    <!-- Card 1 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-1.png" class="card-img-top" alt="Dark Mocha Cake">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Dark Mocha Cake</h5>
                                <p class="card-text"><strong>₱ 540</strong></p>
                                <p>Size: 6×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-2.png" class="card-img-top" alt="Blue Berry Delight Cake">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Blue Berry Delight Cake</h5>
                                <p class="card-text"><strong>₱ 590</strong></p>
                                <p>Size: 7×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-3.png" class="card-img-top" alt="Ube Cream Cake">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Ube Cream Cake</h5>
                                <p class="card-text"><strong>₱ 650</strong></p>
                                <p>Size: 8×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-4.png" class="card-img-top" alt="Carrot Cake">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Carrot Cake</h5>
                                <p class="card-text"><strong>₱ 850</strong></p>
                                <p>Size: 7×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-5.png" class="card-img-top" alt="Fruityberry Small">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Fruityberry Small</h5>
                                <p class="card-text"><strong>₱ 500</strong></p>
                                <p>Size: 5×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <img src="../img/cake-6.png" class="card-img-top" alt="Fruityberry Grande">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Fruityberry Grande</h5>
                                <p class="card-text"><strong>₱ 950</strong></p>
                                <p>Size: 8×3</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success w-100">Add to Cart</button>
                                </div>
                            </div>
                        </div>
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
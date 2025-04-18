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
                <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2 category-active">Amenities</a>
                <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
                <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
                <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
            </div>


            <!-- Amenities Grid -->
            <div class="card-container">

                <!-- Card 1 -->
                <div class="card">
                    <img src="../img/amenities-1.png" alt="Face Painting">
                    <div class="card-content">
                        <div class="card-title">🎨 Face Painting</div>
                        <div class="card-price">₱ 2,000 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            1 artist, 3 hrs. unlimited.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="../img/amenities-2.png" alt="Small Inflatable Playground">
                    <div class="card-content">
                        <div class="card-title">🎪 Small Inflatable Playground</div>
                        <div class="card-price">₱ 2,500 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. rent, size 17 ft x 13 ft.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="../img/amenities-3.png" alt="Big Inflatable Playground">
                    <div class="card-content">
                        <div class="card-title">🎡 Big Inflatable Playground</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. rent, size 21 ft x 10 ft.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="../img/amenities-4.png" alt="Photo booth - A">
                    <div class="card-content">
                        <div class="card-title">📸 Photo booth - A</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. unlimited, Medium Quality Photo Paper Prints.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card">
                    <img src="../img/amenities-5.png" alt="Photo booth - B">
                    <div class="card-content">
                        <div class="card-title">📷 Photo booth - B</div>
                        <div class="card-price">₱ 4,500 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. unlimited, Silk / Matte Quality Photo Paper Prints.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="card">
                    <img src="../img/amenities-6.png" alt="Photo booth - C">
                    <div class="card-content">
                        <div class="card-title">📷 Photo booth - C</div>
                        <div class="card-price">₱ 7,000 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. unlimited, Silk / Matte Quality Magnetic Photo Paper Prints.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="card">
                    <img src="../img/amenities-7.png" alt="Photo Coverage J1">
                    <div class="card-content">
                        <div class="card-title">📸 Photo Coverage J1</div>
                        <div class="card-price">₱ 5,700 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. photo coverage, 100 printouts, soft copies saved on USB.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="card">
                    <img src="../img/amenities-8.png" alt="LED Wall">
                    <div class="card-content">
                        <div class="card-title">💡 LED Wall</div>
                        <div class="card-price">₱ 12,000 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. rent, 9x12 ft, Video loop Presentation, Live Feed, SDE Presentation.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 9 -->
                <div class="card">
                    <img src="../img/amenities-9.png" alt="Lights and Sounds">
                    <div class="card-content">
                        <div class="card-title">🔊 Lights and Sounds</div>
                        <div class="card-price">₱ 10,000 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            3 hrs. rent, with DJ or Operator.
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
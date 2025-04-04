<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Party Packages - ByGems</title>
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
                <a href="./services_cakes-and-cupcakes.php" class="tab-third-btn text-decoration-none">Cakes</a>
                <a href="./services_tier-cakes.php" class="tab-third-btn text-decoration-none">Tier Cakes</a>
                <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none third-active">Dessert Packages</a>
                <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none">Cupcakes & Brownies</a>
            </div>
        </div>

            <div class="card-container">

                <!-- Card 1 -->
                <div class="card">
                    <img src="../img/Image.png" alt="Party Package 1">
                    <div class="card-content">
                        <div class="card-title">Dessert Package - 1</div>
                        <div class="card-price">₱ 5,900</div>
                        <ul class="card-description ul">
                            <li>approx. good for 70 heads</li>
                            <li>Tablea Rocky Road Brownies</li>
                            <li>Mini Cup Cakes - 3 Different Flavors</li>
                            <li>Mini Slice Cake</li>
                            <li>Mini Shot Glass</li>
                        </ul>
                        <button class="btn btn-success">Add to Cart</button>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="../img/Image.png" alt="Party Package 2">
                    <div class="card-content">
                        <div class="card-title">Dessert Package - 2</div>
                        <div class="card-price">₱ 8,200</div>
                        <ul class="card-description ul">
                            <li>approx. good for 70 heads</li>
                            <li>Tablea Rocky Road Brownies</li>
                            <li>Mini Cup Cakes - 3 Different Flavors</li>
                            <li>Mini Slice Cake</li>
                            <li>Carrot Cake</li>
                            <li>Butter Cookies</li>
                            <li>Mini Cake Donuts</li>
                        </ul>
                        <button class="btn btn-success">Add to Cart</button>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="../img/Image.png" alt="Party Package 3">
                    <div class="card-content">
                        <div class="card-title">Dessert Package - 3</div>
                        <div class="card-price">₱ 4,900</div>
                        <ul class="card-description ul">
                            <li>approx. good for 80 heads</li>
                            <li>Mini Cup Cakes - 3 Different Flavors</li>
                            <li>Mini Shot Glass</li>
                            <li>Carrot Cake</li>
                            <li>Ube Cream Cake</li>
                            <li>Dark Mocha Cake</li>
                            <li>Mango La Creme Cake</li>
                            <li>Coffee Peanut Butter Brownies</li>
                            <li>Triple Choco Walnut Cookies</li>
                            <li>Revel Bars</li>
                        </ul>
                        <button class="btn btn-success">Add to Cart</button>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="../img/Image.png" alt="Party Package 4">
                    <div class="card-content">
                        <div class="card-title">Dessert Package - 4</div>
                        <div class="card-price">₱ 25,000</div>
                        <ul class="card-description ul">
                            <li>approx. good for 70 heads</li>
                            <li>Tablea Rocky Road Brownies</li>
                            <li>Mini Cup Cakes - 3 Different Flavors</li>
                            <li>Mini Slice Cake</li>
                            <li>Mini Shot Glass</li>
                        </ul>
                        <button class="btn btn-success">Add to Cart</button>
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
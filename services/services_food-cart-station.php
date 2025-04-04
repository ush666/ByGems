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
            <div class="d-flex justify-content-center mb-4 flex-wrap">
                <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2">Entertainers</a>
                <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2 category-active">Food Cart
                    Stations</a>
                <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
                <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
                <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
                <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
            </div>


            <!-- Food Cart Station Grid -->
            <div class="card-container">

                <!-- Card 1 -->
                <div class="card">
                    <img src="../img/food-cart-1.png" alt="Cotton Candy">
                    <div class="card-content">
                        <div class="card-title">Cotton Candy</div>
                        <div class="card-price">₱ 2,500 <span class="card-text">/ Serve</span></div>
                        <p class="card-description">
                            100 cotton candy on stick, 3 flavor choices, 1 food cart crew; max. 3 hours stay on venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="../img/food-cart-2.png" alt="Hotdog">
                    <div class="card-content">
                        <div class="card-title">Hotdog</div>
                        <div class="card-price">₱ 3,000 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            100 regular hotdogs on stick, Beef or Chicken Flavor, 1 food cart crew; max. 3 hours stay on
                            venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="../img/food-cart-3.png" alt="Ice Cream">
                    <div class="card-content">
                        <div class="card-title">Ice Cream</div>
                        <div class="card-price">₱ 1,800 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            100 scoops of ice cream on cone, 2 classic flavors, 1 food cart crew; max. 3 hours stay on
                            venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="../img/food-cart-4.png" alt="Sweet Butter Corn">
                    <div class="card-content">
                        <div class="card-title">Sweet Butter Corn</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            100 cups of sweet butter corn with cheese powder, 1 food cart crew; max. 3 hours stay on
                            venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card">
                    <img src="../img/food-cart-5.png" alt="Pop Corn">
                    <div class="card-content">
                        <div class="card-title">Pop Corn</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            100 packs of popcorn, salted or cheese flavor, 1 food cart crew; max. 3 hours stay on venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="card">
                    <img src="../img/food-cart-6.png" alt="Milk Tea Corner">
                    <div class="card-content">
                        <div class="card-title">Milk Tea Corner</div>
                        <div class="card-price">₱ 3,000 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            Good for 50 pax, 3 milk tea flavors, with table set-up, 1-2 service staff; max. 3 hours stay
                            on venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="card">
                    <img src="../img/food-cart-7.png" alt="Dessert Station">
                    <div class="card-content">
                        <div class="card-title">Dessert Station</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Show</span></div>
                        <p class="card-description">
                            100 cups of sweet butter corn with cheese powder, 1 food cart crew; max. 3 hours stay on
                            venue.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="card">
                    <img src="../img/food-cart-8.png" alt="Food Cart Combo 1">
                    <div class="card-content">
                        <div class="card-title">Food Cart Combo 1</div>
                        <div class="card-price">₱ 8,200</div>
                        <p class="card-description">
                            Slice seasoned fruits, snacks, desserts, and food carts for 50 pax with a variety of items.
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
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
            <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2">Food Cart
                Stations</a>
            <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
            <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue
                Decorations</a>
            <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2 category-active">Party
                Accessories</a>
            <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
        </div>


        <!-- Party Accessories Grid -->
        <div class="card-container">

            <!-- Card 1 -->
            <div class="card">
                <img src="../img/party-accessories-1.png" alt="FWR (Plain)">
                <div class="card-content">
                    <div class="card-title">FWR (Plain)</div>
                    <div class="card-price">₱ 20 <span class="card-text">/ PC</span></div>
                    <p class="card-description">With stick and ribbons</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <img src="../img/party-accessories-2.png" alt="FWR (Printed)">
                <div class="card-content">
                    <div class="card-title">FWR (Printed)</div>
                    <div class="card-price">₱ 25 <span class="card-text">/ PC</span></div>
                    <p class="card-description">With stick and ribbons</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="card">
                <img src="../img/party-accessories-3.png" alt="Loot Box (w/out Content)">
                <div class="card-content">
                    <div class="card-title">Loot Box (w/out Content)</div>
                    <div class="card-price">₱ 60 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Minimum order of 20 pcs, themed layout</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <img src="../img/party-accessories-4.png" alt="Loot Box (with Content)">
                <div class="card-content">
                    <div class="card-title">Loot Box (with Content)</div>
                    <div class="card-price">₱ 95 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Minimum order of 20 pcs, themed layout with 8 activity items</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="card">
                <img src="../img/party-accessories-5.png" alt="Fun Box Piñata (w/out Content)">
                <div class="card-content">
                    <div class="card-title">Fun Box Piñata (w/out Content)</div>
                    <div class="card-price">₱ 400 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Themed FunBox cover</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="card">
                <img src="../img/party-accessories-6.png" alt="Fun Box Piñata (with Content)">
                <div class="card-content">
                    <div class="card-title">Fun Box Piñata (with Content)</div>
                    <div class="card-price">₱ 580 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Themed FunBox cover, assorted candies and chocos</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 7 -->
            <div class="card">
                <img src="../img/party-accessories-7.png" alt="Themed Party Hats">
                <div class="card-content">
                    <div class="card-title">Themed Party Hats</div>
                    <div class="card-price">₱ 20 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Minimum order of 20 pcs, themed layout</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 8 -->
            <div class="card">
                <img src="../img/party-accessories-8.png" alt="Party Popper">
                <div class="card-content">
                    <div class="card-title">Party Popper</div>
                    <div class="card-price">₱ 250 <span class="card-text">/ PC</span></div>
                    <p class="card-description">60 cm, confetti twist popper</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 9 -->
            <div class="card">
                <img src="../img/party-accessories-9.png" alt="Game Prizes">
                <div class="card-content">
                    <div class="card-title">Game Prizes</div>
                    <div class="card-price">₱ 75 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Minimum order of 20 pcs, Kiddie Toys</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 10 -->
            <div class="card">
                <img src="../img/party-accessories-10.png" alt="Personalized Invitation (print-out)">
                <div class="card-content">
                    <div class="card-title">Personalized Invitation (print-out)</div>
                    <div class="card-price">₱ 35 <span class="card-text">/ PC</span></div>
                    <p class="card-description">Minimum order of 20 pcs, with white envelope, Free Layout; 4R or
                        4×6 inches</p>
                    <a href="#" class="btn-cart">Add to Cart</a>
                </div>
            </div>

            <!-- Card 11 -->
            <div class="card">
                <img src="../img/party-accessories-11.png" alt="Personalized Invitation (Layout Only)">
                <div class="card-content">
                    <div class="card-title">Personalized Invitation (Layout Only)</div>
                    <div class="card-price">₱ 500 <span class="card-text">/ Layout</span></div>
                    <p class="card-description">Soft copy file, ByGems artist layout, 3 maximum revisions</p>
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
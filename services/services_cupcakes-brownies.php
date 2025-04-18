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
    $services = "font-bold";
    include("../components/header.php");
    ?>
    <style>
        ul.ul {
            min-height: 100px;
        }

        .arrows {
            display: flex;
            gap: 8px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .arrow-btn {
            background: #28a745;
            color: #fff;
            border: 1px solid #ccc;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .arrow-btn:hover {
            color: #fff;
            background:rgb(51, 170, 79);
        }
    </style>
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
                <a href="./services_dessert-packages.php" class="tab-third-btn text-decoration-none">Dessert Packages</a>
                <a href="./services_cupcakes-brownies.php" class="tab-third-btn text-decoration-none third-active">Cupcakes & Brownies</a>
            </div>
        </div>

        <div class="card-container">

            <!-- Card 1 -->
            <div class="card">
                <img src="../img/Image.png" alt="Party Package 1">
                <div class="card-content">
                    <div class="card-title">Cupcake with cut out topper</div>
                    <div class="card-price"><span class="card-text">Retail Price</span> ₱ 45.00 <span class="card-text">each</span></div>
                    <ul class="card-description ul">
                        <li>Chocolate moist</li>
                        <li>Frost with whip icing and topped with themed cut-outs</li>
                        <li>Minimum order of 12pcs or by dozen</li>
                    </ul>
                    <button class="btn btn-success">Add to Cart</button>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="card">
                <img src="../img/Image.png" alt="Party Package 2">
                <div class="card-content">
                    <div class="card-title">Cupcake with Fondant topper</div>
                    <div class="card-price"><span class="card-text">Retail Price</span> ₱ 75.00 <span class="card-text">each</span></div>
                    <ul class="card-description ul">
                        <li>Chocolate moist</li>
                        <li>Frost with whip icing and topped with themed fondant topper</li>
                        <li>Minimum order of 12pcs or by dozen</li>
                    </ul>
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
                <img src="../img/Image.png" alt="Party Package 3">
                <div class="card-content">
                    <div class="card-title">Ube Cake Bars</div>
                    <div class="card-price"><span class="card-text">Retail Price</span> ₱ 195.00</span></div>
                    <ul class="card-description ul">
                        <li>6 Pieces in a tub</li>
                    </ul>
                    <button class="btn btn-success">Add to Cart</button>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="card">
                <img src="../img/Image.png" alt="Party Package 4">
                <div class="card-content">
                    <div class="card-title">Revel Bars</div>
                    <div class="card-price"><span class="card-text">Retail Price</span> ₱ 245.00</div>
                    <ul class="card-description ul">
                        <li>6 Pieces in a tub</li>
                    </ul>
                    <button class="btn btn-success">Add to Cart</button>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="card">
                <img src="../img/Image.png" alt="Party Package 4">
                <div class="card-content">
                    <div class="card-title">Assorted Choco Bars</div>
                    <div class="card-price"><span class="card-text">Retail Price</span> ₱ 210.00</div>
                    <ul class="card-description ul">
                        <li>6 Pieces in a tub</li>
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
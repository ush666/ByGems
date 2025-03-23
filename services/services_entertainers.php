<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Entertainers - ByGems</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="./services_entertainers.php" class="btn btn-warning me-2 mb-2 category-active">Entertainers</a>
                <a href="./services_food-cart-station.php" class="btn btn-warning me-2 mb-2">Food Cart
                    Stations</a>
                <a href="./services_amenities.php" class="btn btn-warning me-2 mb-2">Amenities</a>
                <a href="./services_venue-decorations.php" class="btn btn-warning me-2 mb-2">Venue Decorations</a>
                <a href="./services_party-accessories.php" class="btn btn-warning me-2 mb-2">Party Accessories</a>
                <a href="./services_cakes-and-cupcakes.php" class="btn btn-warning mb-2">Cakes & Cupcakes</a>
            </div>


            <div class="card-container">

                <!-- Card 1 -->
                <div class="card">
                    <img src="./img/entertainer1.png" alt="Gemster Host Solo">
                    <div class="card-content">
                        <div class="card-title">Gemster Host Solo</div>
                        <div class="card-price">₱ 2,000 <span class="card-text">/ Show</span></div>
                        <p class="card-description">2 hours Program including game facilitation</p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>2 Hours</option>
                            <option>3 Hours</option>
                            <option>4 Hours</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card">
                    <img src="./img/entertainer2.png" alt="Magician Package Show">
                    <div class="card-content">
                        <div class="card-title">Magician (Package Show)</div>
                        <div class="card-price">₱ 3,000 <span class="card-text">/ Show</span></div>
                        <p class="card-description">Available only for booking with Gemster Host, approx. 15 mins show.
                        </p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>15 mins</option>
                            <option>30 mins</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card">
                    <img src="./img/entertainer3.png" alt="Character Mascot">
                    <div class="card-content">
                        <div class="card-title">Character Mascot</div>
                        <div class="card-price">₱ 1,800 <span class="card-text">/ Show</span></div>
                        <p class="card-description">2 appearances, max 15 mins each for dancing or photo ops.</p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>1 Appearance</option>
                            <option>2 Appearances</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="card">
                    <img src="./img/entertainer4.png" alt="Bubble Show">
                    <div class="card-content">
                        <div class="card-title">Bubble Show</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Show</span></div>
                        <p class="card-description">2 Clown Performers, approx. 30 mins show.</p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>30 mins</option>
                            <option>45 mins</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="card">
                    <img src="./img/entertainer5.png" alt="Magician Half Show">
                    <div class="card-content">
                        <div class="card-title">Magician (Half Show)</div>
                        <div class="card-price">₱ 3,500 <span class="card-text">/ Show</span></div>
                        <p class="card-description">Available only with Gemster Host, 13 Magic Tricks, approx. 30 mins
                            show.</p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>30 mins</option>
                            <option>45 mins</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="card">
                    <img src="./img/entertainer6.png" alt="Gemster Host Duo">
                    <div class="card-content">
                        <div class="card-title">Gemster Host Duo</div>
                        <div class="card-price">₱ 3,000 <span class="card-text">/ Show</span></div>
                        <p class="card-description">2 Hosts for 4 hours program or 1 Solo Host for 4 hours program.</p>

                        <label class="font-1">Duration Options</label>
                        <select class="form-select">
                            <option>Select Value</option>
                            <option>2 Hours</option>
                            <option>4 Hours</option>
                        </select>

                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>

            </div>

        </div>
        <?php
        include("../components/footer.php");
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems Services</title>
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/service.css">
        <link rel="stylesheet" href="../css/home.css">
    </head>

    <body>
        <?php
        $services = "font-bold";
        include("../components/header.php");
        ?>
        <div class="container" style="padding-top: 100px !important;">
            <!-- Tabs Section -->
            <div class="tabs">
                <a href="./prop-up_packages.php" class="tab-btn tab-active">Prop-Up Packages</a>
                <a href="./party_packages.php" class="tab-btn">Party Packages</a>
                <a href="./services_entertainers.php" class="tab-btn">Our Services</a>
            </div>
            <!-- Cards Section -->
            <div class="card-container">
                <!-- Card 1 -->
                <div class="card">
                    <img src="../img/Image-0.png" alt="Prop-up Package Set A">
                    <div class="card-content">
                        <div class="card-title">Prop-up Package Set A</div>
                        <div class="card-price">₱ 4,650</div>
                        <p class="card-description">
                            Prop styling with 1 wall background plus balloon decoration. Includes celebrant's name, age,
                            and theme accessories.
                            Occupied space estimated at 6×8ft.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="card">
                    <img src="../img/Image-1.png" alt="Prop-up Package Set B">
                    <div class="card-content">
                        <div class="card-title">Prop-up Package Set B</div>
                        <div class="card-price">₱ 5,650</div>
                        <p class="card-description">
                            Prop styling with 2 wall backgrounds plus balloon decoration. Includes celebrant's name,
                            age, and theme accessories.
                            Occupied space estimated at 6×8ft.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="card">
                    <img src="../img/Image-2.png" alt="Prop-up Package Set C">
                    <div class="card-content">
                        <div class="card-title">Prop-up Package Set C</div>
                        <div class="card-price">₱ 7,550</div>
                        <p class="card-description">
                            Prop styling with 3 wall backgrounds plus balloon decoration. Includes celebrant's name,
                            age, and theme accessories.
                            Occupied space estimated at 12×10ft.
                        </p>
                        <a href="#" class="btn-cart">Add to Cart</a>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="card">
                    <img src="../img/Image-3.png" alt="Prop-up Package Set D">
                    <div class="card-content">
                        <div class="card-title">Prop-up Package Set D</div>
                        <div class="card-price">₱ 9,700</div>
                        <p class="card-description">
                            Prop styling with 4 wall backgrounds plus balloon decoration. Includes celebrant's name,
                            age, and theme accessories.
                            Occupied space estimated at 16×12ft.
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
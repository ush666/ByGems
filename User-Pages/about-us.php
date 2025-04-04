<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About ByGems</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css"> <!-- External CSS -->
</head>

<body>

    <?php
    $about_us = "font-bold";
    include("../components/header.php");
    ?>

    <div class="main-container">
        <div class="content-container ">
            <div class="floating-icons">
                <img src="../img/bg-3.png" alt="Gift" class="img-top-left">
                <img src="../img/bg-4.png" alt="Balloons" class="img-top-right">
                <img src="../img/bg-2.png" alt="Ice Cream" class="img-bottom-left">
                <img src="../img/bg-1.png" alt="Donut" class="img-bottom-right">
            </div>

            <button class="title-btn">History Of ByGems</button>

            <div class="content d-flex flex-column">
                <p>
                    ByGems began in 2007, born from the passion of Gemma Covarrubias and Engr. Fred G. Covarrubias
                    Jr.,
                    who started arranging balloons for the birthday parties of their friends. Their creative and
                    heartfelt designs
                    quickly gained attention, and soon more people were seeking their services.
                </p>
                <p>
                    One day, after a particularly memorable event, someone asked, "Who organized this party?" The
                    response
                    was simple: "By Gems," a reference to Gemma's nickname. From that moment, ByGems grew into the
                    trusted name
                    it is today, turning every celebration into something extraordinary. The rest, as they say, is
                    history.
                </p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    include("../components/footer.php");
    ?>

    <script src="../bootstrap-5.3.2-dist\js\bootstrap.bundle.min.js"></script>

</body>

</html>
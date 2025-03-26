<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: customer_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About ByGems</title>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css"> <!-- External CSS -->
    </head>

    <body>

        <?php
        include("../components/header.php");
        ?>

        <div class="main-container">
            <div class="content-container">
                <div class="floating-icons">
                    <img src="../img/bg-3.png" alt="Gift" class="img-top-left">
                    <img src="../img/bg-4.png" alt="Balloons" class="img-top-right">
                    <img src="../img/bg-2.png" alt="Ice Cream" class="img-bottom-left">
                    <img src="../img/bg-1.png" alt="Donut" class="img-bottom-right">
                </div>

                <button class="title-btn">Welcome to ByGems</button>

                <div class="content pt-3 pb-1">
                    <p style="width: 50%;">
                        Welcome to ByGems! Let's make your next event unforgettable with our awesome services. From
                        balloons to mascots, we've got it all! Ready to party? Basta party, ByGems!
                    </p>
                </div>

                <button class="btn-view"><a class="text-white" href="./home.php">Go to Homepage</a></button>
            </div>
        </div>
        <!-- Footer -->
        <?php
        include("../components/footer.php");
        ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

</html>
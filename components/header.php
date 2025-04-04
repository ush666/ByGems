<?php
require_once '../includes/db.php'; // Database connection file
session_start();
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {


    // Fetch user data from the database
    $userId = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT username, name, email FROM account WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            session_destroy();
            header("Location: ../User-Pages/customer_login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-warning fixed-top">
    <div class="container position-relative p-0 ps-5 pe-5" style="min-height: 50px; max-width: none !important;">

        <!-- Logo in the center bottom -->
        <a href="../User-Pages/home.php" class="d-none d-lg-block position-absolute cstm-position translate-middle-x">
            <img src="../img/logo.png" alt="ByGems Logo" style="height: 100px;">
        </a>

        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item font-sm <?= $home ?>"><a class="nav-link"
                        href="../User-Pages/home.php">Home</a>
                </li>
                <li class="nav-item dropdown position-relative <?= $services ?>">
                    <a class="nav-link dropdown-toggle font-sm" href="#" id="servicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu font-sm position-absolute top-100 right-0" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="../services/prop-up_packages.php">Event Packages</a></li>
                        <li><a class="dropdown-item" href="../services/party_packages.php">Party Packages</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        <li><a class="dropdown-item" href="../services/services_entertainers.php">Other Offer</a></li>
                </li>
            </ul>
            </li>
            
            <li class="nav-item font-sm <?= $reviews ?>"><a class="nav-link"
                    href="../User-Pages/review.php">Reviews</a>
            </li>
            <li class="nav-item font-sm <?= $about_us ?>"><a class="nav-link"
                    href="../User-Pages/about-us.php">About
                    Us</a>
            </li>
            </ul>
        </div>

        <div class="ms-auto">
            <?php
            // Simulating user authentication status (replace with your actual session check)
            $isLoggedIn = isset($_SESSION['user_id']);
            ?>

            <?php if ($isLoggedIn): ?>
                <!-- Show Profile Dropdown if Logged In -->
                <ul class="navbar-nav gap-3">
                    <style>
                        /* Show dropdown menu on hover */
                        .nav-item.dropdown:hover>.dropdown-menu {
                            display: block;
                            margin-top: 0;
                            top: 40px;
                            right: 0;
                        }
                    </style>

                    <li class="nav-item dropdown font-2 d-flex align-items-center font-brown me-2">
                        <a class="nav-link d-flex align-items-center" href="#" id="cartDropdown" role="button">
                            <ion-icon name="cart-outline" size="medium"></ion-icon>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end cart-dropdown" aria-labelledby="cartDropdown">
                            <li class="px-3 pb-2 text-center">
                                <strong>Cart</strong>
                            </li>
                            <li>
                                <div class="cart-item">
                                    <img src="img1.jpg" alt="Item 1">
                                    <div>
                                        <h6>Prop-up Package Set-A</h6>
                                        <p>₱ 4,650</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cart-item">
                                    <img src="img2.jpg" alt="Item 2">
                                    <div>
                                        <h6>Gemster Host Solo</h6>
                                        <p>₱ 2,000</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cart-item">
                                    <img src="img3.jpg" alt="Item 3">
                                    <div>
                                        <h6>Magician (Package Show)</h6>
                                        <p>₱ 3,000</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cart-item">
                                    <img src="img4.jpg" alt="Item 4">
                                    <div>
                                        <h6>Character Mascot</h6>
                                        <p>₱ 3,600</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cart-item">
                                    <img src="img5.jpg" alt="Item 5">
                                    <div>
                                        <h6>Food Cart: Cotton Candy</h6>
                                        <p>₱ 2,500</p>
                                    </div>
                                </div>
                            </li>
                            <li class="px-3 pt-2 row">
                                <div class="col-6 cart-total">Cart Total: ₱ 15,750</div>
                                <a href="../User-Pages/cart.php" class="col-6 cart-btn text-center">View Cart</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item d-flex align-items-center position-relative gap-2">
                        <a class="text-decoration-none position-relative" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="../img/user_avatar.png" alt="Profile Picture"
                                style="width: 40px; height: 40px; border-radius: 50%;">

                            <!-- Dropdown Icon -->
                            <i class="dropdown-icon position-absolute bottom-0 end-0">
                                <ion-icon name="chevron-down" style="color: #555;"></ion-icon>
                            </i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end position-absolute end-0 top-100 z-3 p-1">
                            <li class="px-3 py-1">
                                <span><?= ucfirst($user['name']) ?></span>
                                <div class="text-muted font-01"><?= $user['email'] ?></div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="../includes/logout.php">
                                    <button type="submit"
                                        class="btn w-100 text-start d-flex justify-content-between align-items-center gap-3 bg-transparent border-0">
                                        <span class="text-muted font-0">Log out</span>
                                        <span>
                                            <ion-icon name="log-out-outline"></ion-icon>
                                        </span>
                                    </button>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

            <?php else: ?>
                <!-- Show Login and Sign Up Buttons if Logged Out -->
                <div class="d-flex">
                    <a class="btn btn-outline-dark me-1 ps-3 pe-3"
                        style="border-radius: 0px; border-top-left-radius: 20px; border-bottom-left-radius: 20px; font-size: 0.8rem; transition: all 0.3s;"
                        href="../User-Pages/customer_login.php">Login</a>
                    <a class="btn btn-dark ps-3 pe-3"
                        style="border-radius: 0px; border-top-right-radius: 20px; border-bottom-right-radius: 20px; font-size: 0.8rem; color: white !important; transition: all 0.3s;"
                        href="../User-Pages/customer_register.php">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Initialize Tooltip -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
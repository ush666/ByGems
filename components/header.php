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
            echo "User not found!";
            exit;
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
                <li class="nav-item dropdown <?= $services ?>">
                    <a class="nav-link dropdown-toggle font-sm" href="#" id="servicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu font-sm" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="event-planning.php">Place Holder</a></li>
                        <li><a class="dropdown-item" href="decorations.php">Place Holder</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="../services/prop-up_packages.php">Event Packages</a></li>
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
            <ul class="navbar-nav">
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
                    style="border-radius: 0px; border-top-left-radius: 20px; border-bottom-left-radius: 20px; font-size: 0.8rem;"
                    href="../User-Pages/customer_login.php">Login</a>
                <a class="btn btn-dark ps-3 pe-3"
                    style="border-radius: 0px; border-top-right-radius: 20px; border-bottom-right-radius: 20px; font-size: 0.8rem; color: white !important;"
                    href="../User-Pages/customer_register.php">Sign Up</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
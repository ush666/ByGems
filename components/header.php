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

        <!-- Login and Sign Up Buttons -->
        <div class="d-flex">
            <a class="btn btn-outline-dark me-1 ps-3 pe-3"
                style="border-radius: 0px; border-top-left-radius: 20px; border-bottom-left-radius: 20px; font-size: 0.8rem;"
                href="../User-Pages/customer_login.php">Login</a>
            <a class="btn btn-dark ps-3 pe-3"
                style="border-radius: 0px; border-top-right-radius: 20px; border-bottom-right-radius: 20px; font-size: 0.8rem; color: white !important;"
                href="../User-Pages/customer_register.php">Sign Up</a>
        </div>

    </div>
</nav>
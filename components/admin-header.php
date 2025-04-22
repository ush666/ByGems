<?php
require_once '../includes/db.php'; // Database connection file
// Check if the user is logged in
if (isset($_SESSION['user_id'])) {


    // Fetch user data from the database
    $userId = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT username, name, email FROM account WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = $pdo->prepare("SELECT role FROM account WHERE user_id = ?");
        $query->execute([$userId]);
        $role = $query->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            session_destroy();
            header("Location: ../Staff-Pages/staff_login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

?>

<!-- Navbar -->
<nav class="navbar navbar-expand-sm navbar-light bg-warning fixed-top admin-header">
    <div class="container-fluid position-relative p-0 ps-3 pe-3" style="min-height: 50px; max-width: none !important;">
        <!-- Navbar Toggler - Moved to the left -->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo in the center bottom - Visible on lg screens -->
        <a href="../User-Pages/home.php" class="d-none d-lg-block position-absolute cstm-position translate-middle-x">
            <img src="../img/logo.png" alt="ByGems Logo" style="height: 100px;">
        </a>

        <!-- Logo for mobile - Smaller and centered -->
        <a href="../User-Pages/home.php" class="d-lg-none ms-auto">
            <img src="../img/logo.png" alt="ByGems Logo" style="height: 60px;">
        </a>

        <!-- Offcanvas Menu for Mobile -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="ms-auto displa  ">
                <?php
                // Simulating user authentication status (replace with your actual session check)
                $isLoggedIn = isset($_SESSION['user_id']);
                ?>

                <?php if ($isLoggedIn): ?>

                <?php else:
                    header("Location: ../login/customer_login.php");
                endif; ?>
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
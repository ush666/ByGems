<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user info, including role
    $stmt = $pdo->prepare("SELECT user_id, password, role FROM account WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin' || $user['role'] === 'staff') {
            header("Location: ../Staff-Pages/cms_dashboard.php");
        } elseif ($user['role'] === 'customer') {
            header("Location: ../User-Pages/home.php");
        } else {
            // Optional: handle unknown roles
            header("Location: error.php");
        }
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems | Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/login.css">
    </head>

    <body class="position-relative">
        <a href="../User-Pages/home.php" class="d-none d-lg-block position-absolute cstm-position translate-middle-x"
            style="left: 10%;top: 1%;">
            <img src="../img/logo.png" alt="ByGems Logo" style="height: 100px;">
        </a>

        <!-- Left Section (Background Image) -->
        <div class="left-section d-none d-md-block"></div>

        <!-- Right Section (Form) -->
        <div class="right-section">
            <h1>Welcome to Bygems</h1>

            <!-- Error Message -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="d-flex align-items-center flex-column" style="width: 70%;">
                <label for="username" style="width: 100%;">Username</label>
                <input class="mt-0" type="text" id="username" name="username" placeholder="Enter your username"
                    required>

                <label for="password" style="width: 100%;">Password</label>
                <input class="mt-0" type="password" id="password" name="password" placeholder="Enter your password"
                    required>

                <button type="submit" class="login-btn btn btn-dark pt-1 pb-1 mt-3" style="width: 70%;">Login</button>
            </form>

            <div class="login-link">
                Don't have an account yet? <a href="customer_register.php">Sign Up</a>
            </div>
        </div>
    </body>

</html>
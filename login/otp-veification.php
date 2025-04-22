<?php
session_start();
require '../includes/db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_otp = $_POST['otp'];
    $email = $_SESSION['otp_email'] ?? '';

    if (empty($email)) {
        $error_message = "Session expired. Please register again.";
    } else {
        // Check OTP attempts
        if ($_SESSION['otp_attempts'] >= 3) {
            $error_message = "Too many attempts. Please try again later.";
        } else {
            // Get OTP from database
            $stmt = $pdo->prepare("SELECT otp, otp_expiry FROM account WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if OTP matches and isn't expired
                if ($user_otp === $user['otp'] && strtotime($user['otp_expiry']) > time()) {
                    // Mark email as verified
                    $updateStmt = $pdo->prepare("UPDATE account SET email_verified = 1, otp = NULL, otp_expiry = NULL, last_login = NOW() WHERE email = ?");
                    $updateStmt->execute([$email]);

                    // Auto-login the user
                    $loginStmt = $pdo->prepare("SELECT user_id FROM account WHERE email = ?");
                    $loginStmt->execute([$email]);
                    $user = $loginStmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['role'] = 'customer';
                        unset($_SESSION['otp_email']);
                        unset($_SESSION['otp_attempts']);

                        header("Location: ../User-Pages/welcome.php");
                        exit();
                    }
                } else {
                    $_SESSION['otp_attempts']++;
                    $error_message = "Invalid OTP or OTP has expired.";
                }
            } else {
                $error_message = "User not found.";
            }
        }
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
        <h1>OTP Verification</h1>

        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="d-flex align-items-center flex-column" style="width: 70%;">
            <label for="otp" style="width: 100%;">OTP</label>
            <input class="mt-0" type="text" id="otp" name="otp" placeholder="Enter OTP: XXXXXX" maxlength="6" pattern="\d{6}"
                required autofocus>
            <button type="submit" class="login-btn btn btn-dark pt-1 pb-1 mt-3" style="width: 70%;">Verify</button>
        </form>
        <div class="login-link">
            <p>Didn't receive the OTP? <a href="resend_otp.php">Resend OTP</a></p>
        </div>
    </div>
</body>

</html>
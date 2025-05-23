<?php
session_start();
require '../includes/db.php';
require '../vendor/autoload.php'; // Needed for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate 6-digit OTP
    $otp = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes')); // OTP valid for 15 minutes

    // Insert new user into the database with OTP
    $stmt = $pdo->prepare("INSERT INTO account (name, username, email, phone, password, role, otp, otp_expiry, email_verified) VALUES (?, ?, ?, ?, ?, 'customer', ?, ?, 0)");

    try {
        $stmt->execute([$name, $username, $email, $phone, $hashed_password, $otp, $otp_expiry]);

        // Store OTP in session for verification page
        $_SESSION['otp_email'] = $email;
        $_SESSION['otp_attempts'] = 0; // Track OTP attempts

        // Send verification email with OTP
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bygems001@gmail.com';
        $mail->Password = 'ftwq hjbh bbet tkrx'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@bygems.com', 'ByGems');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification OTP';
        $mail->Body = "
            <h2>ByGems Account Verification</h2>
            <p>Thank you for registering, $name!</p>
            <p>Your verification code is: <strong>$otp</strong></p>
            <p>This code will expire in 15 minutes.</p>
            <p>Enter this code on the verification page to complete your registration.</p>
            <p>If you didn't request this, please ignore this email.</p>
        ";

        if ($mail->send()) {
            $success_message = 'Registration successful! Please check your email for the verification OTP.';
            
            // Redirect to OTP verification page
            header("Location: ../login/otp-veification.php");
            exit();
        } else {
            $error_message = 'Error sending verification email. Please try again.';
        }

    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems | Sign Up</title>
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
            <h1>Sign Up for Bygems</h1>

            <!-- Display Success or Error Messages -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="d-flex align-items-center flex-column" style="width: 70%;">
                <label for="name" style="width: 100%;">Full Name</label>
                <input class="mt-0" type="text" id="name" name="name" placeholder="Full Name" required>

                <label for="username" style="width: 100%;">Username</label>
                <input class="mt-0" type="text" id="username" name="username" placeholder="Username" required>

                <label for="email" style="width: 100%;">Email</label>
                <input class="mt-0" type="email" id="email" name="email" placeholder="Email" required>

                <label for="phone" style="width: 100%;">Phone Number</label>
                <input class="mt-0" type="text" id="phone" name="phone" placeholder="Phone Number" required>

                <label for="password" style="width: 100%;">Password</label>
                <input class="mt-0" type="password" id="password" name="password" placeholder="Password" required>

                <button type="submit" class="signup-btn btn btn-dark pt-1 pb-1 mt-3"
                    style="width: 70%;">Register</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="../login/customer_login.php">Login</a>
            </div>
        </div>
    </body>

</html>
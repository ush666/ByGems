<?php
session_start();
require '../includes/db.php';

$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    $enteredOtp = $_POST['otp'];

    $stmt = $pdo->prepare("SELECT otp, otp_expiry, temp_email FROM account WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        die("User not found.");
    }

    $storedOtp = $user['otp'];
    $expiresAt = strtotime($user['otp_expiry']);
    $now = time();
    $temp_email = $user['temp_email'];

    // Check if the temp_email already exists in another user's email field
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM account WHERE email = ? AND user_id != ?");
    $checkStmt->execute([$temp_email, $userId]);
    $emailExists = $checkStmt->fetchColumn();

    if ($emailExists > 0) {
        header("Location: ../user-dashboard/user-profile.php?error=email-taken");
        exit();
    }

    if ($enteredOtp === $storedOtp && $now <= $expiresAt) {
        $stmt = $pdo->prepare("UPDATE account SET email = temp_email, temp_email = NULL, otp = NULL, otp_expiry = NULL WHERE user_id = ?");
        $stmt->execute([$userId]);

        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Email verified successfully!'];
        header("Location: ../user-dashboard/user-profile.php");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid or expired OTP.'];
        header("Location: verify-email.php");
        exit;
    }
}
<?php
session_start();
require '../includes/db.php'; // Your PDO DB connection file

// Assuming user ID is stored in session
$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    // Gather input
    $newEmail = $_POST['email'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Get current email from DB
    $stmt = $pdo->prepare("SELECT email FROM account WHERE user_id = ?");
    $stmt->execute([$userId]);
    $currentEmail = $stmt->fetchColumn();

    // Handle profile picture upload
    $imgPath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $imgName = basename($_FILES['profile_picture']['name']);
        $imgTmp = $_FILES['profile_picture']['tmp_name'];
        $imgPath = $imgName;

        if (!file_exists('../uploads/profile')) {
            mkdir('../uploads/profile', 0777, true);
        }
        move_uploaded_file($imgTmp, '../uploads/profile/' . $imgPath);
    }

    // If email is changed, verify new email with OTP
    if ($newEmail !== $currentEmail) {
        $otp = rand(100000, 999999);
        $otpExpiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

        // Store temporary new email + OTP in DB (you can add new columns for this)
        $sql = "UPDATE account SET 
                    name = :name,
                    username = :username,
                    birthday = :dob,
                    phone = :phone,
                    address = :address,
                    profile_picture = :profile_picture,
                    temp_email = :temp_email,
                    otp = :otp,
                    otp_expiry = :otp_expiry
                WHERE user_id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':username' => $username,
            ':dob' => $dob,
            ':phone' => $phone,
            ':address' => $address,
            ':profile_picture' => $imgPath ?? null,
            ':temp_email' => $newEmail,
            ':otp' => $otp,
            ':otp_expiry' => $otpExpiry,
            ':id' => $userId
        ]);

        // Send OTP email
        require_once '../includes/send_email.php'; // Add your mail logic here
        sendOTPEmail($newEmail, $otp, $name); // Create this function

        // Redirect to verification page
        header("Location: ../user-dashboard/verify-email.php");
        exit;
    }

    // Email didn't change, proceed with normal update
    $sql = "UPDATE account SET 
                name = :name,
                username = :username,
                birthday = :dob,
                phone = :phone,
                address = :address" .
        ($imgPath ? ", profile_picture = :profile_picture" : "") . "
            WHERE user_id = :id";

    $params = [
        ':name' => $name,
        ':username' => $username,
        ':dob' => $dob,
        ':phone' => $phone,
        ':address' => $address,
        ':id' => $userId
    ];
    if ($imgPath) {
        $params[':profile_picture'] = $imgPath;
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        header("Location: ../user-dashboard/user-profile.php?success=1");
        exit();
    } else {
        echo "Error updating profile.";
    }
}
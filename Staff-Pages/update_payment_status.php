<?php
require 'db.php'; // Database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Update overdue payments (15-30 days old)
$sql = "UPDATE payments 
        SET status = 'Overdue' 
        WHERE status = 'Pending' 
        AND DATEDIFF(NOW(), payment_due_date) BETWEEN 15 AND 30";

$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch affected users for notifications
$sql = "SELECT customer_id, email, phone FROM payments 
        WHERE status = 'Overdue' AND DATEDIFF(NOW(), payment_due_date) BETWEEN 15 AND 30";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    sendEmailNotification($user['email']);
    sendSMSNotification($user['phone']);
}

echo "Payment status updated and notifications sent.";

function sendEmailNotification($email) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ByGems001@gmail.com'; // Replace with your email
        $mail->Password = 'softwareengineering01'; // Use an app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ByGems001@gmail.com', 'ByGems'); 
        $mail->addAddress($email);
        $mail->Subject = 'Payment Overdue Notice';
        $mail->Body = 'Your payment is overdue. Please settle it immediately.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function sendSMSNotification($phone) {
    $semaphore_api_key = "cc7570915f6301cc8d4adad7b1603b21"; // Replace with your actual API key
    $sender_name = "ByGems"; // Your registered sender name in Semaphore
    $message = "Your ByGems payment is overdue. Please settle it now.";

    $url = "https://semaphore.co/api/v4/messages";
    
    $data = [
        'apikey' => $semaphore_api_key,
        'number' => $phone,
        'message' => $message,
        'sendername' => $sender_name
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>

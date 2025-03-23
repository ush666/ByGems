<?php
require 'includes/db.php'; 
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO account (name, username, password, email, phone, role) 
                           VALUES (:name, :username, :password, :email, :phone, 'customer')");
    $stmt->execute([
        ':name'     => 'Test User',
        ':username' => 'testuser1',
        ':password' => password_hash('testuser', PASSWORD_BCRYPT),
        ':email'    => 'chumabdulla55@gmail.com',
        ':phone'    => '09955138471'
    ]);
    $user_id = $pdo->lastInsertId();


    $stmt = $pdo->prepare("INSERT INTO event_request (user_id, order_id, celebrant_name, event_location, event_date, payment_status, request_status) 
                           VALUES (:user_id, :order_id, :celebrant_name, :event_location, NOW() + INTERVAL 7 DAY, 'Pending', 'Pending')");
    $stmt->execute([
        ':user_id'         => $user_id,
        ':order_id'        => uniqid(),
        ':celebrant_name'  => 'Test Celebrant',
        ':event_location'  => 'Test Venue'
    ]);
    $event_id = $pdo->lastInsertId(); 


    $stmt = $pdo->prepare("INSERT INTO payment (event_id, amount_paid, payment_status, payment_type) 
                           VALUES (:event_id, :amount_paid, 'Pending', 'Cash')");
    $stmt->execute([
        ':event_id'   => $event_id,
        ':amount_paid'=> 5000.00
    ]);

    $pdo->commit();


    sendPaymentNotification('09955138471', "Hello, your payment for Event #$event_id is pending. Please settle it soon. - ByGems");

    echo "Test user, event, and payment inserted successfully! SMS sent.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}


function sendPaymentNotification($phone, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chumabdulla44@gmail.com';
        $mail->Password   = 'bmpuwxeymyrelxpq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $carrier_gateway = "@txt.globe.com.ph";  
        $to = $phone . $carrier_gateway;


        $mail->setFrom('your-email@gmail.com', 'ByGems'); 
        $mail->addAddress($to);
        $mail->Subject = "ByGems Payment Reminder";
        $mail->Body    = $message;


        if ($mail->send()) {
            echo "SMS sent successfully!";
        } else {
            echo "Failed to send SMS.";
        }
    } catch (Exception $e) {
        echo "Mail error: " . $mail->ErrorInfo;
    }
}
?>

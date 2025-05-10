<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendOTPEmail($to, $otp, $name)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bygems001@gmail.com';
        $mail->Password = 'ftwq hjbh bbet tkrx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('your_gmail@gmail.com', 'BYGems');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify your new email';
        $mail->Body = "
            <h2>ByGems Account Verification</h2>
            <p>Hello, $name!</p>
            <p>Your verification code is: <strong>$otp</strong></p>
            <p>This code will expire in 10 minutes.</p>
            <p>Enter this code on the verification page to complete your registration.</p>
            <p>If you didn't request this, please ignore this email.</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
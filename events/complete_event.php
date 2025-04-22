<?php
session_start();
require_once '../includes/db.php';
require '../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['event_id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../Staff-Pages/event_management.php");
    exit();
}

$event_id = $_POST['event_id'];

try {
    // Fetch user info
    $stmtUser = $pdo->prepare("
        SELECT a.email, a.name, e.celebrant_name, e.event_date
        FROM event_request e
        JOIN account a ON e.user_id = a.user_id
        WHERE e.event_id = ?
    ");
    $stmtUser->execute([$event_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update event request_status to 'completed'
        $stmt = $pdo->prepare("UPDATE event_request SET request_status = 'completed' WHERE event_id = ?");
        if ($stmt->execute([$event_id])) {
            // Send email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bygems001@gmail.com'; // your email
            $mail->Password = 'ftwq hjbh bbet tkrx'; // your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@bygems.com', 'ByGems Events');
            $mail->addAddress($user['email'], $user['name']);

            $mail->isHTML(true);
            $mail->Subject = 'Your Event Payment Has Been Completed';
            $mail->Body = "
                <p>Dear {$user['name']},</p>
                <p>We are pleased to inform you that the payment for your event request for <strong>{$user['celebrant_name']}</strong> on <strong>{$user['event_date']}</strong> has been completed.</p>
                <p>If you have any questions or need further assistance, feel free to reach out to us.</p>
                <br>
                <p>Best regards,<br>ByGems Events Team</p>
            ";

            $mail->send();
            $_SESSION['success'] = "Event status updated to completed and email sent successfully!";
        } else {
            $_SESSION['error'] = "Failed to update event status.";
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Mailer Error: {$mail->ErrorInfo}";
}

header("Location: ../Staff-Pages/event_management.php?message=completed");
exit();
?>

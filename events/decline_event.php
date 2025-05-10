<?php
session_start();
require_once '../includes/db.php';
require '../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['event_id']) || !isset($_POST['reason'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../Staff-Pages/event_management.php");
    exit();
}

$event_id = $_POST['event_id'];
$reason = $_POST['reason'];

try {
    // Fetch user and event info
    $stmtUser = $pdo->prepare("
        SELECT a.user_id, a.email, a.name, e.celebrant_name, e.event_date, e.order_id
        FROM event_request e
        JOIN account a ON e.user_id = a.user_id
        WHERE e.event_id = ?
    ");
    $stmtUser->execute([$event_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update event request_status
        $stmt = $pdo->prepare("UPDATE event_request SET request_status = 'declined', decline_reason = ? WHERE event_id = ?");
        if ($stmt->execute([$reason, $event_id])) {

            // Insert into notification table (NOW INCLUDING event_id)
            $notificationMessage = "Your event request for {$user['celebrant_name']} on {$user['event_date']} has been declined.";
            $notificationLink = "../User-Pages/invoice.php?order_id={$user['order_id']}"; // Example link, adjust based on your pages
            $stmtNotif = $pdo->prepare("
                INSERT INTO notifications (recipient_id, message, link, event_id, is_read, created_at)
                VALUES (?, ?, ?, ?, 0, NOW())
            ");
            $stmtNotif->execute([$user['user_id'], $notificationMessage, $notificationLink, $event_id]);

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
            $mail->Subject = 'Your Event Request has been Declined';
            $mail->Body = "
                <p>Dear {$user['name']},</p>
                <p>We regret to inform you that your event request for <strong>{$user['celebrant_name']}</strong> on <strong>{$user['event_date']}</strong> has been declined.</p>
                <p><strong>Reason:</strong> {$reason}</p>
                <p>If you have any questions or need further assistance, feel free to reach out to us.</p>
                <br>
                <p>Best regards,<br>ByGems Events Team</p>
            ";

            $mail->send();
            $_SESSION['success'] = "Event declined, notification saved, and email sent successfully!";
        } else {
            $_SESSION['error'] = "Failed to decline event.";
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: {$e->getMessage()}";
}

header("Location: ../Staff-Pages/event_management.php?declined=success");
exit();
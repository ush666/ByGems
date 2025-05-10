<?php
session_start();
require_once '../includes/db.php';
require '../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid request. Event ID is missing.";
    header("Location: ../Staff-Pages/event_management.php?message=invalid");
    exit();
}

$event_id = intval($_GET['id']); // get id from URL

try {
    // Fetch user and event info
    $stmtUser = $pdo->prepare("
        SELECT a.user_id, a.email, a.name, e.celebrant_name, e.event_type, e.event_date, e.order_id
        FROM event_request e
        JOIN account a ON e.user_id = a.user_id
        WHERE e.event_id = ?
    ");
    $stmtUser->execute([$event_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update event request_status
        $stmt = $pdo->prepare("UPDATE event_request SET request_status = 'approved' WHERE event_id = ?");
        if ($stmt->execute([$event_id])) {

            // Insert into notification table
            $notificationMessage = "Your event request for {$user['celebrant_name']} ({$user['event_type']}) on {$user['event_date']} has been approved!";
            $notificationLink = "../User-Pages/invoice.php?order_id={$user['order_id']}"; // Example link, adjust if needed
            $stmtNotif = $pdo->prepare("
                INSERT INTO notifications (recipient_id, message, link, event_id, is_read, created_at)
                VALUES (?, ?, ?, ?, 0, NOW())
            ");
            $stmtNotif->execute([$user['user_id'], $notificationMessage, $notificationLink, $event_id]);

            // Send approval email
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
            $mail->Subject = 'Your Event Request has been Approved!';
            $mail->Body = "
                <p>Dear {$user['name']},</p>
                <p>We are thrilled to inform you that your event request for <strong>{$user['celebrant_name']}</strong> ({$user['event_type']}) on <strong>" . date('F j, Y', strtotime($user['event_date'])) . "</strong> has been <strong>approved</strong>!</p>
                <p>Our team is excited to be part of your special day.</p>
                <p>If you have any further questions or updates, feel free to contact us anytime.</p>
                <br>
                <p>Best regards,<br>ByGems Events Team</p>
            ";

            $mail->send();
            $_SESSION['success'] = "Event approved, notification saved, and email sent successfully!";
        } else {
            $_SESSION['error'] = "Failed to approve event.";
        }
    } else {
        $_SESSION['error'] = "User not found for the given event ID.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header("Location: ../Staff-Pages/event_management.php?approved=success");
exit();
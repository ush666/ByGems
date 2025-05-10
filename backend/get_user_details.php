<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                user_id, name, username, email, phone, address, gender, 
                profile_picture, role, last_login, email_verified, 
                created_at, updated_at, otp, otp_expiry
            FROM account 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Don't return sensitive data like password and OTP in production
            unset($user['password']);
            unset($user['otp']);
            unset($user['otp_expiry']);
            
            echo json_encode([
                'success' => true,
                'data' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
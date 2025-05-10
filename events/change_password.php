<?php
session_start();
require '../includes/db.php';

$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'New passwords do not match.'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT password FROM account WHERE user_id = ?");
    $stmt->execute([$userId]);
    $hashedPassword = $stmt->fetchColumn();

    if (!$hashedPassword || !password_verify($currentPassword, $hashedPassword)) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Current password is incorrect.'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE account SET password = ? WHERE user_id = ?");
    if ($stmt->execute([$newHashedPassword, $userId])) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Password changed successfully.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update password.'];
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
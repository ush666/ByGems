<?php
session_start();
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the basic user query
    $stmt = $pdo->prepare("SELECT user_id, role, username, password FROM account WHERE username = ? AND role IN ('staff', 'admin')");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Check if last_login column exists before trying to update it
        try {
            // First check if the column exists in the table
            $checkColumn = $pdo->query("SHOW COLUMNS FROM account LIKE 'last_login'");
            if ($checkColumn->rowCount() > 0) {
                // Column exists, update the last login time
                $updateStmt = $pdo->prepare("UPDATE account SET last_login = NOW() WHERE user_id = ?");
                $updateStmt->execute([$user['user_id']]);
            }
        } catch (PDOException $e) {
            // Silently fail if there's an error updating last_login
            error_log("Error updating last login: " . $e->getMessage());
        }

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: cms_dashboard.php");
        } else {
            header("Location: cms_dashboard.php");
        }
        exit();
    } else {
        // Generic error message to avoid revealing which was wrong (username or password)
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }
}
?>
<form method="POST">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
</form>


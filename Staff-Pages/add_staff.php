<?php
session_start();
require '../includes/db.php';

// Ensure only admins can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: cms_dashboard.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'staff' or 'admin'

    // Insert new staff/admin into the account table
    $stmt = $pdo->prepare("INSERT INTO account (name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$name, $username, $email, $phone, $password, $role]);
        echo "New $role added successfully! <a href='cms_dashboard.php'>Back to Dashboard</a>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<h2>Add New Staff/Admin</h2>
<form method="POST">
    <input type="text" name="name" required placeholder="Full Name">
    <input type="text" name="username" required placeholder="Username">
    <input type="email" name="email" required placeholder="Email">
    <input type="text" name="phone" required placeholder="Phone Number">
    <input type="password" name="password" required placeholder="Password">
    <select name="role" required>
        <option value="staff">Staff</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Add User</button>
</form>

<a href="cms_dashboard.php">Back to Dashboard</a>

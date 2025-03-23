<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: customer_login.php");
    exit();
}
echo "Welcome to your dashboard, Customer!";
?>
<a href="../includes/logout.php">Logout</a>

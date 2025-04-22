<?php
session_start();

$redirect_page = "../login/customer_login.php"; // Default for customers

if (isset($_SESSION['role']) && ($_SESSION['role'] === 'staff' || $_SESSION['role'] === 'admin')) {
    $redirect_page = "../login/customer_login.php"; // Redirect staff and admin to staff login
}

// Destroy session
session_unset();
session_destroy();

// Redirect to the correct login page
header("Location: $redirect_page");
exit();

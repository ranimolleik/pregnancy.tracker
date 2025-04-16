<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a mother
if (!isset($_SESSION['mother_id'])) {
    header("Location: login.php");
    exit();
}

// Set session role if not already set
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'mother';
}
?> 
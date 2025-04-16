<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a midwife
if (!isset($_SESSION['medical_staff_id']) || $_SESSION['role'] !== 'midwife') {
    header("Location: login.php");
    exit();
}

// Set session role if not already set
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'midwife';
}
?> 
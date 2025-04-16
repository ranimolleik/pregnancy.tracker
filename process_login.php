<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once("classes/Database.php");

// Debug output
error_log("POST Data: " . print_r($_POST, true));
error_log("Session Data: " . print_r($_SESSION, true));

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    error_log("Attempting login for email: " . $email);

    try {
        $db = new Database();
        
        // Check mothers table
        $query = "SELECT * FROM mothers WHERE email = ? AND is_verified = 1";
        $result = $db->read($query, [$email]);
        
        if ($result && !empty($result)) {
            if (password_verify($password, $result[0]['password'])) {
                $_SESSION['user_id'] = $result[0]['id'];
                $_SESSION['user_type'] = 'mother';
                $_SESSION['first_name'] = $result[0]['first_name'];
                error_log("Mother login successful");
                header("Location: dashboard.php");
                exit();
            }
        }
        
        // Check medical_staff table
        $query = "SELECT * FROM medical_staff WHERE email = ? AND verified = 1";
        $result = $db->read($query, [$email]);
        
        if ($result && !empty($result)) {
            if (password_verify($password, $result[0]['password'])) {
                $_SESSION['user_id'] = $result[0]['id'];
                $_SESSION['user_type'] = 'medical_staff';
                $_SESSION['first_name'] = $result[0]['first_name'];
                error_log("Medical staff login successful");
                header("Location: midwife_dashboard.php");
                exit();
            }
        }
        
        // Check if user exists but is not verified
        $query = "SELECT * FROM mothers WHERE email = ? AND is_verified = 0";
        $result = $db->read($query, [$email]);
        if ($result && !empty($result)) {
            $error = "Your account is pending verification. Please check your email for verification instructions.";
        } else {
            $query = "SELECT * FROM medical_staff WHERE email = ? AND verified = 0";
            $result = $db->read($query, [$email]);
            if ($result && !empty($result)) {
                $error = "Your account is pending verification. Please contact the administrator.";
            } else {
                $error = "Invalid email or password";
            }
        }
        
        error_log("Login failed: " . $error);
        header("Location: login.php?error=" . urlencode($error));
        exit();
        
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: login.php?error=" . urlencode("An error occurred. Please try again later."));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
} 
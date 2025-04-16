<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once("classes/Database.php");

// Debugging: Log session variables
error_log("Session Variables: " . print_r($_SESSION, true));

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $db = new Database();
    
    // Check if user is a verified mother
    $query = "SELECT * FROM mothers WHERE email = ? AND is_verified = 1";
    $result = $db->read($query, [$email]);
    
    if ($result && password_verify($password, $result[0]['password'])) {
        $_SESSION['mother_id'] = $result[0]['id'];
        $_SESSION['first_name'] = $result[0]['first_name'];
        $_SESSION['role'] = 'mother';
        header("Location: dashboard.php");
        exit();
    }
    
    // Check if user is a verified medical staff
    $query = "SELECT * FROM medical_staff WHERE email = ? AND verified = 1";
    $result = $db->read($query, [$email]);
    
    if ($result && password_verify($password, $result[0]['password'])) {
        $_SESSION['medical_staff_id'] = $result[0]['id'];
        $_SESSION['first_name'] = $result[0]['first_name'];
        $_SESSION['role'] = 'midwife';
        header("Location: midwife_dashboard.php");
        exit();
    }
    
    // Check if user exists but is not verified
    $query = "SELECT * FROM mothers WHERE email = ? AND is_verified = 0";
    $result = $db->read($query, [$email]);
    if ($result) {
        $error = "Your account is pending verification. Please check your email for verification instructions.";
    } else {
        $query = "SELECT * FROM medical_staff WHERE email = ? AND verified = 0";
        $result = $db->read($query, [$email]);
        if ($result) {
            $error = "Your account is pending verification. Please contact the administrator.";
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pregnancy Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .login-button {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-button:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
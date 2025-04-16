<?php
session_start();
include("classes/Database.php");
include("classes/Invitation.php");

$invitation = new Invitation();
$error = '';
$success = '';

// Check if token is provided and valid
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    if (!$invitation->validateToken($token)) {
        $error = "Invalid or expired invitation token. Please contact the administrator for a new invitation.";
    }
} else {
    $error = "No invitation token provided. Please contact the administrator for an invitation.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Validate token again
    if (!isset($_POST['token']) || !$invitation->validateToken($_POST['token'])) {
        $error = "Invalid or expired invitation token.";
    } else {
        // Get form data
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        $description = trim($_POST['description']);

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($description)) {
            $error = "All fields are required.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long.";
        } else {
            // Check if email already exists
            $db = new Database();
            $checkEmail = $db->read("SELECT id FROM medical_staff WHERE email = ?", [$email]);
            
            if (!empty($checkEmail)) {
                $error = "Email already registered.";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new midwife
                $query = "INSERT INTO medical_staff (first_name, last_name, email, password, role, description, verified) 
                         VALUES (?, ?, ?, ?, 'midwife', ?, 1)";
                
                if ($db->save($query, [$first_name, $last_name, $email, $hashedPassword, $description])) {
                    // Mark token as used
                    $invitation->markTokenAsUsed($_POST['token']);
                    $success = "Registration successful! You can now login with your credentials.";
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midwife Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff5f7;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #e91e63;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ffcdd2;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        textarea:focus {
            border-color: #e91e63;
            outline: none;
        }
        textarea {
            height: 120px;
            resize: vertical;
        }
        .btn {
            background-color: #e91e63;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #c2185b;
        }
        .message {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            text-align: center;
        }
        .error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .info-box {
            background-color: #fce4ec;
            border-left: 6px solid #e91e63;
            padding: 20px;
            margin-bottom: 25px;
            color: #555;
        }
        .info-box p {
            margin: 0;
            line-height: 1.5;
        }
        a {
            color: #e91e63;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Midwife Registration</h1>
        
        <?php if (!empty($error)): ?>
            <div class="message error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="message success">
                <?php echo $success; ?>
                <p><a href="login.php">Click here to login</a></p>
            </div>
        <?php endif; ?>

        <?php if (empty($success) && isset($token) && $invitation->validateToken($token)): ?>
            <div class="info-box">
                <p>Please complete the registration form below to create your midwife account.</p>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="description">Professional Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <button type="submit" name="register" class="btn">Register</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
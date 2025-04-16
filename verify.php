<?php
session_start(); // Start the session
include("classes/Database.php");

// Initialize message variables
$message = '';
$messageType = '';

if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];
    
    $db = new Database();
    
    // Check if the verification code exists and is not expired
    $query = "SELECT * FROM verification_tokens WHERE token = '$verification_code' AND expires_at > NOW()";
    $result = $db->read($query);
    
    if (!empty($result)) {
        // Get the mother_id from the token
        $mother_id = $result[0]['mother_id'];

        // Update user status to verified
        $updateQuery = "UPDATE mothers SET is_verified = 1 WHERE id = '$mother_id'";
        $db->save($updateQuery);
        
        // Delete the verification token after successful verification
        $deleteQuery = "DELETE FROM verification_tokens WHERE token = '$verification_code'";
        $db->save($deleteQuery);
        
        // Set a success message
        $message = "✅ Email verified successfully! You can now log in.";
        $messageType = "success"; // You can use this to style the message
    } else {
        $message = "❌ Invalid or expired verification token. <a href='resend_verification.php'>Click here</a> to request a new verification email.";
        $messageType = "error"; // You can use this to style the message
    }
} else {
    $message = "❌ No verification code provided.";
    $messageType = "error"; // You can use this to style the message
}

// Display the message at the top of the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            text-align: center;
            padding: 50px;
        }
        .message {
            color: red; /* Set text color to red */
            margin-bottom: 20px; /* Add some space below the message */
        }
        .success {
            color: green; /* Optional: Different color for success messages */
        }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <a href="login.php">Go to Login Page</a>
</body>
</html>
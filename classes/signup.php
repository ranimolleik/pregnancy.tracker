<?php

// Only start session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("Database.php"); // Include database connection

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

class Signup {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Initialize the database
    }

    // Function to calculate pregnancy week based on the pregnancy start date
    private function calculatePregnancyWeek($pregnancy_start_date) {
        $start_date = new DateTime($pregnancy_start_date);
        $current_date = new DateTime(); // Today's date
        $interval = $start_date->diff($current_date);
        
        // Calculate the number of weeks
        $weeks = floor($interval->days / 7);
        return $weeks;
    }

    // Validate that the pregnancy start date is not in the future or more than 1 year ago
    private function validatePregnancyStartDate($pregnancy_start) {
        $current_date = new DateTime();
        $start_date = new DateTime($pregnancy_start);
        
        // Check if the date is in the future
        if ($start_date > $current_date) {
            $_SESSION['error'] = "Pregnancy start date cannot be in the future.";
            return false;
        }
        
        // Check if the date is more than 1 year ago
        $one_year_ago = (clone $current_date)->modify('-1 year'); // Clone to avoid modifying the original
        if ($start_date < $one_year_ago) {
            $_SESSION['error'] = "Pregnancy start date cannot be more than 1 year ago.";
            return false;
        }
        
        return true;
    }

    // Check if the name is already taken
    private function checkNameTaken($first_name, $last_name) {
        $checkName = "SELECT * FROM mothers WHERE first_name = '$first_name' AND last_name = '$last_name'";
        $existingUser  = $this->db->read($checkName);
        
        if (!empty($existingUser )) {
            $_SESSION['error'] = "This name is already registered.";
            return true; // Name exists, return true
        }
        
        return false; // Name is available
    }

    public function registerUser ($data) {
        // Trim inputs
        $first_name = trim($data['first_name']);
        $last_name = trim($data['last_name']);
        $email = trim($data['email']);
        $password = trim($data['password']);
        $pregnancy_start = trim($data['pregnancy_start']);
    
        // Validate first and last name (only letters allowed)
        if (!preg_match("/^[a-zA-Z]+$/", $first_name)) {
            $_SESSION['error'] = "First name can only contain letters.";
            return false;
        }
        if (!preg_match("/^[a-zA-Z]+$/", $last_name)) {
            $_SESSION['error'] = "Last name can only contain letters.";
            return false;
        }
    
        // Check if name already exists
        if ($this->checkNameTaken($first_name, $last_name)) {
            return false; // Name is already taken
        }
    
        // Validate password (minimum 8 characters, at least one number and one letter)
        if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
            $_SESSION['error'] = "Password must be at least 8 characters long and contain at least one letter and one number.";
            return false;
        }
    
        // Check if email already exists
        $checkEmail = "SELECT * FROM mothers WHERE email = '$email'";
        $existingUser  = $this->db->read($checkEmail);
        if (!empty($existingUser )) {
            $_SESSION['error'] = "This email is already registered.";
            return false;
        }
    
        // Validate pregnancy start date
        if (!$this->validatePregnancyStartDate($pregnancy_start)) {
            return false;
        }
    
        // Calculate pregnancy week
        $pregnancy_week = $this->calculatePregnancyWeek($pregnancy_start);
    
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Generate a unique verification code
 $verification_code = bin2hex(random_bytes(16));
    
        // Handle pregnancy problems (store as JSON)
        $problems = isset($data['problems']) ? $data['problems'] : [];
    
        // Check if "Other" is selected and add the specified value
        if (isset($data['other_problems']) && !empty(trim($data['other_problems']))) {
            $problems[] = trim($data['other_problems']); // Add the "Other" input to the problems array
        }
    
        // Convert problems array to JSON
        $problems_json = json_encode($problems);
    
        // Insert into database
        $query = "INSERT INTO mothers (first_name, last_name, email, password, pregnancy_start, pregnancy_week, complications, verification_code,is_verified)
                  VALUES ('$first_name', '$last_name', '$email', '$hashedPassword', '$pregnancy_start', '$pregnancy_week', '$problems_json', '$verification_code' , 0)";
    
    $mother_id = $this->db->save($query); 
    
    if ($mother_id) { // Check if the insert was successful
        // Generate a unique verification token
        $verification_token = bin2hex(random_bytes(16)); // This is the token stored in verification_tokens table

        // Insert the verification token into the verification_tokens table
        $insertTokenQuery = "INSERT INTO verification_tokens (mother_id, token, created_at, expires_at) 
                             VALUES ('$mother_id', '$verification_token', NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))";
                             error_log("Insert Token Query: " . $insertTokenQuery); // Log the query
        $tokenInsertResult = $this->db->save($insertTokenQuery); // Ensure this line is executed

        // Check if the token was inserted successfully
        if (!$tokenInsertResult) {
            // Log or handle the error
            error_log("Failed to insert verification token for mother_id: $mother_id");
        }

        // Send verification email with the verification token
        $this->sendVerificationEmail($email, $verification_token);
        $_SESSION['success'] = "Signup successful! Please verify your email."; // Success message
        return true;
        } else {
            $_SESSION['error'] = "Error saving data."; // Error message
            return false;
        }
}
            
    private function sendVerificationEmail($email, $verification_code) {
        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
    
        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Disable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'kickslittle9@gmail.com';      // Your Gmail address
            $mail->Password   = 'hsroqbekqjxurwob';                   // Use the App Password here
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
            $mail->Port       = 587;                                    // TCP port to connect to
    
            // Recipients
            $mail->setFrom('kickslittle9@gmail.com', 'little kicks'); // Use your email here
            $mail->addAddress($email);                                  // Add a recipient
    
            // Content
            $mail->isHTML(true);                                       // Set email format to HTML
            $mail->Subject = 'Verify Your Email - Pregnancy Tracker';
            $verification_link = "http://localhost/pregnancy_tracker/verify.php?code=$verification_code";
            $mail->Body    = "Hello,<br><br>Please click the link below to verify your email:<br><a href='$verification_link'>$verification_link</a><br><br>Thank you!";
            $mail->AltBody = "Hello,\n\nPlease click the link below to verify your email:\n$verification_link\n\nThank you!";
    
            // Send the email
            $mail->send();
        } catch (Exception $e) {
            $_SESSION['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $signup = new Signup();
    $success = $signup->registerUser ($_POST);
    

    if ($success) {
        header("Location: ../login.php"); // Redirect after signup
        exit();
    } else {
        // Preserve form data on error
        $_SESSION['form_data'] = $_POST;
        header("Location: ../index.php"); // Redirect back to signup page to display error
        exit();
    }
}


?> 
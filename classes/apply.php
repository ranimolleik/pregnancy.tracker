<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("Database.php"); // Include your database connection
include("Invitation.php"); // Include the Invitation class

class Apply {
    private $db;
    private $invitation;

    public function __construct() {
        $this->db = new Database(); // Initialize the database
        $this->invitation = new Invitation();
    }

    public function submitApplication($data) {
        // Validate invitation token
        if (!isset($data['invitation_token']) || !$this->invitation->validateToken($data['invitation_token'])) {
            $_SESSION['error'] = "Invalid or expired invitation token.";
            return false;
        }

        // Check if a midwife already exists
        $checkQuery = "SELECT COUNT(*) as count FROM medical_staff WHERE role = 'midwife'";
        $result = $this->db->read($checkQuery);
        if ($result[0]['count'] > 0) {
            $_SESSION['error'] = "A midwife is already registered in the system.";
            return false;
        }

        // Validate inputs
        $first_name = trim($data['first_name']);
        $last_name = trim($data['last_name']);
        $email = trim($data['email']);
        $password = trim($data['password']);
        $description = trim($data['description']);

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($description)) {
            $_SESSION['error'] = "All fields are required.";
            return false;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
            return false;
        }

        // Validate password strength
        if (!$this->isPasswordStrong($password)) {
            $_SESSION['error'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
            return false;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Start transaction
        $this->db->beginTransaction();

        try {
            // Insert midwife
            $query = "INSERT INTO medical_staff (first_name, last_name, email, password, description, role, verified) 
                     VALUES (?, ?, ?, ?, ?, 'midwife', 1)";
            
            if (!$this->db->save($query, [$first_name, $last_name, $email, $hashedPassword, $description])) {
                throw new Exception("Failed to register midwife.");
            }

            // Mark invitation token as used
            if (!$this->invitation->markTokenAsUsed($data['invitation_token'])) {
                throw new Exception("Failed to mark invitation token as used.");
            }

            $this->db->commit();
            $_SESSION['success'] = "Registration successful! You can now log in.";
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
            return false;
        }
    }

    private function isPasswordStrong($password) {
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[^A-Za-z0-9]/', $password);
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apply = new Apply();
    $success = $apply->submitApplication($_POST);

    if ($success) {
        header("Location: ../login.php"); // Redirect after successful application
        exit();
    } else {
        // Preserve form data on error
        $_SESSION['form_data'] = $_POST;
        header("Location: ../apply.php"); // Redirect back to the application page to display error
        exit();
    }
}
?>
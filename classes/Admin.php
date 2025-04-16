<?php
include("Database.php");
include("Invitation.php");
class Admin {
    private $db;
    private $invitation;

    public function __construct() {
        $this->db = new Database();
        $this->invitation = new Invitation();
    }

    public function getMidwifeInfo() {
        $query = "SELECT * FROM medical_staff WHERE role = 'midwife' LIMIT 1";
        return $this->db->read($query);
    }

    public function generateInvitationToken() {
        return $this->invitation->generateInvitationToken();
    }

    public function validateAdmin($email, $password) {
        $query = "SELECT * FROM admin WHERE email = ? LIMIT 1";
        $result = $this->db->read($query, [$email]);

        if (!empty($result) && password_verify($password, $result[0]['password'])) {
            return $result[0];
        }
        return false;
    }

    public function createAdmin($data) {
        // Check if admin already exists
        $checkQuery = "SELECT COUNT(*) as count FROM admin";
        $result = $this->db->read($checkQuery);
        
        if ($result[0]['count'] > 0) {
            return false; // Only one admin allowed
        }

        // Validate inputs
        $first_name = trim($data['first_name']);
        $last_name = trim($data['last_name']);
        $email = trim($data['email']);
        $password = trim($data['password']);

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            return false;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert admin
        $query = "INSERT INTO admin (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        return $this->db->save($query, [$first_name, $last_name, $email, $hashedPassword]);
    }

    public function updateAdminPassword($admin_id, $new_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE admin SET password = ? WHERE id = ?";
        return $this->db->save($query, [$hashedPassword, $admin_id]);
    }
}
?> 
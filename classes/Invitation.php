<?php
class Invitation {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function generateInvitationToken() {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        
        // Set expiration to 24 hours from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Insert the token into the database
        $query = "INSERT INTO invitation_tokens (token, expires_at) VALUES (?, ?)";
        if ($this->db->save($query, [$token, $expires_at])) {
            return $token;
        }
        return false;
    }

    public function validateToken($token) {
        // Check if token exists and is not expired or used
        $query = "SELECT * FROM invitation_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()";
        $result = $this->db->read($query, [$token]);
        
        return !empty($result);
    }

    public function markTokenAsUsed($token) {
        $query = "UPDATE invitation_tokens SET used = 1 WHERE token = ?";
        return $this->db->save($query, [$token]);
    }

    public function cleanupExpiredTokens() {
        // Delete tokens that are either expired or used
        $query = "DELETE FROM invitation_tokens WHERE expires_at < NOW() OR used = 1";
        return $this->db->save($query);
    }
}
?> 
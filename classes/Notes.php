<?php
include("Database.php");

class Notes {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Initialize the database
    }

    // Function to add a note
    public function addNote($mother_id, $content) {
        $query = "INSERT INTO notes (mother_id, content) VALUES (?, ?)";
        return $this->db->save($query, [$mother_id, $content]);
    }

    // Function to get notes for a specific mother
    public function getNotes($mother_id) {
        $query = "SELECT * FROM notes WHERE mother_id = ?";
        return $this->db->read($query, [$mother_id]);
    }
    public function deleteNote($noteId) {
        // Assuming you have a database connection method
        $query = "DELETE FROM notes WHERE id = ?";
        $db = new Database(); // Create a new Database instance
        return $db->delete($query, [$noteId]); // Execute the delete query
    }
}
?>
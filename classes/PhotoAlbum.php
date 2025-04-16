<?php
require_once("Database.php");
class PhotoAlbum {
    private $db;

    public function __construct() {
        $this->db = new Database(); // Initialize the database
    }

    public function uploadPhoto($mother_id, $image) {
        $query = "INSERT INTO photo_album (mother_id, image) VALUES (?, ?)";
        $result = $this->db->save($query, [$mother_id, $image]);
    
        if ($result === false) {
            error_log("Database insert failed for mother_id: $mother_id, image: $image");
            return false;
        }
        return true;
    }
    

    // Function to get photos for a specific mother
    public function getPhotos($mother_id) {
        $query = "SELECT * FROM photo_album WHERE mother_id = ?";
        $result = $this->db->read($query, [$mother_id]);
        return $result ? $result : []; // Return an empty array if no photos are found
    }

    public function deletePhoto($photo_id, $mother_id) {
        $query = "DELETE FROM photo_album WHERE id = ? AND mother_id = ?";
        return $this->db->delete($query, [$photo_id, $mother_id]); // Implement execute method to run non-select queries
    }

    public function getPhotoById($photo_id, $mother_id) {
        // Prepare the SQL query to fetch the photo details
        $query = "SELECT * FROM photo_album WHERE id = ? AND mother_id = ?";
        
        // Execute the query and return the result
        return $this->db->read($query, [$photo_id, $mother_id]); // Assuming read() returns a single record
    }
}
?>
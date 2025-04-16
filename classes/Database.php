<?php
class Database {
    private $host = "localhost";
    private $db_name = "pregnancy_tracker";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }

    // Read data from database
    public function read($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Read Error: " . $e->getMessage();
            return false;
        }
    }

    // Save data to database
    public function save($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            
            // For UPDATE and DELETE operations, check if the operation was successful
            if (stripos($query, 'UPDATE') === 0 || stripos($query, 'DELETE') === 0) {
                return true; // Return true if the query executed without errors
            }
            
            // For INSERT operations, check if rows were affected
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    // Update data in database
    public function update($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            echo "Update Error: " . $e->getMessage();
            return false;
        }
    }

    // Delete data from database
    public function delete($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            echo "Delete Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
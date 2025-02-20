<?php
class Database {
    // Database configuration
    private $host = 'localhost';     // Database host
    private $username = 'root';      // Database username
    private $password = '';          // Database password
    private $database = 'ppdb_sd';   // Database name from SQL dump

    // Database connection
    private $conn;

    // Constructor to establish database connection
    public function __construct() {
        try {
            // Create PDO connection
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4", 
                $this->username, 
                $this->password
            );
            
            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Enable detailed error reporting for development
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            // Log or display connection error
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to get database connection
    public function getConnection() {
        return $this->conn;
    }

    // Method to close database connection
    public function closeConnection() {
        $this->conn = null;
    }

    // Utility method for prepared statements
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    // Helper method to begin transaction
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    // Helper method to commit transaction
    public function commit() {
        return $this->conn->commit();
    }

    // Helper method to rollback transaction
    public function rollBack() {
        return $this->conn->rollBack();
    }
}
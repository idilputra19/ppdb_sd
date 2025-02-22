<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Authentication Methods
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function register($data) {
        $query = "INSERT INTO " . $this->table . " (username, email, password, role, is_active) 
                  VALUES (?, ?, ?, ?, 1)";
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['email'], // username same as email
                $data['email'],
                $hashedPassword,
                'siswa' // default role
            ]);
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Admin Management Methods
    public function getAllAdmin() {
        $query = "SELECT * FROM " . $this->table . " WHERE role = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? AND role = 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createAdmin($data) {
        $query = "INSERT INTO " . $this->table . " 
                (username, email, password, role, is_active) 
                VALUES (?, ?, ?, 'admin', 1)";
        
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['username'],
                $data['email'],
                $hashedPassword
            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function updateAdmin($id, $data) {
        try {
            $this->conn->beginTransaction();

            // Update basic info
            $query = "UPDATE " . $this->table . " 
                    SET username = ?, email = ?, is_active = ? 
                    WHERE id = ? AND role = 'admin'";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['username'],
                $data['email'],
                $data['is_active'],
                $id
            ]);
            
            // Update password if provided
            if (!empty($data['password'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                $query = "UPDATE " . $this->table . " SET password = ? WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$hashedPassword, $id]);
            }
            
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function deleteAdmin($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ? AND role = 'admin'";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    // Helper Methods
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }

    public function updateLastLogin($id) {
        $query = "UPDATE " . $this->table . " SET last_login = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
<?php
require_once '../config/Database.php';

class AuthController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Validate login credentials
    public function login($email, $password) {
        try {
            // Prepare SQL to prevent SQL injection
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND is_active = 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            // Fetch user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                // Start session
                session_start();
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Update last login
                $updateStmt = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
                $updateStmt->bindParam(':id', $user['id']);
                $updateStmt->execute();
                
                // Log activity
                $this->logActivity($user['id'], 'login', 'User logged in successfully');
                
                return true;
            }
            
            return false;
        } catch(PDOException $e) {
            // Log error
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    // User registration
    public function register($username, $email, $password, $role = 'siswa') {
        try {
            // Check if email already exists
            $checkStmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() > 0) {
                return false; // Email already exists
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Prepare insert statement
            $stmt = $this->conn->prepare("
                INSERT INTO users (username, email, password, role, is_active) 
                VALUES (:username, :email, :password, :role, 1)
            ");
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            
            // Execute the statement
            $result = $stmt->execute();
            
            if ($result) {
                // Get the ID of the newly inserted user
                $userId = $this->conn->lastInsertId();
                
                // Log activity
                $this->logActivity($userId, 'register', 'New user registered');
                
                return $userId;
            }
            
            return false;
        } catch(PDOException $e) {
            // Log error
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    // Log user activities
    private function logActivity($userId, $activityType, $description) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO activity_logs (user_id, activity_type, description, ip_address, user_agent) 
                VALUES (:user_id, :activity_type, :description, :ip_address, :user_agent)
            ");
            
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':activity_type', $activityType);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
            $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
            
            $stmt->execute();
        } catch(PDOException $e) {
            // Log error but don't throw, as this is a secondary operation
            error_log("Activity log error: " . $e->getMessage());
        }
    }

    // Password reset request
    public function requestPasswordReset($email) {
        try {
            // Check if email exists
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // Generate a unique reset token
                $resetToken = bin2hex(random_bytes(32));
                $expiryTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                // Store reset token (you'd need to add columns to users table)
                $updateStmt = $this->conn->prepare("
                    UPDATE users 
                    SET reset_token = :token, 
                        reset_token_expiry = :expiry 
                    WHERE email = :email
                ");
                
                $updateStmt->bindParam(':token', $resetToken);
                $updateStmt->bindParam(':expiry', $expiryTime);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();
                
                return $resetToken;
            }
            
            return false;
        } catch(PDOException $e) {
            error_log("Password reset request error: " . $e->getMessage());
            return false;
        }
    }

    // Verify if user is logged in
    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }

    // Get current user details
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->bindParam(':id', $_SESSION['user_id']);
                $stmt->execute();
                
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                error_log("Get current user error: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }

    // Close database connection
    public function __destruct() {
        $this->db->closeConnection();
    }
}
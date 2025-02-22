<?php
class Logger {
    private $conn;
    private $table = 'activity_logs';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function log($user_id, $activity_type, $description) {
        $query = "INSERT INTO " . $this->table . " 
                (user_id, activity_type, description, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)";
                
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $user_id,
                $activity_type,
                $description,
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT']
            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getActivityLogs($user_id = null, $limit = 100) {
        $query = "SELECT al.*, u.username 
                FROM " . $this->table . " al 
                LEFT JOIN users u ON al.user_id = u.id ";
                
        if ($user_id) {
            $query .= "WHERE al.user_id = ? ";
        }
        
        $query .= "ORDER BY al.created_at DESC LIMIT ?";
        
        $stmt = $this->conn->prepare($query);
        
        if ($user_id) {
            $stmt->execute([$user_id, $limit]);
        } else {
            $stmt->execute([$limit]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php
class NotificationController {
    private $db;
    private $notification;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->notification = new Notification($this->db);
    }

    public function markRead() {
        AuthMiddleware::isLoggedIn();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $success = $this->notification->markAsRead($data['id'], $_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    public function markAllRead() {
        AuthMiddleware::isLoggedIn();
        
        $success = $this->notification->markAllAsRead($_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    public function getUnreadCount() {
        AuthMiddleware::isLoggedIn();
        
        $count = $this->notification->getUnreadCount($_SESSION['user_id']);
        
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }
}
<?php
class AuthMiddleware {
    public static function isLoggedIn() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    public static function isAdmin() {
        self::isLoggedIn();
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /siswa/dashboard');
            exit;
        }
    }

    public static function isSiswa() {
        self::isLoggedIn();
        if ($_SESSION['role'] !== 'siswa') {
            header('Location: /admin/dashboard');
            exit;
        }
    }
}
<?php
class Security {
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            $data = strip_tags($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            $data = trim($data);
        }
        return $data;
    }

    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }

    public static function preventXSS($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    public static function validateFileUpload($file) {
        // Check file size
        if ($file['size'] > 5000000) { // 5MB limit
            return 'File terlalu besar (maksimal 5MB)';
        }

        // Check for PHP files
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $dangerous_mimes = ['application/x-httpd-php', 'application/x-php'];
        if (in_array($mime_type, $dangerous_mimes)) {
            return 'Tipe file tidak diizinkan';
        }

        return true;
    }

    public static function validateImageFile($file) {
        // Check if it's really an image
        if (!getimagesize($file['tmp_name'])) {
            return 'File harus berupa gambar';
        }

        return true;
    }
}

class Security {
    public static function validateCSRFToken(string $token): bool {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
    
    public static function generateCSRFToken(): string {
        return $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
<?php
class SecurityHelper {

    public static function generateCSRFToken() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    public static function init() {
        // Ví dụ: Thiết lập header bảo mật
        header("X-Frame-Options: SAMEORIGIN");
        header("X-XSS-Protection: 1; mode=block");
        header("X-Content-Type-Options: nosniff");
        header("Content-Security-Policy: default-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; font-src 'self' https://cdn.jsdelivr.net data:;");
    }
    public static function validateCSRFToken($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    public static function log($message, $level = 'info') {
        $logFile = __DIR__ . '/../logs/security.log';
        if (!file_exists(dirname($logFile))) {
            mkdir(dirname($logFile), 0755, true);
        }
        $date = date('Y-m-d H:i:s');
        $msg = "[$date][$level] $message\n";
        file_put_contents($logFile, $msg, FILE_APPEND);
    }
    // Có thể bổ sung các hàm bảo mật khác ở đây (nếu cần)
    // public static function validateCSRFToken($token) { ... }
    // public static function log($msg, $level = 'info') { ... }
}
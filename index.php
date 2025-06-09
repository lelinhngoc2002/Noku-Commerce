<?php
require_once 'config/SecurityHelper.php';

// Khởi tạo bảo mật
SecurityHelper::init();

// Tắt hiển thị lỗi trên production
if ($_SERVER['SERVER_NAME'] !== 'localhost') {
    error_reporting(0);
    ini_set('display_errors', 0);
}

session_start();

spl_autoload_register(function ($class) {
    if (file_exists("controllers/$class.php")) {
        require_once "controllers/$class.php";
    } elseif (file_exists("models/$class.php")) {
        require_once "models/$class.php";
    } elseif (file_exists("config/$class.php")) {
        require_once "config/$class.php";
    }
});

// Validate controller và action
$controller = isset($_GET['controller']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['controller']) : 'Home';
$action = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['action']) : 'index';

// Gọi controller và action
$controllerName = $controller . 'Controller';
if (class_exists($controllerName)) {
    $controllerObject = new $controllerName();
    if (method_exists($controllerObject, $action)) {
        // Kiểm tra CSRF token cho các request POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!SecurityHelper::validateCSRFToken($token)) {
                SecurityHelper::log('CSRF attack detected', 'warning');
                die('Invalid request');
            }
        }

        $controllerObject->$action();

        // Sau khi controller xử lý xong, render layout nếu có $view_file
        if (isset($GLOBALS['view_file'])) {
            // Truyền tất cả biến từ $GLOBALS sang view
            extract($GLOBALS, EXTR_SKIP);

            // Thêm CSRF token vào form
            $csrf_token = SecurityHelper::generateCSRFToken();

            ob_start();
            include $GLOBALS['view_file'];
            $content = ob_get_clean();
            include 'views/layouts/main_layout.php';
        }
    } else {
        SecurityHelper::log("Action $action không tồn tại", 'error');
        die("Action $action không tồn tại.");
    }
} else {
    SecurityHelper::log("Controller $controllerName không tồn tại", 'error');
    die("Controller $controllerName không tồn tại.");
}
?>
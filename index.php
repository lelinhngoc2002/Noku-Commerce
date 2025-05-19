<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'Home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Gọi controller và action
$controllerName = $controller . 'Controller';
if (class_exists($controllerName)) {
    $controllerObject = new $controllerName();
    if (method_exists($controllerObject, $action)) {
        $controllerObject->$action();

        // Sau khi controller xử lý xong, render layout nếu có $view_file
        if (isset($GLOBALS['view_file'])) {
            // Truyền tất cả biến từ $GLOBALS sang view
            extract($GLOBALS, EXTR_SKIP);

            ob_start();
            include $GLOBALS['view_file'];
            $content = ob_get_clean();
            include 'views/layouts/main_layout.php';
        }
    } else {
        die("Action $action không tồn tại.");
    }
} else {
    die("Controller $controllerName không tồn tại.");
}
?>
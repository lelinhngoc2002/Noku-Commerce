<?php
class MyBaseAdminController {

    //Kiểm tra quyền truy cập
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=login");
            exit;
        }
    }
}
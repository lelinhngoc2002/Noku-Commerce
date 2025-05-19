<?php
class BaseAdminController {
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
//1. Mục đích của BaseAdminController:
//Kiểm tra quyền truy cập:
//Kiểm tra xem người dùng đã đăng nhập với quyền admin chưa
//Nếu chưa đăng nhập hoặc không phải admin, sẽ chuyển hướng về trang đăng nhập admin
//Khởi tạo session:
//Đảm bảo session đã được khởi tạo trước khi sử dụng
//Kiểm tra session_status() để tránh lỗi "session already started"
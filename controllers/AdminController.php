<?php
require_once 'models/User.php'; //Import User model


class AdminController {
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Nếu đã đăng nhập admin thì chuyển về dashboard, không cho vào lại trang login
        if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin') {
            header("Location: index.php?controller=admin&action=dashboard");
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
        
            $user = User::findByEmail($email);
        
            if (!$user) {
                $error = "Tài khoản không tồn tại!";
            } elseif (!password_verify($password, $user->password)) {
                $error = "Sai email hoặc mật khẩu!";
            } elseif ($user->role !== 'admin') {
                $error = "Không có quyền truy cập!";
            } else {
                // XÓA SESSION MEMBER TRƯỚC KHI ĐĂNG NHẬP ADMIN
                unset($_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_role']);
        
                $_SESSION['admin_id'] = $user->id;
                $_SESSION['admin_name'] = $user->name;
                $_SESSION['admin_role'] = $user->role;
        
                header("Location: index.php?controller=admin&action=dashboard");
                exit;
            }
        }

        // Chỉ truyền error sang view nếu có POST, còn lại là rỗng
        $GLOBALS['error'] = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $error : '';
        $GLOBALS['view_file'] = 'views/admin/login.php';
    }

    // Xử lý ràng buộc luôn hiển thị dashboard nếu đã đăng nhập admin
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=login");
            exit;
        }

        $GLOBALS['view_file'] = 'views/admin/dashboard.php';
    }

    //Xử lý logout
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header("Location: index.php?controller=admin&action=login");
        exit;
    }
}
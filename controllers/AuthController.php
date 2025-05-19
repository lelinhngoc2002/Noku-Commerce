<?php
require_once 'models/User.php';


class AuthController {
    public function loginForm() {
        $GLOBALS['view_file'] = 'views/admin/login.php'; // Cho admin
    }
    public function memberLoginForm() {
        $GLOBALS['view_file'] = 'views/member/login.php'; //cho member
    }
    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['role'] === 'admin') {
                // Xóa session member trước khi đăng nhập admin
                unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role']);
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_role'] = $user['role'];
                header('Location: index.php?controller=Admin&action=dashboard');
                exit;
            } else {
                // Xóa session admin trước khi đăng nhập member
                unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: index.php?controller=Home&action=productList');
                exit;
            }
        } else {
            $error = "Sai email hoặc mật khẩu.";
            $GLOBALS['view_file'] = 'views/admin/login.php';
        }
    }

    //Login member
    public function memberLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = User::findByEmail($email);
            if (!$user) {
                $error = "Tài khoản không tồn tại!";
            } elseif (!password_verify($password, $user->password)) {
                $error = "Sai email hoặc mật khẩu!";
            } elseif ($user->role !== 'member') {
                $error = "Tài khoản không có quyền truy cập!";
            } else {
                // Xóa session admin trước khi đăng nhập member
                unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;
                header("Location: index.php");
                exit;
            }
        }
        $GLOBALS['error'] = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $error : '';
        $GLOBALS['view_file'] = 'views/member/login.php';
    }

    //Logout
    public function logout() {
        session_destroy();
        header('Location: index.php?controller=admin&action=login');
        exit;
    }

    //Logout member
    public function memberLogout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Xóa session của member
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
    
    // Chuyển về trang đăng nhập member
        header("Location: index.php?controller=Auth&action=memberLoginForm");
        exit;
    }
}
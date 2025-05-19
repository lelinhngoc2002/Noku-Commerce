<?php
require_once 'models/User.php';

class ProfileController {

    //Hiển thị trang cá nhân
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }
        $user = User::find($_SESSION['user_id']);
        $GLOBALS['user'] = $user;
        $GLOBALS['view_file'] = 'views/member/profile.php';
    }

    //Cập nhật thông tin cá nhân
    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }

        $errors = [];
        $user = User::find($_SESSION['user_id']);

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        if (empty($name)) $errors['name'] = 'Tên không được để trống';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email không hợp lệ';

        if (!empty($password)) {
            if ($password !== $password_confirm) {
                $errors['password'] = 'Mật khẩu xác nhận không khớp';
            }
        }

        if (empty($errors)) {
            $data = [
                'id' => $user['id'],
                'name' => $name,
                'email' => $email,
                'role' => 'member'
            ];
            //Mã hóa mật khẩu khi cập nhật thông tin cá nhân
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            User::save($data);
            $_SESSION['user_name'] = $name;
            $_SESSION['success'] = 'Cập nhật thành công!';
            header("Location: index.php?controller=Profile&action=index");
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            header("Location: index.php?controller=Profile&action=index");
            exit;
        }
    }
}

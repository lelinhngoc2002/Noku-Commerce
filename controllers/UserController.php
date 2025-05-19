<?php
require_once 'models/User.php';

class UserController extends MyBaseAdminController {

    //Kiểm tra quyền truy cập
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'admin') {
            header("Location: index.php?controller=admin&action=login");
            exit;
        }
    }

    //Hiển thị danh sách user
    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $total_users = User::count($search, $role);
        $last_page = ceil($total_users / $limit);

        $GLOBALS['users'] = User::all($search, $role, $limit, $offset);
        $GLOBALS['search'] = $search;
        $GLOBALS['role'] = $role;
        $GLOBALS['current_page'] = $page;
        $GLOBALS['last_page'] = $last_page;
        $GLOBALS['view_file'] = 'views/admin/users_list.php';
    }

    public function create()
    {
        $user = null;
        $GLOBALS['view_file'] = 'views/admin/user_form.php';
    }

    //Thêm user
    public function store()
    {
        $errors = [];
        
        // Validation
        if (empty($_POST['name'])) {
            $errors['name'] = 'Tên không được để trống';
        }
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        if (empty($_POST['password'])) {
            $errors['password'] = 'Mật khẩu không được để trống';
        }
        if (empty($_POST['role'])) {
            $errors['role'] = 'Vai trò không được để trống';
        }

        //Mã hóa mật khẩu khi thêm mới User
        if (empty($errors)) {
            $data = $_POST;
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            User::save($data);
            $_SESSION['success'] = 'Thêm user thành công!';
            header("Location: index.php?controller=User&action=index");
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?controller=User&action=create");
            exit;
        }
    }

    //Hiển thị form cập nhật user
    public function edit()
    {
        $GLOBALS['user'] = User::find($_GET['id']);
        if (!$GLOBALS['user']) {
            $_SESSION['error'] = 'Không tìm thấy user!';
            header("Location: index.php?controller=User&action=index");
            exit;
        }
        $GLOBALS['view_file'] = 'views/admin/user_form.php';
    }

    //Cập nhật user
    public function update()
    {
        $errors = [];
        
        // Validation
        if (empty($_POST['name'])) {
            $errors['name'] = 'Tên không được để trống';
        }
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        if (empty($_POST['role'])) {
            $errors['role'] = 'Vai trò không được để trống';
        }

        if (empty($errors)) {
            $data = $_POST;

            // Nếu mật khẩu không nhập, loại bỏ khỏi $data để không cập nhật
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                unset($data['password']);
            }

            //Cập nhật user
            User::save($data);
            $_SESSION['success'] = 'Cập nhật user thành công!';
            header("Location: index.php?controller=User&action=index");
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?controller=User&action=edit&id=" . $_POST['id']);
            exit;
        }
    }

    //Xóa user
    public function delete()
    {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Không tìm thấy user!';
            header("Location: index.php?controller=User&action=index");
            exit;
        }

        User::delete($_GET['id']);
        $_SESSION['success'] = 'Xóa user thành công!';
        header("Location: index.php?controller=User&action=index");
        exit;
    }
}
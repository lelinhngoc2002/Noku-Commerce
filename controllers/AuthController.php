<?php
require_once 'models/User.php';

class AuthController {
    private $maxLoginAttempts = 5;
    private $lockoutTime = 60; // 1 phút

    private function checkRateLimit($email) {
        if (!isset($_SESSION['login_attempts'][$email])) {
            $_SESSION['login_attempts'][$email] = [
                'count' => 0,
                'time' => time()
            ];
        }

        $attempts = &$_SESSION['login_attempts'][$email];
        
        if ($attempts['count'] >= $this->maxLoginAttempts) {
            if (time() - $attempts['time'] < $this->lockoutTime) {
                return false;
            }
            $attempts['count'] = 0;
            $attempts['time'] = time();
        }
        return true;
    }

    private function incrementLoginAttempts($email) {
        $_SESSION['login_attempts'][$email]['count']++;
        $_SESSION['login_attempts'][$email]['time'] = time();
    }

    private function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    public function loginForm() {
        if (isset($_SESSION['admin_id'])) {
            header('Location: index.php?controller=Admin&action=dashboard');
            exit;
        }
        $GLOBALS['view_file'] = 'views/admin/login.php';
    }

    public function memberLoginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
        $GLOBALS['view_file'] = 'views/member/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=Auth&action=loginForm');
            exit;
        }

        $email = $this->sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$this->checkRateLimit($email)) {
            $error = "Tài khoản đã bị khóa do đăng nhập sai nhiều lần. Vui lòng thử lại sau 15 phút.";
            $GLOBALS['error'] = $error;
            $GLOBALS['view_file'] = 'views/admin/login.php';
            return;
        }

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Reset login attempts on successful login
            unset($_SESSION['login_attempts'][$email]);
            
            if ($user['role'] === 'admin') {
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role']);
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                header('Location: index.php?controller=Admin&action=dashboard');
                exit;
            } else {
                $error = "Tài khoản không có quyền truy cập admin.";
                $GLOBALS['error'] = $error;
                $GLOBALS['view_file'] = 'views/admin/login.php';
            }
        } else {
            $this->incrementLoginAttempts($email);
            $error = "Sai email hoặc mật khẩu.";
            $GLOBALS['error'] = $error;
            $GLOBALS['view_file'] = 'views/admin/login.php';
        }
    }

    public function memberLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=Auth&action=memberLoginForm');
            exit;
        }

        $error = '';
        $email = $this->sanitizeInput($_POST['email']);
        $password = $_POST['password'];

        if (!$this->checkRateLimit($email)) {
            $error = "Tài khoản đã bị khóa do đăng nhập sai nhiều lần. Vui lòng thử lại sau 1 phút.";
        } else {
            $user = User::findByEmail($email);
            
            if (!$user) {
                $error = "Tài khoản không tồn tại!";
                $this->incrementLoginAttempts($email);
            } elseif (!password_verify($password, $user->password)) {
                $error = "Sai email hoặc mật khẩu!";
                $this->incrementLoginAttempts($email);
            } elseif ($user->role !== 'member') {
                $error = "Tài khoản không có quyền truy cập!";
            } else {
                // Reset login attempts on successful login
                unset($_SESSION['login_attempts'][$email]);
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['last_activity'] = time();
                
                header("Location: index.php");
                exit;
            }
        }
        
        $GLOBALS['error'] = $error;
        $GLOBALS['view_file'] = 'views/member/login.php';
    }

    public function logout() {
        // Regenerate session ID before destroying
        session_regenerate_id(true);
        session_destroy();
        header('Location: index.php?controller=Auth&action=loginForm');
        exit;
    }

    public function memberLogout() {
        // Regenerate session ID before destroying
        session_regenerate_id(true);
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        header("Location: index.php?controller=Auth&action=memberLoginForm");
        exit;
    }
}
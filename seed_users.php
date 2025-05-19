<?php
require_once 'config/DB.php';

$db = DB::getInstance();

// Mã hóa mật khẩu
$password = 'password';
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Thêm user vào cơ sở dữ liệu
$stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute(['Admin', 'admin@example.com', $hashedPassword, 'admin']);

echo "User admin đã được thêm vào cơ sở dữ liệu.";

// File này chỉ chạy một lần để tạo user mẫu (admin, member...) trong database.
// Nó không liên quan đến quá trình đăng ký, thêm user mới, hoặc đổi mật khẩu trong quá trình sử dụng website.
?>
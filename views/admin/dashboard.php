<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Chào mừng <?= htmlspecialchars($_SESSION['admin_name']) ?></h2>

    <div class="mt-4">
        <a href="index.php?controller=User&action=index" class="btn btn-primary me-2">
            <i class="bi bi-person-fill"></i>
            Quản lý người dùng
        </a>
        <a href="index.php?controller=Product&action=index" class="btn btn-success me-2">
            <i class="bi bi-cart-fill"></i>
            Quản lý sản phẩm
        </a>
        <a href="index.php?controller=admin&action=logout" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i>
            Đăng xuất
        </a>
    </div>
</body>
</html>

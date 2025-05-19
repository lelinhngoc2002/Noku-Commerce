<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>Noku Commerce</title>
</head>
<body>
    <?php
    // Lấy controller và action hiện tại
    $controller = $_GET['controller'] ?? '';
    $action = $_GET['action'] ?? '';
    ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container">
                <?php
            // Xác định link cho logo theo loại tài khoản
            if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin') {
                $logo_link = "index.php?controller=Admin&action=dashboard";
            } else {
                $logo_link = "index.php";
            }
                ?>
            <a class="navbar-brand" href="<?= $logo_link ?>">Noku Commerce</a>
            <!-- Thêm nút toggle cho mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Thêm menu items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=Admin&action=dashboard">
                                Xin chào, <?= htmlspecialchars($_SESSION['admin_name']) ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=admin&action=logout">
                            <i class="bi bi-box-arrow-right"></i>Đăng xuất</a>
                        </li>
                    <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'member'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile">Xin chào, <?= htmlspecialchars($_SESSION['user_name']) ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=Auth&action=memberLogout">
                                <i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart">
                                <i class="bi bi-cart"></i> Giỏ hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders">
                                <i class="bi bi-receipt"></i> Đơn hàng</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login">
                                <i class="bi bi-person-fill"></i> Đăng nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>   
            </div>
        </div>
    </nav>
    <main>
        <?php if (isset($content)) echo $content; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$error = $GLOBALS['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <title>Đăng nhập</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Đăng nhập quản trị viên</h1>
        <form action="index.php?controller=Admin&action=login" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($error)): ?>
            <p class="text-danger mt-3"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
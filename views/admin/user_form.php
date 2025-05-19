<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="container mt-4">
    <h2><?php echo isset($user) ? 'Sửa User' : 'Thêm User Mới'; ?></h2>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?controller=User&action=<?php echo (isset($user) && isset($user['id'])) ? 'update' : 'store'; ?>">
        <?php if (isset($user) && isset($user['id'])): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" 
                   value="<?php
                        if (isset($_SESSION['old']['name'])) {
                            echo htmlspecialchars($_SESSION['old']['name']);
                        } elseif (isset($user['name'])) {
                            echo htmlspecialchars($user['name']);
                        }
                   ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php
                        if (isset($_SESSION['old']['email'])) {
                            echo htmlspecialchars($_SESSION['old']['email']);
                        } elseif (isset($user['email'])) {
                            echo htmlspecialchars($user['email']);
                        }
                   ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu <?php echo (isset($user) && isset($user['id'])) ? '(để trống nếu không muốn thay đổi)' : ''; ?></label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Vai trò</label>
            <select class="form-control" id="role" name="role">
                <option value="member"
                    <?php
                        if ((isset($_SESSION['old']['role']) && $_SESSION['old']['role'] == 'member') ||
                            (isset($user['role']) && $user['role'] == 'member')) echo 'selected';
                    ?>
                >Member</option>
                <option value="admin"
                    <?php
                        if ((isset($_SESSION['old']['role']) && $_SESSION['old']['role'] == 'admin') ||
                            (isset($user['role']) && $user['role'] == 'admin')) echo 'selected';
                    ?>
                >Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo (isset($user) && isset($user['id'])) ? 'Cập nhật' : 'Thêm mới'; ?></button>
        <a href="index.php?controller=User&action=index" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php unset($_SESSION['old']); ?>
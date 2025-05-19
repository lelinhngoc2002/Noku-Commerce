<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><i class="bi bi-people"></i> Danh sách Users</h2>
        <a href="index.php?controller=User&action=create" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Thêm User Mới
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form tìm kiếm và lọc tài khoản-->
    <form method="GET" class="mb-4">
        <input type="hidden" name="controller" value="User">
        <input type="hidden" name="action" value="index">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           value="<?= htmlspecialchars($search ?? '') ?>" 
                           placeholder="Tìm kiếm theo tên hoặc email...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" name="role" onchange="this.form.submit()">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="member" <?= ($role ?? '') === 'member' ? 'selected' : '' ?>>Member</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="index.php?controller=User&action=index" class="btn btn-secondary w-100">
                    <i class="bi bi-x-circle"></i> Xóa bộ lọc
                </a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle"> <!-- table-striped: tạo màu để phân biệt giữa các dòng -->
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Email</th>
                    <th scope="col">Vai trò</th>
                    <th scope="col" class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'primary' : 'secondary'; ?>">
                            <?php echo $user['role']; ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="index.php?controller=User&action=edit&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm me-1">
                            <i class="bi bi-pencil-square"></i> Sửa
                        </a>
                        <a href="index.php?controller=User&action=delete&id=<?php echo $user['id']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa user này?')">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <?php if ($last_page > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=User&action=index&page=1&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>">
                            Trước
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $last_page; $i++): ?>
                    <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?controller=User&action=index&page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($current_page < $last_page): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=User&action=index&page=<?= $last_page ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>">
                            Sau
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>


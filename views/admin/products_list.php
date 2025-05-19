<?php
$products = $GLOBALS['products'] ?? [];
$current_page = $GLOBALS['current_page'] ?? 1;
$last_page = $GLOBALS['last_page'] ?? 1;
$search = $GLOBALS['search'] ?? '';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Danh sách sản phẩm</h2>
        <a href="index.php?controller=Product&action=create" class="btn btn-primary">Thêm sản phẩm mới</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Search form -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="controller" value="Product">
        <input type="hidden" name="action" value="index">
        <div class="input-group">
            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Tìm kiếm sản phẩm...">
            <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
        </div>
    </form>

    <!-- Products table -->
    <div class="table-responsive">
        <?php if (empty($products)): ?>
            <div class="alert alert-warning text-center">Không tìm thấy sản phẩm tương ứng.</div>
        <?php else: ?>
        <table class="table table-striped"> <!-- table-striped: tạo dòng chữ để phân biệt giữa các dòng -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="uploads/products/<?= $product['image'] ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     style="max-width: 50px">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= number_format($product['price']) ?> VNĐ</td>
                        <td><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</td>
                        <td>
                            <a href="index.php?controller=Product&action=edit&id=<?= $product['id'] ?>" 
                               class="btn btn-sm btn-primary">Sửa</a>
                            <a href="index.php?controller=Product&action=delete&id=<?= $product['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?php if ($last_page > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=Product&action=index&page=1&search=<?= urlencode($search) ?>">
                            Trước
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $last_page; $i++): ?>
                    <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?controller=Product&action=index&page=<?= $i ?>&search=<?= urlencode($search) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <?php if ($current_page < $last_page): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=Product&action=index&page=<?= $last_page ?>&search=<?= urlencode($search) ?>">
                            Sau
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
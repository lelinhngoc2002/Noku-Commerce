<div class="container mt-5">
    <h2 class="text-center mb-4">Danh sách sản phẩm</h2>
    <!-- Form tìm kiếm -->
    <form method="GET" class="mb-4">
        <input type="hidden" name="controller" value="Home">
        <input type="hidden" name="action" value="index">
        <div class="input-group">
            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Tìm kiếm sản phẩm...">
            <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
        </div>
    </form>
    <div class="row justify-content-center">
        <?php if (empty($products)): ?>
            <p class="text-center text-muted">Không tìm thấy sản phẩm tương ứng.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($product['image'])): ?>
                            <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height:200px;object-fit:contain;">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="card-text"><strong>Giá: <?= number_format($product['price'], 0, ',', '.') ?> VNĐ</strong></p>                             
                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'member'): ?>
                                <form method="POST" action="cart/add/<?= $product['id'] ?>" class="d-flex align-items-center justify-content-between">
                                    <div class="input-group input-group-sm me-2" style="width: 110px;">
                                        <button type="button" class="btn btn-outline-secondary btn-qty-minus">-</button>
                                        <input type="number" name="quantity" class="form-control text-center qty-input" value="1" min="1" style="max-width: 50px;">
                                        <button type="button" class="btn btn-outline-secondary btn-qty-plus">+</button>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Thêm vào giỏ</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Phân trang sản phẩm-->
    <?php if (($last_page ?? 1) > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if (($current_page ?? 1) > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=Home&action=index&page=1&search=<?= urlencode($search ?? '') ?>">
                            Trước
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= ($last_page ?? 1); $i++): ?>
                    <li class="page-item <?= ($i == ($current_page ?? 1)) ? 'active' : '' ?>">
                        <a class="page-link" href="?controller=Home&action=index&page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <?php if (($current_page ?? 1) < ($last_page ?? 1)): ?>
                    <li class="page-item">
                        <a class="page-link" href="?controller=Home&action=index&page=<?= $last_page ?? 1 ?>&search=<?= urlencode($search ?? '') ?>">
                            Sau
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Thêm script quantity selector -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.card').forEach(function(card) {
        const minusBtn = card.querySelector('.btn-qty-minus');
        const plusBtn = card.querySelector('.btn-qty-plus');
        const qtyInput = card.querySelector('.qty-input');
        if (minusBtn && plusBtn && qtyInput) {
            minusBtn.addEventListener('click', function() {
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) qtyInput.value = val - 1;
            });
            plusBtn.addEventListener('click', function() {
                let val = parseInt(qtyInput.value) || 1;
                qtyInput.value = val + 1;
            });
        }
    });
});
</script>

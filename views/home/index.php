<div class="container mt-5">
    <h1 class="text-center">Danh sách sản phẩm</h1>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="card-text"><strong>Giá:</strong> <?= number_format($product['price'], 2) ?> VND</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Không có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>

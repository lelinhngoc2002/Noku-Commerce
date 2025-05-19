<!-- Container chính với chiều rộng tối đa -->
<div class="container mt-5" style="max-width: 900px;">
    <!-- Card chứa thông tin chi tiết đơn hàng -->
    <div class="bg-white rounded-4 shadow p-4">
        <!-- Header với logo và tiêu đề -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a class="navbar-brand fs-3 fw-bold text-primary" href="index.php" style="text-decoration:none;">
                <i class="bi bi-bag-check-fill me-2"></i>Noku Sale
            </a>
            <h2 class="mb-0 text-center flex-grow-1">
                <i class="bi bi-receipt-cutoff me-2"></i>Chi tiết đơn hàng #<?= $order['id'] ?>
            </h2>
        </div>

        <!-- Thông tin tổng quan đơn hàng -->
        <div class="mb-3">
            <strong>Ngày đặt:</strong> <?= $order['order_date'] ?><br>
            <strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?= number_format($order['total'], 0, ',', '.') ?> VNĐ</span>
        </div>

        <!-- Bảng chi tiết sản phẩm trong đơn hàng -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm rounded-3">
                <!-- Header của bảng -->
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">Hình ảnh</th>
                        <th class="text-center">Tên sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Giá</th>
                        <th class="text-center">Thành tiền</th>
                    </tr>
                </thead>
                <!-- Danh sách sản phẩm -->
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <!-- Cột hình ảnh sản phẩm -->
                        <td class="text-center">
                            <?php if (!empty($item['image'])): ?>
                                <img src="uploads/products/<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                     style="max-width: 60px; max-height: 60px;">
                            <?php endif; ?>
                        </td>
                        <!-- Tên sản phẩm -->
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <!-- Số lượng đã mua -->
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <!-- Đơn giá -->
                        <td class="text-end"><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
                        <!-- Thành tiền (số lượng * đơn giá) -->
                        <td class="text-end text-danger fw-bold">
                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> VNĐ
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Nút quay lại danh sách đơn hàng -->
        <a href="index.php?controller=Order&action=myOrders" class="btn btn-secondary mt-3">
            Quay lại danh sách đơn hàng
        </a>
    </div>
</div> 
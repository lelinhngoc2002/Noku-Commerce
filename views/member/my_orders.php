<?php
// Lấy danh sách đơn hàng từ controller
$orders = $GLOBALS['orders'] ?? [];
?>

<div class="container mt-5" style="max-width: 900px;">
    <div class="bg-white rounded-4 shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a class="navbar-brand fs-3 fw-bold text-primary" href="index.php" style="text-decoration:none;">
                <i class="bi bi-bag-check-fill me-2"></i>Noku Sale
            </a>
            <h2 class="mb-0 text-center flex-grow-1">
                <i class="bi bi-receipt-cutoff me-2"></i>Đơn hàng của tôi
            </h2>
        </div>
        <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-2"></i>Bạn chưa có đơn hàng nào.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle shadow-sm rounded-3">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">Mã đơn</th>
                            <th class="text-center">Ngày đặt</th>
                            <th class="text-center">Tổng tiền</th>
                            <th class="text-center">Chỉnh sửa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="text-center fw-semibold"><?= $order['id'] ?></td>
                            <td class="text-center"><?= $order['order_date'] ?></td>
                            <td class="fw-bold text-danger text-end"><?= number_format($order['total'], 0, ',', '.') ?> VNĐ</td>
                            <td class="text-center">
                                <a href="index.php?controller=Order&action=orderDetail&id=<?= $order['id'] ?>" class="btn btn-info btn-sm me-1">
                                    <i class="bi bi-eye"></i> Xem chi tiết</a>
                                <a href="index.php?controller=Order&action=deleteOrder&id=<?= $order['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')">
                                    <i class="bi bi-trash"></i> Hủy đơn
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

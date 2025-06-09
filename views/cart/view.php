<div class="container mt-5">
    <h2>Giỏ hàng</h2>
    <?php if (empty($products)): ?>
        <p>Giỏ hàng trống.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; foreach ($products as $product): 
                    $subtotal = $product['price'] * $product['cart_qty'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</td>
                    <td><?= $product['cart_qty'] ?></td>
                    <td><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</td>
                    <td>
                        <a href="index.php?controller=Cart&action=remove&id=<?= $product['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ</h4>
        <a href="index.php?controller=Cart&action=clear" class="btn btn-secondary mt-2">Xóa toàn bộ giỏ hàng</a>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'member'): ?>
            <form method="POST" action="index.php?controller=Order&action=checkout" class="mt-2">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button type="submit" class="btn btn-success">Đặt hàng</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
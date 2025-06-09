<?php
$product = $GLOBALS['product'] ?? null;
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="container mt-4">
    <h2><?= $product ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' ?></h2>
    
    <?php if (isset($errors['general'])): ?>
        <div class="alert alert-danger"><?= $errors['general'] ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?controller=Product&action=<?= $product ? 'update' : 'store' ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <?php if ($product): ?>
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                   id="name" name="name" value="<?= $old['name'] ?? $product['name'] ?? '' ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?= $errors['name'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" class="form-control <?= isset($errors['price']) ? 'is-invalid' : '' ?>" 
                   id="price" name="price" value="<?= $old['price'] ?? $product['price'] ?? '' ?>">
            <?php if (isset($errors['price'])): ?>
                <div class="invalid-feedback"><?= $errors['price'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                      id="description" name="description" rows="3"><?= $old['description'] ?? $product['description'] ?? '' ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="invalid-feedback"><?= $errors['description'] ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <?php if ($product && !empty($product['image'])): ?>
                <div class="mb-2">
                    <img src="uploads/products/<?= $product['image'] ?>" alt="Current image" style="max-width: 200px">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>" 
                   id="image" name="image" accept="image/*">
            <?php if (isset($errors['image'])): ?>
                <div class="invalid-feedback"><?= $errors['image'] ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary"><?= $product ? 'Cập nhật' : 'Thêm mới' ?></button>
        <a href="index.php?controller=Product&action=index" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
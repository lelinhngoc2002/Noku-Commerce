<?php
require_once 'models/Product.php';
class ProductController extends MyBaseAdminController {

    //Hiển thị danh sách sản phẩm
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 6;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $result = Product::all($page, $perPage, $search);

        $GLOBALS['products'] = $result['data'];
        $GLOBALS['current_page'] = $page;
        $GLOBALS['last_page'] = $result['last_page'];
        $GLOBALS['search'] = $search;
        $GLOBALS['view_file'] = 'views/admin/products_list.php';
    }

    //Hiển thị form thêm sản phẩm
    public function create() {
        $GLOBALS['view_file'] = 'views/admin/product_form.php';
    }

    //Thêm sản phẩm
    public function store() {
        require_once __DIR__ . '/../middleware/SecurityMiddleware.php';
        SecurityMiddleware::validateCSRFToken();
        $data = $_POST;

        // Xử lý upload ảnh nếu có
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $data['image'] = Product::handleImageUpload($_FILES['image']);
        }

        Product::save($data);
        header("Location: index.php?controller=Product&action=index");
        exit;
    }

    //Hiển thị form cập nhật sản phẩm
    public function edit() {
        $product = Product::find($_GET['id']);
        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            header("Location: index.php?controller=Product&action=index");
            exit;
        }

        $GLOBALS['product'] = $product;
        $GLOBALS['view_file'] = 'views/admin/product_form.php';
    }

    //Cập nhật sản phẩm
    public function update() {
        require_once __DIR__ . '/../middleware/SecurityMiddleware.php';
        SecurityMiddleware::validateCSRFToken();
        $data = $_POST;

        // Kiểm tra nếu không upload ảnh mới thì giữ lại ảnh cũ
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $oldProduct = Product::find($data['id']);
            if ($oldProduct && isset($oldProduct['image'])) {
                $data['image'] = $oldProduct['image'];
            }
        } else {
            // Nếu có upload ảnh mới thì xử lý upload
            $data['image'] = Product::handleImageUpload($_FILES['image']);
        }

        Product::save($data);
        header("Location: index.php?controller=Product&action=index");
        exit;
    }

    public function delete() {
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            header("Location: index.php?controller=Product&action=index");
            exit;
        }

        Product::delete($_GET['id']);
        $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        header("Location: index.php?controller=Product&action=index");
        exit;
    }
}
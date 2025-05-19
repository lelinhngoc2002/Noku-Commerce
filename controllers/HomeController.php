<?php
require_once 'models/Product.php';

class HomeController {

    //Hiển thị trang chủ
    public function index() {
        $this->productList();
    }

    //Hiển thị danh sách sản phẩm
    public function productList() {
        $search = $_GET['search'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;

        $db = DB::getInstance();

        if ($search) {
            $query = "SELECT * FROM products WHERE name LIKE :search LIMIT $limit OFFSET $offset";
            $stmt = $db->prepare($query);
            $stmt->execute(['search' => "%$search%"]);
            $countStmt = $db->prepare("SELECT COUNT(*) FROM products WHERE name LIKE :search");
            $countStmt->execute(['search' => "%$search%"]);
        } else {
            $query = "SELECT * FROM products LIMIT $limit OFFSET $offset";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $countStmt = $db->prepare("SELECT COUNT(*) FROM products");
            $countStmt->execute();
        }

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total = $countStmt->fetchColumn();
        $totalPages = ceil($total / $limit);

        $GLOBALS['products'] = $products;
        $GLOBALS['current_page'] = $page;
        $GLOBALS['last_page'] = $totalPages;
        $GLOBALS['search'] = $search;
        $GLOBALS['view_file'] = 'views/home/product_list.php';
    }
}
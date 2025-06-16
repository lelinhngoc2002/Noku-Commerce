<?php
require_once 'models/Product.php';

class CartController {

    //Thêm sản phẩm vào giỏ hàng
    public function add() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id = $_GET['id'] ?? 0;
        $quantity = $_POST['quantity'] ?? 1;
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $quantity;
        } else {
            $_SESSION['cart'][$id] = $quantity;
        }
<<<<<<< HEAD
        header("Location: /mywebsite/cart");
=======
        header("Location: /Noku_Commerce/cart");
>>>>>>> cf88fa8 (fix)
        exit;
    }

    //Hiển thị giỏ hàng
    public function view() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        if ($cart) {
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    $product['cart_qty'] = $qty;
                    $products[] = $product;
                }
            }
        }
        $GLOBALS['products'] = $products;
        $GLOBALS['view_file'] = 'views/cart/view.php';
    }


    //Xóa sản phẩm khỏi giỏ hàng
    public function remove() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id = $_GET['id'] ?? 0;
        unset($_SESSION['cart'][$id]);
        header("Location: index.php?controller=Cart&action=view");
        exit;
    }

    //Xóa tất cả sản phẩm khỏi giỏ hàng
    public function clear() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['cart']);
        header("Location: index.php?controller=Cart&action=view");
        exit;
    }
}
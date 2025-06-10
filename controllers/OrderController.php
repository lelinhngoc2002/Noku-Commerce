<?php
require_once 'models/Product.php';
require_once 'config/DB.php'; // Nếu bạn có class DB riêng

class OrderController {

    /**
     * Xử lý đặt hàng từ giỏ hàng
     * - Kiểm tra đăng nhập
     * - Tính tổng tiền
     * - Lưu đơn hàng và chi tiết đơn hàng
     * - Xóa giỏ hàng sau khi đặt thành công
     */
    public function checkout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = "Giỏ hàng trống!";
            header("Location: index.php?controller=Cart&action=view");
            exit;
        }
        $db = DB::getInstance();
        $db->beginTransaction();
        try {
            $total = 0;
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) $total += $product['price'] * $qty;
            }
            // Lấy tên user
            $user_id = $_SESSION['user_id'];
            $user = User::find($user_id);
            $user_name = $user['name'] ?? '';
            // Lưu đơn hàng với user_name
            $stmt = $db->prepare("INSERT INTO orders (user_id, user_name, order_date, total) VALUES (:user_id, :user_name, NOW(), :total)");
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_name', $user_name);
            $stmt->bindValue(':total', $total);
            $stmt->execute();
            $order_id = $db->lastInsertId();
            // Lưu chi tiết đơn hàng với product_name và user_name
            $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, user_name, quantity, price) VALUES (:order_id, :product_id, :product_name, :user_name, :quantity, :price)");
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    $product_name = $product['name'];
                    $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
                    $stmt->bindValue(':product_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':product_name', $product_name);
                    $stmt->bindValue(':user_name', $user_name);
                    $stmt->bindValue(':quantity', $qty, PDO::PARAM_INT);
                    $stmt->bindValue(':price', $product['price']);
                    $stmt->execute();
                }
            }
            $db->commit();
            unset($_SESSION['cart']);
            $_SESSION['success'] = "Đặt hàng thành công!";
            header("Location: index.php?controller=Order&action=myOrders");
            exit;
        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Có lỗi khi đặt hàng!";
            header("Location: index.php?controller=Cart&action=view");
            exit;
        }
    }

    /**
     * Hiển thị danh sách đơn hàng của user đã đăng nhập
     * - Kiểm tra đăng nhập
     * - Lấy tất cả đơn hàng của user
     * - Sắp xếp theo ngày đặt mới nhất
     */
    public function myOrders() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Truyền dữ liệu sang view qua layout
        $GLOBALS['orders'] = $orders;
        $GLOBALS['view_file'] = 'views/member/my_orders.php';
    }

    /**
     * Xóa đơn hàng
     * - Kiểm tra đăng nhập
     * - Xóa chi tiết đơn hàng trước
     * - Xóa đơn hàng chính
     * - Chỉ cho phép xóa đơn hàng của chính user đó
     */
    public function deleteOrder() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($order_id > 0) {
            $db = DB::getInstance();
            // Xóa order_items trước (nếu có ràng buộc foreign key ON DELETE CASCADE thì không cần)
            $stmt = $db->prepare("DELETE FROM order_items WHERE order_id = :order_id");
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            // Xóa đơn hàng thuộc user
            $stmt = $db->prepare("DELETE FROM orders WHERE id = :order_id AND user_id = :user_id");
            $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        }
        header("Location: index.php?controller=Order&action=myOrders");
        exit;
    }
    
    /**
     * Xem chi tiết đơn hàng
     * - Kiểm tra đăng nhập
     * - Lấy thông tin đơn hàng theo ID
     * - Lấy danh sách sản phẩm trong đơn hàng (join với bảng products)
     * - Hiển thị chi tiết: hình ảnh, tên, số lượng, giá, thành tiền
     */
    public function orderDetail() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=Auth&action=memberLoginForm");
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($order_id <= 0) {
            header("Location: index.php?controller=Order&action=myOrders");
            exit;
        }
        $db = DB::getInstance();
        // Lấy thông tin đơn hàng
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = :order_id AND user_id = :user_id");
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            header("Location: index.php?controller=Order&action=myOrders");
            exit;
        }
        // Lấy danh sách sản phẩm trong đơn hàng (join với products để lấy hình ảnh, tên...)
        $stmt = $db->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
        $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $GLOBALS['order'] = $order;
        $GLOBALS['items'] = $items;
        $GLOBALS['view_file'] = 'views/member/order_detail.php';
    }
}

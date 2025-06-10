<?php
require_once 'config/DB.php';
require_once __DIR__ . '/../middleware/SecurityMiddleware.php';

class Product {
    // Lấy danh sách sản phẩm có phân trang và tìm kiếm
    public static function all($page = 1, $perPage = 10, $search = '') {
        $db = DB::getInstance();
        $offset = ($page - 1) * $perPage;

        $where = '';
        $params = [];

        if (!empty($search)) {
            $where = "WHERE name LIKE :search OR description LIKE :search";
            $params['search'] = "%$search%";
        }

        // Đếm tổng số sản phẩm
        $countStmt = $db->prepare("SELECT COUNT(*) FROM products $where");
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        // Lấy danh sách sản phẩm theo trang và tìm kiếm
        $sql = "SELECT * FROM products $where ORDER BY id DESC LIMIT :offset, :limit";
        $stmt = $db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ];
    }

    // Tìm sản phẩm theo ID
    public static function find($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lưu hoặc cập nhật sản phẩm
    public static function save($data) {
        $db = DB::getInstance();

        if (!empty($data['id'])) {
            // Nếu có ảnh mới
            if (!empty($data['image'])) {
                $stmt = $db->prepare("UPDATE products SET name = :name, price = :price, description = :description, image = :image WHERE id = :id");
                $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
                $stmt->bindValue(':name', $data['name']);
                $stmt->bindValue(':price', $data['price']);
                $stmt->bindValue(':description', $data['description']);
                $stmt->bindValue(':image', $data['image']);
            } else {
                // Không cập nhật trường image
                $stmt = $db->prepare("UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id");
                $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
                $stmt->bindValue(':name', $data['name']);
                $stmt->bindValue(':price', $data['price']);
                $stmt->bindValue(':description', $data['description']);
            }
        } else {
            // insert
            $stmt = $db->prepare("INSERT INTO products (name, price, description, image) VALUES (:name, :price, :description, :image)");
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':price', $data['price']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':image', $data['image'] ?? null);
        }
        return $stmt->execute();
    }

    // Xóa sản phẩm theo ID
    public static function delete($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Xử lý upload hình ảnh
    public static function handleImageUpload($file) {
        $uploadDir = 'uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Kiểm tra file upload
        try {
            SecurityMiddleware::validateFileUpload($file);
        } catch (Exception $e) {
            // Có thể log lỗi hoặc trả về chuỗi rỗng
            return '';
        }

        $fileName = SecurityMiddleware::sanitizeFileName($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }
        return '';
    }
}
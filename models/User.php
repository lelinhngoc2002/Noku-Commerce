<?php
require_once 'config/DB.php';

class User {
    // Thuộc tính của class User
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public ?string $created_at;
    public ?string $updated_at;

    // Tìm user theo email
    public static function findByEmail($email) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User'); // Gán kết quả vào class User
        return $stmt->fetch(); // Trả về đối tượng User hoặc null
    }

    // Lấy tất cả user, mỗi trang 6 user
    public static function all($search = '', $role = '', $limit = 6, $offset = 0) {
        $db = DB::getInstance();
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(name LIKE :search OR email LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($role)) {
            $where[] = "role = :role";
            $params['role'] = $role;
        }

        $sql = "SELECT * FROM users";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số user
    public static function count($search = '', $role = '') {
        $db = DB::getInstance();
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(name LIKE :search OR email LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($role)) {
            $where[] = "role = :role";
            $params['role'] = $role;
        }

        $sql = "SELECT COUNT(*) FROM users";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Tìm user theo ID
    public static function find($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lưu hoặc cập nhật user
    public static function save($data) {
        $db = DB::getInstance();

        if (isset($data['id']) && $data['id']) {
            // UPDATE
            if (isset($data['password']) && $data['password'] !== '') {
                // Có cập nhật mật khẩu
                $sql = "UPDATE users SET name=:name, email=:email, password=:password, role=:role, updated_at=NOW() WHERE id=:id";
            } else {
                // Không cập nhật mật khẩu
                $sql = "UPDATE users SET name=:name, email=:email, role=:role, updated_at=NOW() WHERE id=:id";
            }
            $stmt = $db->prepare($sql);
            $params = [
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':role' => $data['role'],
                ':id' => $data['id']
            ];
            if (isset($data['password']) && $data['password'] !== '') {
                $params[':password'] = $data['password'];
            }
            $stmt->execute($params);
        } else {
            // INSERT
            $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':role' => $data['role']
            ]);
        }
    }

    // Xóa user theo ID
    public static function delete($id) {
        $db = DB::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
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

    // Validate email
    private static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validate password strength
    private static function validatePassword($password) {
        // Ít nhất 8 ký tự, 1 chữ hoa, 1 chữ thường, 1 số
        return strlen($password) >= 8 
            && preg_match('/[A-Z]/', $password) 
            && preg_match('/[a-z]/', $password) 
            && preg_match('/[0-9]/', $password);
    }

    // Sanitize input
    private static function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitizeInput'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Tìm user theo email
    public static function findByEmail($email) {
        if (!self::validateEmail($email)) {
            return null;
        }

        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute();
        return $stmt->fetch();
    }

    // Lấy tất cả user, mỗi trang 6 user
    public static function all($search = '', $role = '', $limit = 6, $offset = 0) {
        $db = DB::getInstance();
        $where = [];
        $params = [];

        if (!empty($search)) {
            $search = self::sanitizeInput($search);
            $where[] = "(name LIKE :search OR email LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($role)) {
            $role = self::sanitizeInput($role);
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
            $search = self::sanitizeInput($search);
            $where[] = "(name LIKE :search OR email LIKE :search)";
            $params['search'] = "%$search%";
        }

        if (!empty($role)) {
            $role = self::sanitizeInput($role);
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
        if (!is_numeric($id)) {
            return null;
        }

        $db = DB::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lưu hoặc cập nhật user
    public static function save($data) {
        $db = DB::getInstance();
        
        // Sanitize input
        $data = self::sanitizeInput($data);

        // Validate email
        if (!self::validateEmail($data['email'])) {
            throw new Exception('Email không hợp lệ');
        }

        // Validate password for new users or password changes
        if (!isset($data['id']) || (isset($data['password']) && $data['password'] !== '')) {
            if (!self::validatePassword($data['password'])) {
                throw new Exception('Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số');
            }
            
        }

        if (isset($data['id']) && $data['id']) {
            // UPDATE
            if (isset($data['password']) && $data['password'] !== '') {
                $sql = "UPDATE users SET name=:name, email=:email, password=:password, role=:role, updated_at=NOW() WHERE id=:id";
            } else {
                $sql = "UPDATE users SET name=:name, email=:email, role=:role, updated_at=NOW() WHERE id=:id";
            }
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':role', $data['role']);
            $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
            if (isset($data['password']) && $data['password'] !== '') {
                $stmt->bindValue(':password', $data['password']);
            }
            $stmt->execute();
        } else {
            // INSERT
            $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $data['password']);
            $stmt->bindValue(':role', $data['role']);
            $stmt->execute();
        }
    }

    // Xóa user theo ID
    public static function delete($id) {
        if (!is_numeric($id)) {
            return false;
        }

        $db = DB::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
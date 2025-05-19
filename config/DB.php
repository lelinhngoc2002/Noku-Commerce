<?php
class DB {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $host = 'localhost';
                $dbname = 'lab_db';
                $username = 'root';
                $password = '';
                self::$instance = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Kết nối cơ sở dữ liệu thất bại: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
?>
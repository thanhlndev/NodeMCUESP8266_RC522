<?php
class Database
{
    private static $dbName = 'nodemcu_rfidrc522_mysql';
    private static $dbHost = 'localhost:3306'; // Thay bằng hostname MySQL Docker nếu cần
    private static $dbUsername = 'root'; // Thay bằng username MySQL
    private static $dbUserPassword = 'root'; // Thay bằng mật khẩu MySQL
    private static $connection = null;

    private function __construct()
    {
        die('Init function is not allowed');
    }

    public static function connect()
    {
        if (null === self::$connection) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName . ";charset=utf8mb4",
                    self::$dbUsername,
                    self::$dbUserPassword,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function disconnect()
    {
        self::$connection = null;
    }
}

<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $path = __DIR__ . '/../database.sqlite';
        $this->connection = new SQLite3($path);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
?>

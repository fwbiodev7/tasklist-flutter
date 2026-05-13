<?php
class Database {
    private static $instance = null;
    private $connection;

   private function __construct() {
    // Caminho direto para evitar erro de pasta
    $path = 'C:\copa-sustentavel-site\database.sqlite';
    
    if (!file_exists($path)) {
        die("ERRO: O arquivo nao esta em: " . $path);
    }
    $this->connection = new SQLite3($path);
}

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}

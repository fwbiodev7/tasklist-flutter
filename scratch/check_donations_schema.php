<?php
require_once __DIR__ . '/../core/Database.php';
$db = Database::getInstance();
$res = $db->query("PRAGMA table_info(donations)");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}

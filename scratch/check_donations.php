<?php
require_once __DIR__ . '/../core/Database.php';
$db = Database::getInstance();
$res = $db->query("SELECT * FROM donations LIMIT 5");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}

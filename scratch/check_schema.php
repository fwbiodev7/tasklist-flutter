<?php
require_once __DIR__ . '/../core/Database.php';
$db = Database::getInstance();

$tables = ['teams', 'donations', 'admins'];
foreach ($tables as $table) {
    echo "--- Table: $table ---\n";
    $res = $db->query("PRAGMA table_info($table)");
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        print_r($row);
    }
}

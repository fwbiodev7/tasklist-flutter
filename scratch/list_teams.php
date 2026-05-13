<?php
require_once __DIR__ . '/../core/Database.php';

$db = Database::getInstance();

echo "Current Teams:\n";
$res = $db->query("SELECT * FROM teams");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: {$row['id']} | Name: {$row['name']} | Points: {$row['total_points']}\n";
}

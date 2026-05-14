<?php
$db = new SQLite3('database.sqlite');
$res = $db->query('SELECT * FROM admins');
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}

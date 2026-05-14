<?php
$db = new SQLite3('database.sqlite');
$teams = [];
$res = $db->query('SELECT * FROM teams');
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $teams[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'country' => $row['country'],
        'total_points' => (int)$row['total_points']
    ];
}

$donations = [];
$res = $db->query('SELECT * FROM donations');
while($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $donations[] = [
        'id' => (int)$row['id'],
        'team_id' => (int)$row['team_id'],
        'team_name' => $row['team_name'],
        'material_type' => $row['material_type'],
        'quantity' => (float)$row['quantity'],
        'points_awarded' => (int)$row['points_awarded'],
        'created_at' => $row['created_at']
    ];
}

echo json_encode(['teams' => $teams, 'donations' => $donations], JSON_PRETTY_PRINT);

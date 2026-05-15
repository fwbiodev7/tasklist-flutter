<?php
// Utilitário de extração — usa MySQL (XAMPP)
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

$teams = [];
$res   = $db->query('SELECT * FROM teams ORDER BY total_points DESC');
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $teams[] = [
        'id'           => (int) $row['id'],
        'name'         => $row['name'],
        'country'      => $row['country'],
        'total_points' => (int) $row['total_points'],
    ];
}

$donations = [];
$res       = $db->query('SELECT d.*, t.name AS team_name FROM donations d JOIN teams t ON d.team_id = t.id ORDER BY d.created_at DESC');
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
    $donations[] = [
        'id'             => (int) $row['id'],
        'team_id'        => (int) $row['team_id'],
        'team_name'      => $row['team_name'],
        'material_type'  => $row['material_type'],
        'quantity'       => (float) $row['quantity'],
        'points_awarded' => (int) $row['points_awarded'],
        'created_at'     => $row['created_at'],
    ];
}

header('Content-Type: application/json');
echo json_encode(['teams' => $teams, 'donations' => $donations], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

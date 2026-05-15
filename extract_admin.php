<?php
// Utilitário de extração de admins — usa MySQL (XAMPP)
require_once __DIR__ . '/core/Database.php';

$db  = Database::getInstance();
$res = $db->query('SELECT id, username, created_at FROM admins');

header('Content-Type: application/json');
echo json_encode($res->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT);

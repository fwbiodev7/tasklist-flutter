<?php
require_once __DIR__ . '/../core/Database.php';

$db = Database::getInstance();

$updates = [
    1 => ['name' => '2º LOG', 'country' => 'Brasil'],
    2 => ['name' => '2º ELE', 'country' => 'México'],
    3 => ['name' => '1º LOG', 'country' => 'Estados Unidos'],
    4 => ['name' => '3º SIST', 'country' => 'Nova Zelândia'],
    5 => ['name' => '1º ELE', 'country' => 'Marrocos'],
    6 => ['name' => '2º PROP', 'country' => 'França'],
    7 => ['name' => '1º SIST', 'country' => 'Portugal'],
    8 => ['name' => '2º SIST', 'country' => 'Alemanha'],
    9 => ['name' => '3º PROP', 'country' => 'Inglaterra'],
    10 => ['name' => '1º INF', 'country' => 'Espanha'],
    11 => ['name' => '3º LOG', 'country' => 'Catar'],
    12 => ['name' => '3º ELE', 'country' => 'Coreia do Sul']
];

foreach ($updates as $id => $data) {
    $stmt = $db->prepare("UPDATE teams SET name = :n, country = :c WHERE id = :id");
    $stmt->bindValue(':n', $data['name']);
    $stmt->bindValue(':c', $data['country']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    echo "Fixed ID $id: {$data['name']} ({$data['country']})\n";
}

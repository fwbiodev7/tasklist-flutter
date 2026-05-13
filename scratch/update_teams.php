<?php
require_once __DIR__ . '/../core/Database.php';

$db = Database::getInstance();

$updates = [
    '2º LOG' => 'Brasil (2º LOG)',
    '2º ELE' => 'México (2º ELE)',
    '1º LOG' => 'Estados Unidos (1º LOG)',
    '3º SIST' => 'Nova Zelândia (3º SIST)',
    '1º ELE' => 'Marrocos (1º ELE)',
    '2º PROP' => 'França (2º PROP)',
    '1º SIST' => 'Portugal (1º SIST)',
    '2º SIST' => 'Alemanha (2º SIST)',
    '3º PROP' => 'Inglaterra (3º PROP)',
    '1º INF' => 'Espanha (1º INF)',
    '3º LOG' => 'Catar (3º LOG)',
    '3º ELE' => 'Coreia do Sul (3º ELE)'
];

foreach ($updates as $oldName => $newName) {
    $stmt = $db->prepare("UPDATE teams SET name = :newName WHERE name = :oldName");
    $stmt->bindValue(':newName', $newName);
    $stmt->bindValue(':oldName', $oldName);
    $stmt->execute();
    echo "Updated $oldName to $newName\n";
}

<?php
session_start();

function calculatePoints($type, $quantity) {
    switch ($type) {
        case 'higiene': return $quantity * 2;
        case 'vestuario': return $quantity * 5;
        case 'leite': return $quantity * 10;
        case 'reciclável': return floor($quantity / 3) * 5;
        case 'lacre': return $quantity * 30;
        default: return 0;
    }
}

function checkLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: /login");
        exit;
    }
}
?>

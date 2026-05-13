<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$fullPath = __DIR__ . '/public' . $path;

if ($path !== '/' && file_exists($fullPath)) {
    return false;
}

$_GET['url'] = trim($path, '/');

require_once __DIR__ . '/public/index.php';
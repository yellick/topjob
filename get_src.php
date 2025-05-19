<?php
// Определяем протокол
if (
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
) {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

$host = $_SERVER['HTTP_HOST'];
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$projectDepth = substr_count(str_replace('\\', '/', realpath(__DIR__)), '/');
$basePath = implode('/', array_slice(explode('/', $scriptPath), 0, -$projectDepth));
$baseUrl = rtrim("$protocol$host$basePath", '/') . '/';

// Локальный хост (если нужно)
if ($host === 'localhost' || $host === '127.0.0.1') {
    $baseUrl .= 'TopJob/';
}

define('BASE_URL', $baseUrl);
?>
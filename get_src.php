<?php
// Определяем протокол (HTTPS или HTTP)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
           || ($_SERVER['SERVER_PORT'] == 443)
           || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
$protocol = $isHttps ? 'https://' : 'http://';

// Определяем хост и локальное окружение
$host = $_SERVER['HTTP_HOST'];
$isLocalhost = in_array($host, ['localhost', '127.0.0.1']);

// Формируем базовый URL
define('BASE_URL', $protocol . $host . ($isLocalhost ? '/TopJob' : '') . '/');
?>
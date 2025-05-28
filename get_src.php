<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$isLocalhost = in_array($host, ['localhost', '127.0.0.1']);

define('BASE_URL', $protocol . $host . ($isLocalhost ? '/TopJob' : '') . '/');
?>
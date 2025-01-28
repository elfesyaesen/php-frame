<?php
session_start(); ob_start();
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'https';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . '://' . $host . '/';

//site name 
define('APP_URL', $base_url);

define('APP_ROOT', dirname(__FILE__));
define('APP_SUBFOLDER', '/php-frame');
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');
define('DB_CHARSET', 'utf8');
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
]);

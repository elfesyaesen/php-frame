<?php
session_start();
ob_start();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$scriptPath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$base_url = $protocol . "://" . $host . ($scriptPath === '/' ? '' : $scriptPath);

define('APP_URL', $base_url);
define('APP_ROOT', dirname(__FILE__));
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

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/system/engine/Autoloader.php';
require_once __DIR__ . '/system/routing/Router.php';


use System\Engine\Autoloader;
use System\Routing\Router;

$autoloader = new Autoloader();
$router     = new Router();
$router->name('catalog.index')->get('/', [App\Catalog\Controller\BaseController::class, 'index']);

$router->name('dashboard.index')->get('/dashboard', [App\Admin\Controller\BaseController::class, 'index']);


$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

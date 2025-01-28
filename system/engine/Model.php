<?php

namespace System\Engine;

use PDO;
use PDOException;

class Model
{
    protected $pdo = null;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('mysql:host=' . \DB_HOST . ';dbname=' . \DB_NAME, \DB_USER, \DB_PASS, \DB_OPTIONS);
        } catch (PDOException $e) {
            error_log('Veritabanı hatası: ' . $e->getMessage() . PHP_EOL, 3, \APP_ROOT . '/error_log.php');
            die($e->getMessage());
        }
    }
}

<?php

namespace System\Engine;

class Autoloader
{
    public function __construct()
    {
        spl_autoload_register(function (string $class): void {
            $this->load($class);
        });

        spl_autoload_extensions('.php');
    }

    public function load(string $class): bool
    {
        $parts = explode('\\', $class);

        $className = array_pop($parts);

        $namespacePath = strtolower(implode(DIRECTORY_SEPARATOR, $parts));

        $filePath =  APP_ROOT . '/' . $namespacePath . DIRECTORY_SEPARATOR . $className . '.php';

        if (is_file($filePath)) {
            require_once($filePath);
            return true;
        } else {
            return false;
        }
    }
}

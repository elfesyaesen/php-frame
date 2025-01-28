<?php

namespace System\Middleware;

class MiddlewareAlias
{
    private static array $middlewareMap = [
        'role' => RoleMiddleware::class,
        'permission' => PermissionMiddleware::class,
    ];

    public static function run(string $middleware): bool
    {
        try {
            [$name, $parameter] = array_pad(explode(':', $middleware, 2), 2, null);

            if (!isset(self::$middlewareMap[$name])) {
                error_log("Middleware $name : bulunamadı !");
                return false;
            }

            $middlewareClass = self::$middlewareMap[$name];
            $instance = new $middlewareClass();

            if (!$instance instanceof MiddlewareInterface) {
                error_log("Handle methodu middlewarede bulunmak zorunda !");
                return false;
            }

            return $instance->handle($parameter);
        } catch (\Throwable $e) {
            error_log("Middleware Hatası : " . $e->getMessage());
            return false;
        }
    }
}

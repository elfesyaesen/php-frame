<?php

namespace System\Middleware;

use System\Engine\Controller;

class RoleMiddleware extends Controller implements MiddlewareInterface
{
    public function handle(?string $parameter): bool
    {
        $roles = explode(',', $parameter);
        $hasRole = in_array($_SESSION['role_name'] ?? '', $roles);

        if (!$hasRole) {
            header('location: ' . APP_URL . 'unauthorization');
            return false;
        }

        return true;
    }
}

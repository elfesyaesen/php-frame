<?php

namespace System\Middleware;

use System\Engine\Controller;

class PermissionMiddleware extends Controller implements MiddlewareInterface
{
    public function handle(?string $parameter): bool
    {
        $permissions = explode(',', $parameter);
        $hash = in_array($_SESSION['permission_name'] ?? '', $permissions);

        if (!$hash) {
            header('location: ' . APP_URL . 'unauthorization');
            return false;
        }

        return true;
    }
}

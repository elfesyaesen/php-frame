<?php

namespace System\Middleware;

interface MiddlewareInterface
{
    public function handle(?string $parameter): bool;
}

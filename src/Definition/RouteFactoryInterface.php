<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouteFactoryInterface
{
    /**
     * Creates a route instance.
     */
    public function createRoute(string $name, string $methods, string $path, mixed $target): RouteInterface;
}

<?php declare(strict_types=1);


namespace AlanVdb\Router\Definition;


interface RouteFactoryInterface
{
    /**
     * Creates a route instance.
     *
     * @param string $path
     * @param array $methods
     * @return RouteInterface
     */
    public function createRoute(string $methods, string $path, callable $target): RouteInterface;
}

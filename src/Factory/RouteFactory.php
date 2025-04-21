<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Route;

class RouteFactory implements RouteFactoryInterface
{
    public function createRoute(string $name, string $methods, string $path, mixed $target): RouteInterface
    {
        return new Route($name, $methods, $path, $target);
    }
}
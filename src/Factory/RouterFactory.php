<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouterFactoryInterface;
use AlanVdb\Router\Definition\RouterInterface;
use AlanVdb\Router\Router;

class RouterFactory implements RouterFactoryInterface
{
    public function createRouter(array $routes) : RouterInterface
    {
        return new Router($routes);
    }
}

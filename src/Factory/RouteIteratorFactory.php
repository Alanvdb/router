<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RouteIteratorFactoryInterface;
use AlanVdb\Router\RouteIterator;

class RouteIteratorFactory implements RouteIteratorFactoryInterface
{
    public function createRouteIterator(RouteInterface ...$routes): RouteIteratorInterface
    {
        return new RouteIterator(...$routes);
    }
}

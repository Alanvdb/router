<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use AlanVdb\Dependency\Definition\LazyContainerInterface;

interface RouteCollectionFactoryInterface
{
    public function createRouteCollection(): RouteIteratorInterface & LazyContainerInterface;
}

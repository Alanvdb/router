<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouteIteratorFactoryInterface
{
    public function createRouteIterator(RouteInterface ...$routes): RouteIteratorInterface;
}

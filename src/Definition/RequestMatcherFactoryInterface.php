<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use Psr\Container\ContainerInterface;

interface RequestMatcherFactoryInterface
{
    /**
     * 
     */
    public function createRequestMatcher(
        RouteIteratorInterface & ContainerInterface $routeCollection
    ) : RequestMatcherInterface;
}

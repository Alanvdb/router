<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

interface RequestMatcherFactoryInterface
{
    /**
     * 
     */
    public function createRequestMatcher(
        RouteIteratorInterface & LazyContainerInterface $routeCollection
    ) : MiddlewareInterface;
}

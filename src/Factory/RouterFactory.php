<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\RouteIteratorFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;

use AlanVdb\Router\Route;
use AlanVdb\Router\RouteIterator;
use AlanVdb\Router\RequestMatcher;
use AlanVdb\Router\UriGenerator;

class RouterFactory
    implements
        RouteFactoryInterface,
        RouteIteratorFactoryInterface,
        RequestMatcherFactoryInterface,
        UriGeneratorFactoryInterface
{
    /**
     * Creates a route instance.
     * 
     * @param string $methods like "GET" or "GET|POST"
     * @param string $path
     * @param mixed $target
     * @return RouteInterface
     */
    public function createRoute(string $name, string $methods, string $path, mixed $target): RouteInterface
    {
        return new Route($name, $methods, $path, $target);
    }

    /**
     * Creates a Iterator of routes.
     *
     * @return RouteIteratorInterface
     */
    public function createRouteIterator(RouteInterface ...$routes): RouteIteratorInterface
    {
        return new RouteIterator(...$routes);
    }

    /**
     * 
     */
    public function createRequestMatcher(RouteIteratorInterface $routes): RequestMatcherInterface
    {
        return new RequestMatcher($routes);
    }

    /**
     * 
     */
    public function createUriGenerator(RouteIteratorInterface $routes): UriGeneratorInterface
    {
        return new UriGenerator($routes);
    }
}

<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\RouteCollectionFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use Psr\Http\Server\MiddlewareInterface;

use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Middleware\RequestMatcher;
use AlanVdb\Router\UriGenerator;

class RouterFactory
    implements
        RouteFactoryInterface,
        RouteCollectionFactoryInterface,
        RequestMatcherFactoryInterface,
        UriGeneratorFactoryInterface
{
    /**
     * Creates a route instance.
     * 
     * @param string $methods like "GET" or "GET|POST"
     * @param string $path
     * @param callable $target
     * @return RouteInterface
     */
    public function createRoute(string $methods, string $path, callable $target): RouteInterface
    {
        return new Route($methods, $path, $target);
    }

    /**
     * Creates a collection of routes.
     *
     * @return RouteIteratorInterface
     */
    public function createRouteCollection(): RouteIteratorInterface & LazyContainerInterface
    {
        return new RouteCollection();
    }

    /**
     * 
     */
    public function createRequestMatcher(RouteIteratorInterface & LazyContainerInterface $routeCollection): MiddlewareInterface
    {
        return new RequestMatcher($routeCollection);
    }

    /**
     * 
     */
    public function createUriGenerator(RouteIteratorInterface & LazyContainerInterface $routeCollection): UriGeneratorInterface
    {
        return new UriGenerator($routeCollection);
    }
}

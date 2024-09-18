<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\RouteCollectionFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use Psr\Container\ContainerInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;

use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\RequestMatcher;
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
     * @param mixed $target
     * @return RouteInterface
     */
    public function createRoute(string $name, string $methods, string $path, mixed $target): RouteInterface
    {
        return new Route($name, $methods, $path, $target);
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
    public function createRequestMatcher(RouteIteratorInterface & ContainerInterface $routeCollection): RequestMatcherInterface
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

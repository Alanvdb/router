<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouterInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\RouteIteratorFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Exception\InvalidRouterParamProvided;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    protected RouteIteratorInterface $routes;
    protected UriGeneratorFactoryInterface $uriGeneratorFactory;
    protected RequestMatcherFactoryInterface $requestMatcherFactory;
    protected ?UriGeneratorInterface $uriGenerator = null;
    protected ?RequestMatcherInterface $requestMatcher = null;

    public function __construct(
        array $routes,
        ?RouteFactoryInterface $routeFactory = null,
        ?RouteIteratorFactoryInterface $routeIteratorFactory = null,
        ?UriGeneratorFactoryInterface $uriGeneratorFactory = null,
        ?RequestMatcherFactoryInterface $requestMatcherFactory = null,
        bool $eagerLoad = false
    ) {
        $this->initializeServices($routes, $routeFactory, $routeIteratorFactory, $uriGeneratorFactory, $requestMatcherFactory);
        if ($eagerLoad) {
            $this->getUriGenerator();
            $this->getRequestMatcher();
        }
    }

    public function getUriGenerator(): UriGeneratorInterface
    {
        if ($this->uriGenerator === null) {
            $this->uriGenerator = $this->uriGeneratorFactory->createUriGenerator($this->routes);
        }
        return $this->uriGenerator;
    }

    public function getRequestMatcher(): RequestMatcherInterface
    {
        if ($this->requestMatcher === null) {
            $this->requestMatcher = $this->requestMatcherFactory->createRequestMatcher($this->routes);
        }
        return $this->requestMatcher;
    }

    public function generateUri(string $name, array $params = []): string
    {
        return $this->getUriGenerator()->generateUri($name, $params);
    }

    public function matchRequest(ServerRequestInterface $request): RouteInterface
    {
        return $this->getRequestMatcher()->matchRequest($request);
    }

    private function initializeServices(
        array $routes,
        ?RouteFactoryInterface $routeFactory,
        ?RouteIteratorFactoryInterface $routeIteratorFactory,
        ?UriGeneratorFactoryInterface $uriGeneratorFactory,
        ?RequestMatcherFactoryInterface $requestMatcherFactory
    ): void {
        if (empty($routes)) {
            throw new InvalidRouterParamProvided('No routes provided to the router.');
        }
        if (!array_is_list($routes)) {
            throw new InvalidRouterParamProvided('Routes must be provided as a list.');
        }
        $this->uriGeneratorFactory = $uriGeneratorFactory ?? new \AlanVdb\Router\Factory\UriGeneratorFactory();
        $this->requestMatcherFactory = $requestMatcherFactory ?? new \AlanVdb\Router\Factory\RequestMatcherFactory();
        $routeFactory = $routeFactory ?? new \AlanVdb\Router\Factory\RouteFactory();
        $routeIteratorFactory = $routeIteratorFactory ?? new \AlanVdb\Router\Factory\RouteIteratorFactory();
        $routeCollection = [];

        foreach ($routes as $routeParams) {
            if (!is_array($routeParams) || count($routeParams) < 4) {
                throw new InvalidRouterParamProvided('Route parameters must be an array of 4 values.');
            }
            $routeCollection[] = $routeFactory->createRoute(...$routeParams);
        }
        $this->routes = $routeIteratorFactory->createRouteIterator(...$routeCollection);
    }
}

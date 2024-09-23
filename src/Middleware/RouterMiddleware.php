<?php declare(strict_types=1);

namespace AlanVdb\Router\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AlanVdb\Router\Definition\RouteFactoryInterface;
use AlanVdb\Router\Definition\RouteIteratorFactoryInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;
use AlanVdb\Router\Exception\InvalidRouteParamProvided;

class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected array $routeParams,
        protected RouteFactoryInterface & RouteIteratorFactoryInterface & RequestMatcherFactoryInterface & UriGeneratorFactoryInterface $routerFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeParams = $this->routeParams;

        if (empty($routeParams)) {
            throw new InvalidRouteParamProvided('No route params provided.');
        }
        $routes = [];

        foreach ($routeParams as $params) {

            if (!is_array($params) || empty($params)) {
                throw new InvalidRouteParamProvided('Empty route params array provided.');
            }
            $routes[] = $this->routerFactory->createRoute(...$params);
        }
        $routeIterator = $this->routerFactory->createRouteIterator(...$routes);

        $matcher = $this->routerFactory->createRequestMatcher($routeIterator);
        $matchedRoute = $matcher->matchRequest($request);

        return $handler->handle(
            $request
                ->withAttribute('matchedRoute', $matchedRoute)
                ->withAttribute('uriGenerator', $this->routerFactory->createUriGenerator($routeIterator))
        );
    }
}

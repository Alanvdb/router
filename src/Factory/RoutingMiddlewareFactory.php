<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Middleware\RoutingMiddleware;
use Psr\Http\Server\MiddlewareInterface;
use AlanVdb\Router\Definition\RoutingMiddlewareFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use AlanVdb\Router\Definition\RouterInterface;

class RoutingMiddlewareFactory implements RoutingMiddlewareFactoryInterface
{
    public function createRoutingMiddleware(
        RouterInterface $router,
        ResponseFactoryInterface $responseFactory
    ): MiddlewareInterface {
        return new RoutingMiddleware($router, $responseFactory);
    }
}

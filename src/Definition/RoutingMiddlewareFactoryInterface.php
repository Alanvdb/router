<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use psr\http\server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;

interface RoutingMiddlewareFactoryInterface
{
    public function createRoutingMiddleware(
        RouterInterface $router,
        ResponseFactoryInterface $responseFactory
    ): MiddlewareInterface;
}

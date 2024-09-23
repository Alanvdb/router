<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use Psr\Http\Server\MiddlewareInterface;

interface RouterMiddlewareFactoryInterface
{
    public function createRouterMiddleware(array $routeParams) : MiddlewareInterface;
}

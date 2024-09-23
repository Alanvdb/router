<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouterMiddlewareFactoryInterface
{
    public function createRouterMiddleware(array $routeParams) : MiddlewareInterface;
}

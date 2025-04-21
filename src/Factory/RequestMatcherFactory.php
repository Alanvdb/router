<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;
use AlanVdb\Router\Definition\RequestMatcherFactoryInterface;
use AlanVdb\Router\RequestMatcher;

class RequestMatcherFactory implements RequestMatcherFactoryInterface
{
    public function createRequestMatcher(RouteIteratorInterface $routes): RequestMatcherInterface
    {
        return new RequestMatcher($routes);
    }
}
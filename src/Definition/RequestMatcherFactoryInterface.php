<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RequestMatcherFactoryInterface
{
    public function createRequestMatcher(RouteIteratorInterface $routes) : RequestMatcherInterface;
}

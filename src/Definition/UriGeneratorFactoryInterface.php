<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface UriGeneratorFactoryInterface
{
    public function createUriGenerator(RouteIteratorInterface $routes) : UriGeneratorInterface;
}

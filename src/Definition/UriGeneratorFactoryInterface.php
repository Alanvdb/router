<?php declare(strict_types=1);


namespace AlanVdb\Router\Definition;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;

interface UriGeneratorFactoryInterface
{
    /**
     * 
     */
    public function createUriGenerator(RouteIteratorInterface & LazyContainerInterface $routeCollection) : UriGeneratorInterface;
}

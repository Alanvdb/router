<?php declare(strict_types=1);

namespace AlanVdb\Router\Factory;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\UriGeneratorFactoryInterface;
use AlanVdb\Router\UriGenerator;

class UriGeneratorFactory implements UriGeneratorFactoryInterface
{
    public function createUriGenerator(RouteIteratorInterface $routes): UriGeneratorInterface
    {
        return new UriGenerator($routes);
    }
}

<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use AlanVdb\Router\Definition\RouterInterface;

interface RouterFactoryInterface
{
    public function createRouter(array $routes) : RouterInterface;
}

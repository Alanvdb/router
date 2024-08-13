<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use Psr\Http\Message\ServerRequestInterface;
use AlanVdb\Router\Definition\RouteInterface;

interface RequestMatcherInterface
{
    /**
     * 
     */
    public function matchRequest(ServerRequestInterface $request) : RouteInterface;
}

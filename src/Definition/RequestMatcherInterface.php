<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use Psr\Http\Message\ServerRequestInterface;

interface RequestMatcherInterface
{
    public function matchRequest(ServerRequestInterface $request) : RouteInterface;
}

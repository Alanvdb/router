<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use Iterator;

interface RouteIteratorInterface extends Iterator
{
    /* @throws RouteNameNotFound */
    public function get(string $routeName) : RouteInterface;

    public function has(string $name) : bool;

    public function current(): RouteInterface;

    public function key(): string;
}

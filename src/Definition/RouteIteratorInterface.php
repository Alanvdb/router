<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;


use Iterator;
use Countable;

interface RouteIteratorInterface extends Iterator, Countable
{
    public function __construct(RouteInterface ...$routes);

    public function current(): RouteInterface;

    public function next(): void;

    public function rewind(): void;

    public function valid(): bool;

    public function count(): int;

    public function key(): string;
    /* @throws RouteNameNotFound */
    public function get(string $routeName) : RouteInterface;

    public function has(string $name) : bool;
}

<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

use Iterator;

interface RouteIteratorInterface extends Iterator
{
    /**
     * Return the current element.
     *
     * @return RouteInterface
     */
    public function current(): RouteInterface;

    /**
     * Return the key of the current element.
     *
     * @return string
     */
    public function key(): string;
}

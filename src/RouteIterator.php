<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RouteInterface;

use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Router\Exception\InvalidRouteCollectionParamProvided;
use AlanVdb\Router\Exception\RouteNameNotFound;
use Throwable;

class RouteIterator implements RouteIteratorInterface
{
    protected array $routes = [];
    protected array $offsets = [];
    protected int $currentOffset = 0;

    public function __construct(RouteInterface ...$routes)
    {
        foreach ($routes as $route) {
            $routeName = $route->getName();
            $this->routes[$routeName] = $route;
            $this->offsets[] = $routeName;
        }
    }

    /**
     * Retrieves a route by its name.
     *
     * @param string $name The name of the route.
     * @return RouteInterface The route associated with the given name.
     * @throws RouteNameNotFound If the route name is not found in the collection.
     */
    public function get(string $name): RouteInterface
    {
        if (!array_key_exists($name, $this->routes)) {
            throw new RouteNameNotFound("Route name '$name' not found in collection.", 0, $e);
        }
        return $this->routes[$name];
    }

    public function has(string $name) : bool
    {
        return array_key_exists($name, $this->routes);
    }

    // Iteration methods

    public function current(): RouteInterface
    {
        return $this->routes[$this->key()];
    }

    public function key(): string
    {
        return $this->offsets[$this->currentOffset];
    }

    public function next() : void
    {
        $this->currentOffset++;
    }

    public function rewind() : void
    {
        $this->currentOffset = 0;
    }

    public function valid(): bool
    {
        return array_key_exists($this->currentOffset, $this->offsets);
    }
}

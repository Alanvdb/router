<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;

use AlanVdb\Dependency\IterableLazyContainer;

use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Router\Exception\InvalidRouteCollectionParamProvided;
use AlanVdb\Router\Exception\RouteNameNotFound;
use Throwable;

class RouteCollection
    extends IterableLazyContainer
    implements RouteIteratorInterface, LazyContainerInterface
{
    /**
     * Retrieves a route by its name.
     *
     * @param string $name The name of the route.
     * @return RouteInterface The route associated with the given name.
     * @throws RouteNameNotFound If the route name is not found in the collection.
     */
    public function get(string $name): RouteInterface
    {
        try {
            return parent::get($name);
        } catch (NotFoundExceptionInterface $e) {
            throw new RouteNameNotFound("Route name '$name' not found in collection.", 0, $e);
        }
    }

    /**
     * Returns the current route.
     *
     * @return RouteInterface The current route.
     */
    public function current(): RouteInterface
    {
        return parent::current();
    }

    /**
     * Returns the name of the current route.
     *
     * @return string The name of the current route.
     */
    public function key(): string
    {
        return parent::key();
    }
}

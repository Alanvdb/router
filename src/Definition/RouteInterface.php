<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouteInterface
{
    /**
     * Gets the name of the route.
     *
     * @return string The name of the route.
     */
    public function getName(): string;

    /**
     * Gets the path of the route.
     *
     * @return string The path of the route.
     */
    public function getPath(): string;

    /**
     * Gets the HTTP methods allowed for the route.
     *
     * @return string[] An array of allowed HTTP methods.
     */
    public function getMethods(): array;

    /**
     * Gets the parameters for the route.
     *
     * @return array An associative array of parameters for the route.
     */
    public function getParams(): array;

    /**
     * Sets the parameters for the route.
     *
     * @param array $params An associative array of parameters to set.
     * @return self
     *
     * @throws InvalidRouteParamProvided If any parameter key is not a string.
     */
    public function setParams(array $params): self;

    /**
     * Returns the target callable to execute `$route->getTarget($route->getParams())`.
     *
     * @return mixed The target action associated with the route.
     */
    public function getTarget(): mixed;
}

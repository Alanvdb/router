<?php declare(strict_types=1);


namespace AlanVdb\Router\Definition;


interface RouteInterface
{
    /**
     * Gets the path of the route.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Gets the HTTP methods allowed for the route.
     *
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * Gets the parameters for the route.
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Sets the parameters for the route.
     *
     * @param array $params
     * @return self
     */
    public function setParams(array $params): self;
}

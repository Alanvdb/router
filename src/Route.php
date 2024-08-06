<?php declare(strict_types=1);


namespace AlanVdb\Router;


use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Throwable\InvalidRouteParamProvided;
use InvalidArgumentException;


class Route implements RouteInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string[]
     */
    private $methods;

    /**
     * @var array
     */
    private $params = [];

    /**
     * Route constructor.
     *
     * @param string $methods
     * @param string $path
     * @throws InvalidRouteParamProvided
     */
    public function __construct(string $methods, string $path)
    {
        if (empty($path)) {
            throw new InvalidRouteParamProvided("Path cannot be empty.");
        }
        if (!preg_match('/^\/[a-zA-Z0-9\/{}]+$/', $path)) {
            throw new InvalidRouteParamProvided("Path must be a valid regex pattern with variables, e.g., /post/{slug}.");
        }
        if (empty($methods)) {
            throw new InvalidRouteParamProvided("Methods cannot be empty.");
        }
        $methods = explode('|', $methods);

        $this->path = $path;
        $this->methods = $methods;
    }

    /**
     * Gets the path of the route.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Gets the HTTP methods allowed for the route.
     *
     * @return string[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Gets the parameters for the route.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Sets the parameters for the route.
     *
     * @param array $params
     * @return self
     * @throws InvalidRouteParamProvided
     */
    public function setParams(array $params): self
    {
        foreach ($params as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidRouteParamProvided("Parameter keys must be strings.");
            }
        }
        $this->params = $params;
        return $this;
    }
}

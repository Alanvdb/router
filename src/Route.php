<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Exception\InvalidRouteParamProvided;
use InvalidArgumentException;

/**
 * The Route class represents a specific route within a web application.
 * It defines the route's name, path, allowed HTTP methods, and the target
 * action to be executed when the route is matched.
 */
class Route implements RouteInterface
{
    private string $name;
    private string $path;
    private array $methods;
    private array $params = [];

    /**
     * @var mixed $target
     */
    private $target;

    /**
     * Constructs a new Route instance.
     *
     * @param string   $name    The name of the route.
     * @param string   $methods The HTTP methods allowed for the route, separated by '|'.
     * @param string   $path    The path of the route, which may contain parameters.
     * @param mixed $target  The target action to execute when the route is matched.
     *
     * @throws InvalidRouteParamProvided If any of the parameters are invalid.
     */
    public function __construct(string $name, string $methods, string $path, mixed $target)
    {
        foreach (['name', 'path', 'methods'] as $varName) {
            if (empty($$varName)) {
                throw new InvalidRouteParamProvided("${$varName} argument cannot be empty.");
            }
        }

        if (!preg_match('/^\/[a-zA-Z0-9\/{}\-\_]*$/', $path)) {
            throw new InvalidRouteParamProvided("Route path must be a valid regex pattern with variables, e.g., /post/{slug}. Path provided : '$path'.");
        }

        $this->name    = $name;
        $this->methods = explode('|', $methods);
        $this->path    = $path;
        $this->target  = $target;
    }

    /**
     * Gets the name of the route.
     *
     * @return string The name of the route.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the path of the route.
     *
     * @return string The path of the route.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Gets the HTTP methods allowed for the route.
     *
     * @return string[] An array of allowed HTTP methods.
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Gets the parameters for the route.
     *
     * @return array An associative array of parameters for the route.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Sets the parameters for the route.
     *
     * @param array $params An associative array of parameters to set.
     * @return self
     *
     * @throws InvalidRouteParamProvided If any parameter key is not a string.
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

    /**
     * Calls the target action to execute when the route is matched.
     *
     * @return mixed The target action associated with the route.
     */
    public function getTarget(): mixed
    {
        return $this->target;
    }
}

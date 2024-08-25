<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use Psr\Container\ContainerInterface;

use AlanVdb\Router\Exception\RouteNameNotFound;

class UriGenerator implements UriGeneratorInterface
{
    /**
     * @var RouteIteratorInterface&ContainerInterface $routes
     */
    protected RouteIteratorInterface&ContainerInterface $routeCollection;

    /**
     * UriGenerator constructor.
     *
     * @param RouteIteratorInterface&ContainerInterface $routes
     */
    public function __construct(RouteIteratorInterface & ContainerInterface $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * Generate a URI based on the route name and variables.
     *
     * @param string $name The name of the route.
     * @param array<string, mixed> $vars An associative array of variables to replace in the URI.
     * 
     * @return string The generated URI.
     *
     * @throws RouteNameNotFound If the route name is not found in the collection.
     */
    public function generateUri(string $name, array $vars = []): string
    {
        if (!$this->routeCollection->has($name)) {
            throw new RouteNameNotFound("Provided route name not found in Route collection : '$name'.");
        }
        
        $uri = $this->routeCollection->get($name)->getPath();

        foreach ($vars as $varName => $value) {
            $pattern = sprintf('/\{%s\s*\}/', preg_quote($varName, '/'));
            $uri = preg_replace($pattern, (string) $value, $uri);
        }

        return $uri;
    }
}
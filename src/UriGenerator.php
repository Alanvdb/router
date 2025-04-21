<?php declare(strict_types=1);

namespace AlanVdb\Router;

use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Exception\RouteNameNotFound;
use AlanVdb\Router\Exception\InvalidUriGeneratorParamProvided;

class UriGenerator implements UriGeneratorInterface
{
    protected RouteIteratorInterface $routes;

    public function __construct(RouteIteratorInterface $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Make the instance callable as a shortcut to generateUri()
     */
    public function __invoke(string $name, array $vars = []): string
    {
        return $this->generateUri($name, $vars);
    }

    public function generateUri(string $name, array $vars = []): string
    {
        if (!$this->routes->has($name)) {
            throw new RouteNameNotFound("Provided route name not found in Route collection : '$name'.");
        }

        $route = $this->routes->get($name);
        $uri = $route->getPath();
        
        preg_match_all('/\{([^{}]+)\}/', $uri, $matches);
        $pathVars = array_map('trim', $matches[1]);
        $pathVars = array_unique($pathVars);
        
        $missingVars = array_diff($pathVars, array_keys($vars));
        if (!empty($missingVars)) {
            throw new InvalidUriGeneratorParamProvided(
                "Missing required variables for route '$name': " . implode(', ', $missingVars) . '.'
            );
        }

        foreach ($vars as $varName => $value) {
            $pattern = sprintf('/\{\s*%s\s*\}/', preg_quote($varName, '/'));
            $uri = preg_replace($pattern, (string) $value, $uri);
        }

        return $uri;
    }
}

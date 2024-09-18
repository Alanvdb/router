<?php declare(strict_types=1);

namespace AlanVdb\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;
use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;

use AlanVdb\Router\Exception\RouteNotFound;
use AlanVdb\Router\Exception\MethodNotAllowed;

class RequestMatcher implements RequestMatcherInterface
{
    protected RouteIteratorInterface $routes;

    public function __construct(RouteIteratorInterface $routes)
    {
        $this->routes = $routes;
    }

    public function matchRequest(ServerRequestInterface $request) : RouteInterface
    {
        $path             = $request->getUri()->getPath();
        $method           = $request->getMethod();
        $methodNotAllowed = false;

        foreach ($this->routes as $route) {

            if ($this->isPathMatch($route, $path)) {    

                if ($this->isMethodMatch($route, $method)) {
                    return $route;
                } else {
                    $methodNotAllowed = true;
                }
            }
        }

        throw $methodNotAllowed
            ? new MethodNotAllowed("Method not allowed for the requested route.", 405)
            : new RouteNotFound("No route found for the current request.", 404);
    }

    protected function isPathMatch(RouteInterface $route, string $path): bool
    {
        $pattern = $this->convertPathToRegex($route->getPath());
        if (preg_match($pattern, $path, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $route->setParams($params);
            return true;
        }

        return false;
    }

    protected function isMethodMatch(RouteInterface $route, string $method): bool
    {
        return in_array($method, $route->getMethods());
    }

    /**
     * Converts a route path to a regex pattern for matching.
     *
     * @param string $path
     * @return string
     */
    protected function convertPathToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        return "@^" . $pattern . "$@";
    }
}

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
    /**
     * @var RouteIteratorInterface $routeCollection
     */
    private $routeCollection;

    /**
     * @param RouteIteratorInterface $routeCollection
     */
    public function __construct(RouteIteratorInterface $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * 
     */
    public function matchRequest(ServerRequestInterface $request) : RouteInterface
    {
        $path             = $request->getUri()->getPath();
        $method           = $request->getMethod();
        $methodNotAllowed = false;

        foreach ($this->routeCollection as $route) {

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

    /**
     * Determines if the given route path matches the current request path.
     *
     * @param RouteInterface $route
     * @param string $path
     * @return bool
     */
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

    /**
     * Determines if the given route method matches the current request method.
     *
     * @param RouteInterface $route
     * @param string $method
     * @return bool
     */
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

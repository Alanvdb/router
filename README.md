# Router

A PHP PSR-7 Compatible Routing Library

## Overview

This project provides a simple yet flexible routing system for PHP, compatible with PSR-7 HTTP message interfaces. It is designed to help you manage your application routes efficiently, supporting lazy-loaded route definitions, path matching, request handling, and URI generation.

The main components of this routing system include:

1. **Route**: Represents a single route with methods, path, parameters, and a target action.
2. **RouteCollection**: Manages a collection of routes, enabling easy iteration and retrieval.
3. **RequestMatcher**: Matches incoming HTTP requests to the appropriate route.
4. **UriGenerator**: Generates URIs based on the routes defined in the collection.
5. **RouterFactory**: A factory for creating instances of the above components.

## Installation

To install the router, use Composer:

```bash
composer require alanvdb/router
```

## Usage

### Defining and Using Routes

The `Route` class allows you to define a route by specifying the HTTP methods, path, and a target action (e.g., a controller method). These routes can then be added to a `RouteCollection`.

#### Example

```php
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;

$route = new Route('home', 'GET', '/home', fn() => 'HomeController@index');
$routeCollection = new RouteCollection();
$routeCollection->add('home', fn() => $route);
```

### Matching Requests

The `RequestMatcher` class matches an incoming PSR-7 `ServerRequestInterface` to a route in the `RouteCollection`. If a matching route is found, it is returned; otherwise, an appropriate exception is thrown.

#### Example

```php
use AlanVdb\Router\RequestMatcher;
use Psr\Http\Message\ServerRequestInterface;

$requestMatcher = new RequestMatcher($routeCollection);
try {
    $matchedRoute = $requestMatcher->matchRequest($request);
    // Handle the matched route
} catch (MethodNotAllowed $e) {
    // Handle method not allowed
} catch (RouteNotFound $e) {
    // Handle route not found
}
```

### Generating URIs

The `UriGenerator` class generates URIs based on the routes defined in your `RouteCollection`. This is useful for creating links within your application.

#### Example

```php
use AlanVdb\Router\UriGenerator;

$uriGenerator = new UriGenerator($routeCollection);
$uri = $uriGenerator->generate('home', ['id' => 1]);
echo $uri; // Outputs: /home/1
```

### Using the RouterFactory

The `RouterFactory` simplifies the creation of routes, route collections, request matchers, and URI generators.

#### Example

```php
use AlanVdb\Router\Factory\RouterFactory;

$factory = new RouterFactory();
$route = $factory->createRoute('home', 'GET', '/home', fn() => 'HomeController@index');
$routeCollection = $factory->createRouteCollection();
$routeCollection->add('home', fn() => $route);

$requestMatcher = $factory->createRequestMatcher($routeCollection);
$uriGenerator = $factory->createUriGenerator($routeCollection);
```

## API Documentation

### Route

#### Methods

- **`getName(): string`**: Returns the name of the route.
- **`getPath(): string`**: Returns the path of the route.
- **`getMethods(): array`**: Returns an array of allowed HTTP methods for the route.
- **`getParams(): array`**: Returns an associative array of parameters for the route.
- **`setParams(array $params): self`**: Sets the parameters for the route. Throws `InvalidRouteParamProvided` if a parameter key is not a string.
- **`getTarget(): mixed`**: Returns the target action (a callable) associated with the route.

### RouteCollection

#### Methods

- **`add(string $name, callable $route): void`**: Adds a new route to the collection.
- **`get(string $name): RouteInterface`**: Retrieves a route by its name.
- **`has(string $name): bool`**: Checks if a route with the given name exists in the collection.

### RequestMatcher

#### Methods

- **`matchRequest(ServerRequestInterface $request): RouteInterface`**: Matches an incoming request to a route. Throws `MethodNotAllowed` or `RouteNotFound` exceptions if no match is found.

### UriGenerator

#### Methods

- **`generate(string $name, array $params = []): string`**: Generates a URI based on a route name and parameters.

### RouterFactory

#### Methods

- **`createRoute(string $name, string $methods, string $path, callable $target): RouteInterface`**: Creates a new route instance.
- **`createRouteCollection(): RouteIteratorInterface & LazyContainerInterface`**: Creates a new route collection.
- **`createRequestMatcher(RouteIteratorInterface & LazyContainerInterface $routeCollection): RequestMatcherInterface`**: Creates a new request matcher.
- **`createUriGenerator(RouteIteratorInterface & LazyContainerInterface $routeCollection): UriGeneratorInterface`**: Creates a new URI generator.

## Testing

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

The tests are located in the `tests` directory and cover the functionality of the main components such as `Route`, `RouteCollection`, `RequestMatcher`, and `UriGenerator`.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

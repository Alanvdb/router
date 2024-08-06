# Router

A basic PHP application router.

## Overview

The `Router` library provides a simple and extensible routing system for PHP applications. It supports defining routes, matching incoming requests to routes, and generating URIs based on route names and parameters.

## Features

- Simple and easy-to-use API
- PSR compliant
- Supports various HTTP methods (GET, POST, PUT, DELETE, etc.)
- Dynamic route parameters
- Middleware support for request handling
- URI generation based on route names and parameters

## Installation

To install the `Router` library, use Composer:

```sh
composer require alanvdb/router
```

## Usage

Here is an example of how to use the `Router`:

```php
<?php

require 'vendor/autoload.php';

use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\UriGenerator;
use AlanVdb\Router\Middleware\RequestMatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\ResponseFactory;

$routeCollection = new RouteCollection();
$routeCollection->set('post.show', function() {
    return new Route('GET', '/post/{id}');
});

$requestMatcher = new RequestMatcher($routeCollection);
$uriGenerator = new UriGenerator($routeCollection);

$request = new ServerRequest('GET', '/post/123');

$responseFactory = new ResponseFactory();
$handler = new class($responseFactory) implements RequestHandlerInterface {
    private $responseFactory;
    public function __construct($responseFactory) {
        $this->responseFactory = $responseFactory;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface {
        return $this->responseFactory->createResponse(200)->withBody(new \GuzzleHttp\Psr7\Stream(fopen('php://temp', 'r+')));
    }
};

$response = $requestMatcher->process($request, $handler);
echo $response->getStatusCode(); // 200

$uri = $uriGenerator->generateUri('post.show', ['id' => 123]);
echo $uri; // /post/123
```

## Testing

To run the tests, use PHPUnit. Ensure you have PHPUnit installed and execute the following command:

```sh
vendor/bin/phpunit
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Issues and Feedback

If you encounter any issues or have feedback, please open an issue on the [GitHub repository](https://github.com/alanvdb/router/issues).

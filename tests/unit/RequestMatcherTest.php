<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AlanVdb\Router\Middleware\RequestMatcher;
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Throwable\RouteNotFound;
use AlanVdb\Router\Throwable\MethodNotAllowed;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\UriInterface;
use AlanVdb\Router\Definition\RouteInterface;

class TestableRequestMatcher extends RequestMatcher
{
    public function isPathMatchWrapper(RouteInterface $route, string $path): bool
    {
        return $this->isPathMatch($route, $path);
    }
}

class RequestMatcherTest extends TestCase
{
    public function testProcessWithMatchingRoute()
    {
        $route = new Route('GET', '/post/{id}', function () {});
        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/post/123');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->method('handle')->willReturn($this->createMock(ResponseInterface::class));

        $middleware = new RequestMatcher($routeCollection);
        $response = $middleware->process($request, $requestHandler);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        // Vérifie que les paramètres ont été extraits et définis correctement
        $this->assertSame(['id' => '123'], $route->getParams());
    }

    public function testProcessWithNonMatchingRouteThrowsRouteNotFound()
    {
        $this->expectException(RouteNotFound::class);

        $routeCollection = new RouteCollection();

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/invalid');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new RequestMatcher($routeCollection);
        $middleware->process($request, $requestHandler);
    }

    public function testProcessWithMethodNotAllowedThrowsMethodNotAllowed()
    {
        $this->expectException(MethodNotAllowed::class);

        $route = new Route('POST', '/post/{id}', function () {});
        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/post/123');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $requestHandler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new RequestMatcher($routeCollection);
        $middleware->process($request, $requestHandler);
    }

    public function testProcessWithMultipleMatchingRoutes()
    {
        $route1 = new Route('GET', '/post/{id}', function () {});
        $route2 = new Route('GET', '/post/{id}/edit', function () {});
        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route1) {
            return $route1;
        });
        $routeCollection->set('post.edit', function () use ($route2) {
            return $route2;
        });

        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/post/123');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler->method('handle')->willReturn($this->createMock(ResponseInterface::class));

        $middleware = new RequestMatcher($routeCollection);
        $response = $middleware->process($request, $requestHandler);

        $this->assertInstanceOf(ResponseInterface::class, $response);

        // Vérifie que les paramètres ont été extraits et définis correctement
        $this->assertSame(['id' => '123'], $route1->getParams());
    }

    public function testIsPathMatch()
    {
        $route = new Route('GET', '/post/{id}', function () {});
        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $middleware = new TestableRequestMatcher($routeCollection);

        $result = $middleware->isPathMatchWrapper($route, '/post/123');

        $this->assertTrue($result);
        $this->assertSame(['id' => '123'], $route->getParams());
    }

    public function testIsPathMatchWithNonMatchingPath()
    {
        $route = new Route('GET', '/post/{id}', function () {});
        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $middleware = new TestableRequestMatcher($routeCollection);

        $result = $middleware->isPathMatchWrapper($route, '/invalid/path');

        $this->assertFalse($result);
        $this->assertSame([], $route->getParams());
    }
}

<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router;

use AlanVdb\Router\RequestMatcher;
use AlanVdb\Router\Route;
use AlanVdb\Router\Exception\RouteNotFound;
use AlanVdb\Router\Exception\MethodNotAllowed;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use AlanVdb\Router\RouteIterator;

#[CoversClass(RequestMatcher::class)]
final class RequestMatcherTest extends TestCase
{
    private function createRoute(string $path, string $methods, string $name = 'test_route'): Route
    {
        return new Route(
            $name,
            $methods,
            $path,
            fn() => 'response'
        );
    }

    private function createRequest(string $path, string $method): ServerRequestInterface
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn($path);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn($method);

        return $request;
    }

    public function testMatchRequestReturnsRouteOnValidPathAndMethod(): void
    {
        $route = $this->createRoute('/home', 'GET');
        $routeCollection = new RouteIterator($route);
        $request = $this->createRequest('/home', 'GET');

        $matcher = new RequestMatcher($routeCollection);
        $matchedRoute = $matcher->matchRequest($request);

        $this->assertSame($route, $matchedRoute);
    }

    public function testMatchRequestWithParameters(): void
    {
        $route = $this->createRoute('/user/{id}', 'GET');
        $routeCollection = new RouteIterator($route);
        $request = $this->createRequest('/user/123', 'GET');

        $matcher = new RequestMatcher($routeCollection);
        $matchedRoute = $matcher->matchRequest($request);

        $this->assertSame($route, $matchedRoute);
        $this->assertEquals(['id' => '123'], $route->getParams());
    }

    public function testMatchRequestThrowsMethodNotAllowed(): void
    {
        $route = $this->createRoute('/home', 'POST');
        $routeCollection = new RouteIterator($route);
        $request = $this->createRequest('/home', 'GET');

        $matcher = new RequestMatcher($routeCollection);

        $this->expectException(MethodNotAllowed::class);
        $matcher->matchRequest($request);
    }

    public function testMatchRequestThrowsRouteNotFound(): void
    {
        $route = $this->createRoute('/about', 'GET');
        $routeCollection = new RouteIterator($route);
        $request = $this->createRequest('/home', 'GET');

        $matcher = new RequestMatcher($routeCollection);

        $this->expectException(RouteNotFound::class);
        $matcher->matchRequest($request);
    }

    public function testMatchRequestWithMultipleRoutes(): void
    {
        $route1 = $this->createRoute('/home', 'POST');
        $route2 = $this->createRoute('/about', 'GET');
        $routeCollection = new RouteIterator($route1, $route2);
        $request = $this->createRequest('/about', 'GET');

        $matcher = new RequestMatcher($routeCollection);
        $matchedRoute = $matcher->matchRequest($request);

        $this->assertSame($route2, $matchedRoute);
    }

    #[DataProvider('providePathAndRegex')]
    public function testConvertPathToRegex(string $path, string $expectedRegex): void
    {
        $matcher = new RequestMatcher(new RouteIterator(new Route('test', 'GET', $path, fn() => null)));
        $reflection = new \ReflectionClass($matcher);
        $method = $reflection->getMethod('convertPathToRegex');
        $method->setAccessible(true);

        $actualRegex = $method->invokeArgs($matcher, [$path]);

        $this->assertSame($expectedRegex, $actualRegex);
    }

    public static function providePathAndRegex(): array
    {
        return [
            ['/home', '@^/home$@'],
            ['/post/{id}', '@^/post/(?P<id>[^/]+)$@'],
            ['/user/{id}/profile', '@^/user/(?P<id>[^/]+)/profile$@'],
            ['/category/{category_id}/product/{product_id}', '@^/category/(?P<category_id>[^/]+)/product/(?P<product_id>[^/]+)$@'],
        ];
    }

    #[DataProvider('provideMethodMatches')]
    public function testIsMethodMatch(array $routeMethods, string $requestMethod, bool $expected): void
    {
        $route = $this->createRoute('/test', implode('|', $routeMethods));
        $matcher = new RequestMatcher(new RouteIterator($route));
        $reflection = new \ReflectionClass($matcher);
        $method = $reflection->getMethod('isMethodMatch');
        $method->setAccessible(true);

        $result = $method->invokeArgs($matcher, [$route, $requestMethod]);

        $this->assertSame($expected, $result);
    }

    public static function provideMethodMatches(): array
    {
        return [
            [['GET'], 'GET', true],
            [['POST'], 'GET', false],
            [['GET', 'POST'], 'POST', true],
            [['PUT', 'PATCH'], 'DELETE', false],
        ];
    }
}
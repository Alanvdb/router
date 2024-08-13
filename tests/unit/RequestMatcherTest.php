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
use AlanVdb\Router\RouteCollection;

#[CoversClass(RequestMatcher::class)]
final class RequestMatcherTest extends TestCase
{
    #[\PHPUnit\Test]
    public function testMatchRequestReturnsRouteOnValidPathAndMethod(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/home');
        $route->method('getMethods')->willReturn(['GET']);

        $routeCollection = new RouteCollection();
        $routeCollection->add('home', fn() => $route);

        $request = $this->createMock(ServerRequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/home');
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $matcher = new RequestMatcher($routeCollection);
        $matchedRoute = $matcher->matchRequest($request);

        $this->assertSame($route, $matchedRoute);
    }

    #[\PHPUnit\Test]
    public function testMatchRequestThrowsMethodNotAllowed(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/home');
        $route->method('getMethods')->willReturn(['POST']);

        $routeCollection = new RouteCollection();
        $routeCollection->add('home', fn() => $route);

        $request = $this->createMock(ServerRequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/home');
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $matcher = new RequestMatcher($routeCollection);

        $this->expectException(MethodNotAllowed::class);
        $matcher->matchRequest($request);
    }

    #[\PHPUnit\Test]
    public function testMatchRequestThrowsRouteNotFound(): void
    {
        $route = $this->createMock(Route::class);
        $route->method('getPath')->willReturn('/about');
        $route->method('getMethods')->willReturn(['GET']);

        $routeCollection = new RouteCollection();
        $routeCollection->add('about', fn() => $route);

        $request = $this->createMock(ServerRequestInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/home');
        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $matcher = new RequestMatcher($routeCollection);

        $this->expectException(RouteNotFound::class);
        $matcher->matchRequest($request);
    }

    #[\PHPUnit\Test]
    #[DataProvider('providePathAndRegex')]
    public function testConvertPathToRegex(string $path, string $expectedRegex): void
    {
        $matcher = new RequestMatcher(new RouteCollection());
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
        ];
    }
}

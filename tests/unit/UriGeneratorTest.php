<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AlanVdb\Router\UriGenerator;
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Throwable\RouteNameNotFound;
use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Dependency\IterableLazyContainer;

class UriGeneratorTest extends TestCase
{
    public function testGenerateUriWithValidRoute()
    {
        $route = $this->createMock(RouteInterface::class);
        $route->method('getPath')->willReturn('/post/{slug}');

        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $uriGenerator = new UriGenerator($routeCollection);
        $uri = $uriGenerator->generateUri('post.show', ['slug' => 'hello-world']);

        $this->assertSame('/post/hello-world', $uri);
    }

    public function testGenerateUriWithMissingRouteThrowsException()
    {
        $this->expectException(RouteNameNotFound::class);
        $this->expectExceptionMessage('Provided route name not found in Route collection.');

        $routeCollection = new RouteCollection();
        $uriGenerator = new UriGenerator($routeCollection);

        $uriGenerator->generateUri('nonexistent.route', ['slug' => 'hello-world']);
    }

    public function testGenerateUriWithMultipleVariables()
    {
        $route = $this->createMock(RouteInterface::class);
        $route->method('getPath')->willReturn('/post/{slug}/comment/{id}');

        $routeCollection = new RouteCollection();
        $routeCollection->set('comment.show', function () use ($route) {
            return $route;
        });

        $uriGenerator = new UriGenerator($routeCollection);
        $uri = $uriGenerator->generateUri('comment.show', ['slug' => 'hello-world', 'id' => 123]);

        $this->assertSame('/post/hello-world/comment/123', $uri);
    }

    public function testGenerateUriWithNoVariables()
    {
        $route = $this->createMock(RouteInterface::class);
        $route->method('getPath')->willReturn('/about');

        $routeCollection = new RouteCollection();
        $routeCollection->set('about', function () use ($route) {
            return $route;
        });

        $uriGenerator = new UriGenerator($routeCollection);
        $uri = $uriGenerator->generateUri('about', []);

        $this->assertSame('/about', $uri);
    }

    public function testGenerateUriWithUnusedVariables()
    {
        $route = $this->createMock(RouteInterface::class);
        $route->method('getPath')->willReturn('/post/{slug}');

        $routeCollection = new RouteCollection();
        $routeCollection->set('post.show', function () use ($route) {
            return $route;
        });

        $uriGenerator = new UriGenerator($routeCollection);
        $uri = $uriGenerator->generateUri('post.show', ['slug' => 'hello-world', 'unused' => 'variable']);

        $this->assertSame('/post/hello-world', $uri);
    }
}

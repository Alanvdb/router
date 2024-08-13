<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router\Factory;

use AlanVdb\Router\Factory\RouterFactory;
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\RequestMatcher;
use AlanVdb\Router\UriGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Router\Definition\RequestMatcherInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;

#[CoversClass(RouterFactory::class)]
final class RouterFactoryTest extends TestCase
{
    #[\PHPUnit\Test]
    public function testCreateRoute(): void
    {
        $factory = new RouterFactory();
        $route = $factory->createRoute('home', 'GET', '/home', fn() => 'Home');

        $this->assertInstanceOf(RouteInterface::class, $route);
        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame('home', $route->getName());
        $this->assertSame(['GET'], $route->getMethods());
        $this->assertSame('/home', $route->getPath());
    }

    #[\PHPUnit\Test]
    public function testCreateRouteCollection(): void
    {
        $factory = new RouterFactory();
        $routeCollection = $factory->createRouteCollection();

        $this->assertInstanceOf(RouteIteratorInterface::class, $routeCollection);
        $this->assertInstanceOf(LazyContainerInterface::class, $routeCollection);
        $this->assertInstanceOf(RouteCollection::class, $routeCollection);
    }

    #[\PHPUnit\Test]
    public function testCreateRequestMatcher(): void
    {
        $factory = new RouterFactory();
        $routeCollection = $factory->createRouteCollection();
        $requestMatcher = $factory->createRequestMatcher($routeCollection);

        $this->assertInstanceOf(RequestMatcherInterface::class, $requestMatcher);
        $this->assertInstanceOf(RequestMatcher::class, $requestMatcher);
    }

    #[\PHPUnit\Test]
    public function testCreateUriGenerator(): void
    {
        $factory = new RouterFactory();
        $routeCollection = $factory->createRouteCollection();
        $uriGenerator = $factory->createUriGenerator($routeCollection);

        $this->assertInstanceOf(UriGeneratorInterface::class, $uriGenerator);
        $this->assertInstanceOf(UriGenerator::class, $uriGenerator);
    }

    #[\PHPUnit\Test]
    #[DataProvider('provideRouteData')]
    public function testCreateMultipleRoutes(string $name, string $methods, string $path): void
    {
        $factory = new RouterFactory();
        $route = $factory->createRoute($name, $methods, $path, fn() => 'Action');

        $this->assertInstanceOf(RouteInterface::class, $route);
        $this->assertSame($name, $route->getName());
        $this->assertSame(explode('|', $methods), $route->getMethods());
        $this->assertSame($path, $route->getPath());
    }

    public static function provideRouteData(): array
    {
        return [
            ['home', 'GET', '/home'],
            ['post_show', 'GET|POST', '/post/{id}'],
            ['user_profile', 'GET', '/user/{id}/profile'],
        ];
    }
}

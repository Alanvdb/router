<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router;

use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Route;
use AlanVdb\Router\Exception\RouteNameNotFound;
use AlanVdb\Dependency\Exception\InvalidContainerParamException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use TypeError;

#[CoversClass(RouteCollection::class)]
final class RouteCollectionTest extends TestCase
{
    #[\PHPUnit\Test]
    public function testAddRoute(): void
    {
        $routeCollection = new RouteCollection();
        $route = new Route('home', 'GET', '/home', fn() => 'Home');

        $routeCollection->add('home', fn() => $route);

        $this->assertTrue($routeCollection->has('home'));
    }

    #[\PHPUnit\Test]
    #[\PHPUnit\Depends('testAddRoute')]
    public function testGetRoute(): void
    {
        $routeCollection = new RouteCollection();
        $route = new Route('home', 'GET', '/home', fn() => 'Home');

        $routeCollection->add('home', fn() => $route);
        $retrievedRoute = $routeCollection->get('home');

        $this->assertSame($route, $retrievedRoute);
    }

    #[\PHPUnit\Test]
    public function testGetRouteThrowsExceptionForInvalidName(): void
    {
        $routeCollection = new RouteCollection();

        $this->expectException(RouteNameNotFound::class);

        $routeCollection->get('invalid');
    }

    #[\PHPUnit\Test]
    public function testIterationOverRoutes(): void
    {
        $routeCollection = new RouteCollection();
        $route1 = new Route('home', 'GET', '/home', fn() => 'Home');
        $route2 = new Route('about', 'GET', '/about', fn() => 'About');

        $routeCollection->add('home', fn() => $route1);
        $routeCollection->add('about', fn() => $route2);

        $routes = [];
        foreach ($routeCollection as $name => $route) {
            $routes[$name] = $route;
        }

        $this->assertCount(2, $routes);
        $this->assertSame($route1, $routes['home']);
        $this->assertSame($route2, $routes['about']);
    }

    #[\PHPUnit\Test]
    public function testAddThrowsExceptionForInvalidParams(): void
    {
        $routeCollection = new RouteCollection();

        if (empty($name)) {
            $this->expectException(InvalidContainerParamException::class);
        }

        $routeCollection->add('', fn() => new Route('home', 'GET', '/home', fn() => 'Home'));
    }
}

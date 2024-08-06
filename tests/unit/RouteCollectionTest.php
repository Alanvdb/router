<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Route;
use AlanVdb\Router\Throwable\RouteNameNotFound;
use Psr\Container\NotFoundExceptionInterface;
use AlanVdb\Router\Definition\RouteInterface;

class RouteCollectionTest extends TestCase
{
    public function testGetRouteByName()
    {
        $route = $this->createMock(RouteInterface::class);
        $routeCollection = new RouteCollection();
        $routeCollection->set('home', function () use ($route) {
            return $route;
        });

        $result = $routeCollection->get('home');
        $this->assertSame($route, $result);
    }

    public function testGetRouteByNameThrowsException()
    {
        $this->expectException(RouteNameNotFound::class);
        $this->expectExceptionMessage("Route name 'nonexistent' not found in collection.");

        $routeCollection = new RouteCollection();
        $routeCollection->get('nonexistent');
    }

    public function testCurrentRoute()
    {
        $route = $this->createMock(RouteInterface::class);
        $routeCollection = new RouteCollection();
        $routeCollection->set('home', function () use ($route) {
            return $route;
        });

        $routeCollection->rewind();
        $result = $routeCollection->current();

        $this->assertSame($route, $result);
    }

    public function testKeyRoute()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->set('home', function () {
            return $this->createMock(RouteInterface::class);
        });

        $routeCollection->rewind();
        $key = $routeCollection->key();

        $this->assertSame('home', $key);
    }
}

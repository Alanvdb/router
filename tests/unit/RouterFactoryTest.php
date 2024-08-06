<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AlanVdb\Router\Factory\RouterFactory;
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteCollection;
use AlanVdb\Router\Middleware\RequestMatcher;
use AlanVdb\Router\UriGenerator;
use AlanVdb\Router\Definition\RouteInterface;
use AlanVdb\Router\Definition\RouteIteratorInterface;
use AlanVdb\Dependency\Definition\LazyContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use AlanVdb\Router\Definition\UriGeneratorInterface;

class RouterFactoryTest extends TestCase
{
    public function testCreateRoute()
    {
        $factory = new RouterFactory();
        $route = $factory->createRoute('GET', '/path', function () {
            return 'target';
        });

        $this->assertInstanceOf(RouteInterface::class, $route);
        $this->assertSame('/path', $route->getPath());
        $this->assertSame(['GET'], $route->getMethods());
    }

    public function testCreateRouteCollection()
    {
        $factory = new RouterFactory();
        $routeCollection = $factory->createRouteCollection();

        $this->assertInstanceOf(RouteIteratorInterface::class, $routeCollection);
        $this->assertInstanceOf(LazyContainerInterface::class, $routeCollection);
    }

    public function testCreateRequestMatcher()
    {
        $factory = new RouterFactory();
        $routeCollection = new RouteCollection();

        $requestMatcher = $factory->createRequestMatcher($routeCollection);

        $this->assertInstanceOf(MiddlewareInterface::class, $requestMatcher);
        $this->assertInstanceOf(RequestMatcher::class, $requestMatcher);
    }

    public function testCreateUriGenerator()
    {
        $factory = new RouterFactory();
        $routeCollection = new RouteCollection();

        $uriGenerator = $factory->createUriGenerator($routeCollection);

        $this->assertInstanceOf(UriGeneratorInterface::class, $uriGenerator);
        $this->assertInstanceOf(UriGenerator::class, $uriGenerator);
    }
}

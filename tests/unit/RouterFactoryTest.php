<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router\Factory;

use AlanVdb\Router\Factory\RouterFactory;
use AlanVdb\Router\Definition\RouterInterface;
use AlanVdb\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    private RouterFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new RouterFactory();
    }

    public function testImplementsRouterFactoryInterface(): void
    {
        $this->assertInstanceOf(
            'AlanVdb\Router\Definition\RouterFactoryInterface',
            $this->factory
        );
    }

    public function testCreateRouterReturnsRouterInstance(): void
    {
        $routes = [
            ['home', 'GET', '/', fn() => null],
            ['about', 'GET', '/about', fn() => null]
        ];

        $router = $this->factory->createRouter($routes);

        $this->assertInstanceOf(Router::class, $router);
        $this->assertInstanceOf(RouterInterface::class, $router);
    }

    public function testCreatedRouterContainsProvidedRoutes(): void
    {
        $routes = [
            ['test_route', 'GET', '/test', fn() => null]
        ];

        $router = $this->factory->createRouter($routes);

        // Vérification indirecte via la génération d'URI
        $this->assertSame('/test', $router->generateUri('test_route'));
    }

    public function testEmptyRoutesArePassedThrough(): void
    {
        $this->expectException('AlanVdb\Router\Exception\InvalidRouterParamProvided');
        $this->expectExceptionMessage('No routes provided to the router.');

        $this->factory->createRouter([]);
    }

    public function testInvalidRoutesStructureArePassedThrough(): void
    {
        $this->expectException('AlanVdb\Router\Exception\InvalidRouterParamProvided');
        $this->expectExceptionMessage('Route parameters must be an array of 4 values.');

        $this->factory->createRouter([['invalid']]);
    }
}

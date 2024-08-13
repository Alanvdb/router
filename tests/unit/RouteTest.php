<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router;

use AlanVdb\Router\Route;
use AlanVdb\Router\Exception\InvalidRouteParamProvided;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(Route::class)]
final class RouteTest extends TestCase
{
    #[DataProvider('validRouteProvider')]
    public function testConstructorAndGetters(string $name, string $methods, string $path, callable $target): void
    {
        $route = new Route($name, $methods, $path, $target);

        $this->assertSame($name, $route->getName());
        $this->assertSame($path, $route->getPath());
        $this->assertSame(explode('|', $methods), $route->getMethods());
    }

    public static function validRouteProvider(): array
    {
        return [
            ['root', 'GET', '/', fn() => 'Root'],
            ['home', 'GET', '/home', fn() => 'Home'],
            ['post_show', 'GET|POST', '/post/{id}', fn() => 'Post Show'],
        ];
    }

    #[DataProvider('invalidRouteProvider')]
    public function testConstructorThrowsExceptionForInvalidParameters(string $name, string $methods, string $path, callable $target): void
    {
        $this->expectException(InvalidRouteParamProvided::class);

        new Route($name, $methods, $path, $target);
    }

    public static function invalidRouteProvider(): array
    {
        return [
            ['', 'GET', '/home', fn() => 'Home'],  // Empty name
            ['home', '', '/home', fn() => 'Home'],  // Empty methods
            ['home', 'GET', '', fn() => 'Home'],  // Empty path
            ['home', 'GET', 'invalid_path', fn() => 'Home'],  // Invalid path
        ];
    }

    public function testSetParams(): void
    {
        $route = new Route('home', 'GET', '/home', fn() => 'Home');
        $params = ['id' => 1, 'slug' => 'example'];

        $route->setParams($params);

        $this->assertSame($params, $route->getParams());
    }

    public function testSetParamsThrowsExceptionForInvalidKeys(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);

        $route = new Route('home', 'GET', '/home', fn() => 'Home');
        $route->setParams([1 => 'value']);  // Invalid key
    }

    public function testGetTarget(): void
    {
        $target = fn() => 'Target Called';
        $route = new Route('home', 'GET', '/home', $target);

        $this->assertSame($target, $route->getTarget());
    }

    #[Depends('testConstructorAndGetters')]
    public function testSetAndGetParamsDependency(): void
    {
        $route = new Route('home', 'GET', '/home', fn() => 'Home');
        $params = ['id' => 1, 'slug' => 'example'];

        $route->setParams($params);

        $this->assertSame($params, $route->getParams());
    }
}

<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router;

use AlanVdb\Router\Route;
use AlanVdb\Router\Exception\InvalidRouteParamProvided;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testCanCreateValidRoute(): void
    {
        $route = new Route(
            'test_route',
            'GET|POST',
            '/user/{id}',
            fn() => 'response'
        );

        $this->assertSame('test_route', $route->getName());
        $this->assertSame('/user/{id}', $route->getPath());
        $this->assertEquals(['GET', 'POST'], $route->getMethods());
        $this->assertIsCallable($route->getTarget());
    }

    public function testEmptyNameThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage('name argument cannot be empty.');
        
        new Route(
            '',
            'GET',
            '/path',
            fn() => null
        );
    }

    public function testEmptyMethodsThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage('methods argument cannot be empty.');
        
        new Route(
            'test',
            '',
            '/path',
            fn() => null
        );
    }

    public function testEmptyPathThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage('path argument cannot be empty.');
        
        new Route(
            'test',
            'GET',
            '',
            fn() => null
        );
    }

    public function testInvalidPathFormatThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage('Route path must be a valid regex pattern with variables');
        
        new Route(
            'test',
            'GET',
            'invalid-path',
            fn() => null
        );
    }

    public function testEmptyParamNameThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage("Route path must contain valid variable names");
        
        new Route(
            'empty_param',
            'GET',
            '/user/{ }',
            fn() => null
        );
    }

    public function testRouteWithWhitespaceInParamNames(): void
    {
        $route = new Route(
            'whitespace_route',
            'GET',
            '/user/{ user_id }',
            fn() => null
        );

        $this->assertSame('/user/{user_id}', $route->getPath());
        $this->assertArrayHasKey('user_id', $route->getParams());
    }

    public function testInvalidHttpMethodThrowsException(): void
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage("Invalid HTTP method provided: 'INVALID'. Allowed methods are GET, POST, PUT, DELETE, PATCH, OPTIONS.");
        
        new Route(
            'invalid_method',
            'INVALID',
            '/path',
            fn() => null
        );
    }

    public function testSetAndGetParams(): void
    {
        $route = new Route(
            'test',
            'GET',
            '/user/{id}',
            fn() => null
        );

        $params = ['id' => '123', 'name' => 'test'];
        $route->setParams($params);

        $this->assertSame($params, $route->getParams());
    }

    public function testSetParamsWithNonStringKeysThrowsException(): void
    {
        $route = new Route(
            'test',
            'GET',
            '/path',
            fn() => null
        );

        $this->expectException(InvalidRouteParamProvided::class);
        $this->expectExceptionMessage('Parameter keys must be strings.');
        
        $route->setParams([0 => 'value']);
    }

    public function testMethodsAreProperlyExploded(): void
    {
        $route = new Route(
            'test',
            'GET|POST|PUT|DELETE',
            '/path',
            fn() => null
        );

        $this->assertEquals(['GET', 'POST', 'PUT', 'DELETE'], $route->getMethods());
    }

    public function testSingleMethodRoute(): void
    {
        $route = new Route(
            'test',
            'GET',
            '/path',
            fn() => null
        );

        $this->assertEquals(['GET'], $route->getMethods());
    }

    public function testGetTargetReturnsCorrectValue(): void
    {
        $expectedResponse = 'expected response';
        $route = new Route(
            'test',
            'GET',
            '/path',
            fn() => $expectedResponse
        );

        $target = $route->getTarget();
        $this->assertSame($expectedResponse, $target());
    }

    public function testRouteWithComplexPath(): void
    {
        $route = new Route(
            'complex',
            'GET',
            '/user/{id}/profile/{section}',
            fn() => null
        );

        $this->assertSame('/user/{id}/profile/{section}', $route->getPath());
    }

    public function testRouteWithHyphensAndUnderscores(): void
    {
        $route = new Route(
            'hyphen_test',
            'GET',
            '/user-profile/{user_id}',
            fn() => null
        );

        $this->assertSame('/user-profile/{user_id}', $route->getPath());
    }
}

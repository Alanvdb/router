<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use AlanVdb\Router\Route;
use AlanVdb\Router\Throwable\InvalidRouteParamProvided;

class RouteTest extends TestCase
{
    public function testConstructWithEmptyPathThrowsException()
    {
        $this->expectException(InvalidRouteParamProvided::class);
        new Route('GET', '');
    }

    public function testConstructWithInvalidPathThrowsException()
    {
        $this->expectException(InvalidRouteParamProvided::class);
        new Route('GET', 'invalid_path');
    }

    public function testConstructWithEmptyMethodsThrowsException()
    {
        $this->expectException(InvalidRouteParamProvided::class);
        new Route('', '/valid/path');
    }

    public function testConstructWithValidParams()
    {
        $route = new Route('GET|POST', '/valid/path');

        $this->assertSame('/valid/path', $route->getPath());
        $this->assertSame(['GET', 'POST'], $route->getMethods());
    }

    public function testSetParamsWithNonStringKeysThrowsException()
    {
        $this->expectException(InvalidRouteParamProvided::class);
        $route = new Route('GET', '/valid/path');
        $route->setParams([123 => 'value']);
    }

    public function testSetParamsWithValidParams()
    {
        $route = new Route('GET', '/valid/path');
        $route->setParams(['param1' => 'value1', 'param2' => 'value2']);

        $this->assertSame(['param1' => 'value1', 'param2' => 'value2'], $route->getParams());
    }

    public function testGetPath()
    {
        $route = new Route('GET', '/valid/path');
        $this->assertSame('/valid/path', $route->getPath());
    }

    public function testGetMethods()
    {
        $route = new Route('GET', '/valid/path');
        $this->assertSame(['GET'], $route->getMethods());
    }

    public function testGetParams()
    {
        $route = new Route('GET', '/valid/path');
        $route->setParams(['param1' => 'value1']);

        $this->assertSame(['param1' => 'value1'], $route->getParams());
    }
}

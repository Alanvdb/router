<?php declare(strict_types=1);

namespace AlanVdb\Tests\Router;

use AlanVdb\Router\UriGenerator;
use AlanVdb\Router\Route;
use AlanVdb\Router\RouteIterator;
use AlanVdb\Router\Exception\RouteNameNotFound;
use AlanVdb\Router\Exception\InvalidUriGeneratorParamProvided;
use PHPUnit\Framework\TestCase;

class UriGeneratorTest extends TestCase
{
    private function createRoute(string $name, string $path): Route
    {
        return new Route($name, 'GET', $path, fn() => null);
    }

    public function testGenerateSimpleUri(): void
    {
        $route = $this->createRoute('home', '/home');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('home');
        $this->assertSame('/home', $uri);
    }

    public function testGenerateUriWithParameters(): void
    {
        $route = $this->createRoute('user_profile', '/user/{id}/profile/{tab}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('user_profile', [
            'id' => 123,
            'tab' => 'settings'
        ]);

        $this->assertSame('/user/123/profile/settings', $uri);
    }

    public function testGenerateUriWithPartialParameters(): void
    {
        $route = $this->createRoute('blog_post', '/blog/{year}/{slug}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('blog_post', [
            'year' => '2023',
            'slug' => 'test-post'
        ]);

        $this->assertSame('/blog/2023/test-post', $uri);
    }

    public function testGenerateUriWithExtraParameters(): void
    {
        $route = $this->createRoute('product', '/product/{id}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('product', [
            'id' => '456',
            'extra' => 'value' // Should be ignored
        ]);

        $this->assertSame('/product/456', $uri);
    }

    public function testGenerateUriWithMissingParameters(): void
    {
        $route = $this->createRoute('category', '/category/{category_id}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $this->expectException(InvalidUriGeneratorParamProvided::class);
        $generator->generateUri('category');
    }

    public function testGenerateUriWithNonStringParameters(): void
    {
        $route = $this->createRoute('page', '/page/{id}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('page', [
            'id' => 789 // int should be cast to string
        ]);

        $this->assertSame('/page/789', $uri);
    }

    public function testGenerateUriWithSpecialChars(): void
    {
        $route = $this->createRoute('search', '/search/{query}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('search', [
            'query' => 'hello world'
        ]);

        $this->assertSame('/search/hello world', $uri);
    }

    public function testGenerateUriForNonExistentRoute(): void
    {
        $iterator = new RouteIterator(new Route('existing_route', 'GET', '/existing', 'target'));
        $generator = new UriGenerator($iterator);

        $this->expectException(RouteNameNotFound::class);
        $this->expectExceptionMessage("Provided route name not found in Route collection : 'missing'.");

        $generator->generateUri('missing');
    }

    public function testGenerateUriWithWhitespaceInPattern(): void
    {
        $route = $this->createRoute('whitespace', '/user/{ id }');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('whitespace', [
            'id' => '42'
        ]);

        $this->assertSame('/user/42', $uri);
    }

    public function testGenerateUriWithMultipleSameParameters(): void
    {
        $route = $this->createRoute('multi_param', '/{lang}/product/{lang}/view');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $uri = $generator->generateUri('multi_param', [
            'lang' => 'fr'
        ]);

        $this->assertSame('/fr/product/fr/view', $uri);
    }

    public function testUriGeneratorIsCallable(): void
    {
        $route = $this->createRoute('test_route', '/user/{id}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        // Vérifie que l'instance est callable
        $this->assertIsCallable($generator);

        // Test avec la méthode generateUri()
        $uri1 = $generator->generateUri('test_route', ['id' => 123]);
        
        // Test avec l'appel direct
        $uri2 = $generator('test_route', ['id' => 123]);

        // Les deux méthodes doivent retourner le même résultat
        $this->assertSame('/user/123', $uri1);
        $this->assertSame($uri1, $uri2);
    }

    public function testCallableWithMissingParameters(): void
    {
        $route = $this->createRoute('test_route', '/user/{id}');
        $iterator = new RouteIterator($route);
        $generator = new UriGenerator($iterator);

        $this->expectException(InvalidUriGeneratorParamProvided::class);
        
        // Test que l'exception est bien levée avec l'appel direct
        $generator('test_route');
    }
}

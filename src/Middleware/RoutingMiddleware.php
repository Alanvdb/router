<?php declare(strict_types=1);

namespace AlanVdb\Router\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use AlanVdb\Router\Definition\RouterInterface;
use AlanVdb\Router\Exception\MethodNotAllowed;
use AlanVdb\Router\Exception\RouteNotFound;

/**
 * Middleware that handles routing and makes matched route available to subsequent middleware
 */
class RoutingMiddleware implements MiddlewareInterface
{
    public const ATTRIBUTE_MATCHED_ROUTE = 'matchedRoute';
    public const ATTRIBUTE_URI_GENERATOR = 'uriGenerator';

    public function __construct(
        protected RouterInterface $router,
        protected ResponseFactoryInterface $responseFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $route = $this->router->getRequestMatcher()->matchRequest($request);
            
            return $handler->handle(
                $request
                    ->withAttribute(self::ATTRIBUTE_MATCHED_ROUTE, $route)
                    ->withAttribute(self::ATTRIBUTE_URI_GENERATOR, $this->router->getUriGenerator())
            );
            
        } catch (RouteNotFound $e) {
            return $this->responseFactory->createResponse(404);
        } catch (MethodNotAllowed $e) {
            return $this->responseFactory->createResponse(405)
                ->withHeader('Allow', implode(', ', $e->getAllowedMethods()));
        }
    }
}

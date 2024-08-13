<?php declare(strict_types=1);

namespace AlanVdb\Router\Exception;

use InvalidArgumentException;
use AlanVdb\Router\Definition\RouterExceptionInterface;

/**
 * The InvalidRouteParamProvided exception is thrown when a route is provided
 * with invalid parameters. This could be due to empty values or parameters that
 * do not meet expected criteria, such as an invalid path format.
 * 
 * This exception extends the InvalidArgumentException and implements the
 * RouterExceptionInterface to allow for specialized handling within the
 * router context.
 */
class InvalidRouteParamProvided
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

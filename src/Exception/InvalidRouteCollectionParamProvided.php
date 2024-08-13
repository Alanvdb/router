<?php declare(strict_types=1);

namespace AlanVdb\Router\Exception;

use InvalidArgumentException;
use AlanVdb\Router\Definition\RouterExceptionInterface;

/**
 * The InvalidRouteCollectionParamProvided exception is thrown when an invalid
 * parameter is provided to a route collection. This might occur due to empty
 * values, duplicate route names, or parameters that do not meet the expected
 * criteria.
 * 
 * This exception extends the InvalidArgumentException and implements the
 * RouterExceptionInterface to allow for specialized handling within the
 * router context.
 */
class InvalidRouteCollectionParamProvided
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

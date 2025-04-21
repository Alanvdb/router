<?php

namespace AlanVdb\Router\Exception;

use InvalidArgumentException;
use AlanVdb\Router\Definition\RouterExceptionInterface;

/**
 * The InvalidUriGeneratorParamProvided exception is thrown when the URI generator
 * is provided with invalid parameters. This could be due to missing required
 * variables or parameters that do not meet expected criteria.
 * 
 * This exception extends the InvalidArgumentException and implements the
 * RouterExceptionInterface to allow for specialized handling within the
 * router context.
 */
class InvalidUriGeneratorParamProvided
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

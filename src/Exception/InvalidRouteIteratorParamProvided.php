<?php

namespace AlanVdb\Router\Exception;

use AlanVdb\Router\Definition\RouterExceptionInterface;
use InvalidArgumentException;

class InvalidRouteIteratorParamProvided
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

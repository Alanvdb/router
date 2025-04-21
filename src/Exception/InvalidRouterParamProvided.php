<?php

namespace AlanVdb\Router\Exception;

use InvalidArgumentException;
use AlanVdb\Router\Definition\RouterExceptionInterface;

class InvalidRouterParamProvided
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

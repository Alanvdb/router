<?php

namespace AlanVdb\Router\Exception;

use AlanVdb\Router\Definition\RouterExceptionInterface;
use InvalidArgumentException;

class RouteNameNotFound
    extends InvalidArgumentException
    implements RouterExceptionInterface
{}

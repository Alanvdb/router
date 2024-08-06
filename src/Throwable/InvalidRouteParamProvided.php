<?php declare(strict_types=1);


namespace AlanVdb\Router\Throwable;


use InvalidArgumentException;
use Throwable;


class InvalidRouteParamProvided
    extends InvalidArgumentException
    implements Throwable
{}

<?php declare(strict_types=1);


namespace AlanVdb\Router\Throwable;


use Throwable;
use InvalidArgumentException;


class RouteNameNotFound
    extends InvalidArgumentException
    implements Throwable
{}
<?php declare(strict_types=1);


namespace AlanVdb\Router\Throwable;


use Throwable;
use RuntimeException;


class RouteNotFound
    extends RuntimeException
    implements Throwable
{}
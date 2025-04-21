<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface MethodNotAllowedInterface extends RouterExceptionInterface
{
    public function getAllowedMethods(): array;
}

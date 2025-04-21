<?php

namespace AlanVdb\Router\Exception;

use AlanVdb\Router\Definition\MethodNotAllowedInterface;
use RuntimeException;

class MethodNotAllowed
    extends RuntimeException
    implements MethodNotAllowedInterface
{
    private array $allowedMethods = [];

    public function __construct(array $allowedMethods, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->allowedMethods = $allowedMethods;
    }

    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}

<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface UriGeneratorInterface
{
    public function generateUri(string $name, array $vars = []) : string;
}

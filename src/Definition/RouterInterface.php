<?php declare(strict_types=1);

namespace AlanVdb\Router\Definition;

interface RouterInterface
{
    public function getUriGenerator(): UriGeneratorInterface;

    public function getRequestMatcher(): RequestMatcherInterface;
}

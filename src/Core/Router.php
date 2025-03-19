<?php

declare(strict_types=1);

namespace Crud\Core;
use Crud\Exception\IncorrectRoutePathException;
use Exception;

class Router
{
    public function getController(string $routePath): object
    {
        throw new IncorrectRoutePathException('pyzdiec ne ten');
    }
}
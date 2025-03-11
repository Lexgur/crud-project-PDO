<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

use ReflectionException;

class Container
{
    private array $services = [];

    public function __construct(private readonly string $baseDir = __DIR__)
    {

    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    /**
     * @throws ReflectionException
     */
    public function get(string $id): object
    {

    }
}

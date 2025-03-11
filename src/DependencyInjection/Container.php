<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

use ReflectionClass;

class Container
{
    private array $services = [];

    public function __construct()
    {
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function get(string $id): object
    {
        if (!$this->has($id)) {
            $this->services[$id] = new $id();
        }
        return $this->services[$id];
    }
}

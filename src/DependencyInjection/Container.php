<?php

namespace Crud\DependencyInjection;

class Container
{
    private array $services = [];

    public function has(string $id): bool
    {
        return class_exists($id);
    }

    public function get(string $id): object
    {
        if (!isset($this->services[$id])) {
            if (!class_exists($id)) {
                throw new \InvalidArgumentException("Service '$id' not found.");
            }
            $this->services[$id] = new $id();
        }

        return $this->services[$id];
    }
}
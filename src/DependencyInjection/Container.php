<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

use ReflectionClass;
use ReflectionException;

class Container
{
    private array $services = [];

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function bind(string $id, object $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * @throws ReflectionException
     */
    public function get(string $id): object
    {
        if (str_contains($id, 'Model\\')) {
            throw new ReflectionException("Skipping Model classes: $id");
        }

        if ($this->has($id)) {
            return $this->services[$id];
        }

        try {
            $reflectionClass = new ReflectionClass($id);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor === null) {

                $instance = new $id();
            } else {
                $parameters = $constructor->getParameters();
                $dependencies = [];

                foreach ($parameters as $parameter) {
                    $type = $parameter->getType();

                    if (($type === null) || $type->isBuiltin()) {
                        throw new ReflectionException("Cannot resolve parameter: " . $parameter->getName());
                    }

                    $dependencyClass = $type->getName();
                    $dependencies[] = $this->get($dependencyClass);
                }

                $instance = $reflectionClass->newInstanceArgs($dependencies);
            }

            $this->services[$id] = $instance;
            return $instance;
        } catch (ReflectionException $e) {
            throw new ReflectionException("Cannot instantiate $id: " . $e->getMessage());
        }
    }
}

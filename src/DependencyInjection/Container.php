<?php

namespace Crud\DependencyInjection;

use ReflectionClass;

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
            $reflection = new ReflectionClass($id);
            $constructor = $reflection->getConstructor();

            if ($constructor === null || $constructor->getNumberOfParameters() === 0) {

                $this->services[$id] = $reflection->newInstance();
            } else {

                $parameters = [];
                foreach ($constructor->getParameters() as $parameter) {
                    $paramType = $parameter->getType();
                    if ($paramType && !$paramType->isBuiltin()) {

                        $paramClass = $paramType->getName();
                        $parameters[] = $this->get($paramClass);
                    } else {
                        throw new \InvalidArgumentException("Cannot resolve parameter '{$parameter->getName()}' for '$id'.");
                    }
                }
                $this->services[$id] = $reflection->newInstanceArgs($parameters);
            }
        }

        return $this->services[$id];
    }
}
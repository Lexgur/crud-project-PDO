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
                try {
                    $this->services[$id] = $reflection->newInstance();
                } catch (\ReflectionException $e) {
                }
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
                try {
                    $this->services[$id] = $reflection->newInstanceArgs($parameters);
                } catch (\ReflectionException $e) {
                }
            }
        }

        return $this->services[$id];
    }
}

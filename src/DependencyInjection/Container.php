<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

use Crud\Exception\CircularDependencyException;
use Crud\Exception\MissingDependencyInjectionParameterException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container
{
    private array $services;
    private array $parameters;

    public function __construct(array $parameters = [], array $services = [])
    {
        $this->parameters = $parameters;
        $this->services = $services;
    }

    public function has(string $serviceClass): bool
    {
        return isset($this->services[$serviceClass]);
    }

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @throws MissingDependencyInjectionParameterException
     */
    public function getParameter(string $name): mixed
    {
        if (!$this->hasParameter($name)) {
            throw new MissingDependencyInjectionParameterException("Missing parameter: $name");
        }
        return $this->parameters[$name];
    }

    public function bind(string $serviceClass, object $service): void
    {
        $this->services[$serviceClass] = $service;
    }

    /**
     * @throws CircularDependencyException
     * @throws MissingDependencyInjectionParameterException
     * @throws ReflectionException
     */
    public function get(string $serviceClass): object
    {
        static $instantiating = [];

        if (str_starts_with($serviceClass, 'Crud\Model')) {
            throw new ReflectionException("Skipping Model classes: $serviceClass");
        }

        if ($this->has($serviceClass)) {
            return $this->services[$serviceClass];
        }

        if (isset($instantiating[$serviceClass])) {
            throw new CircularDependencyException("Circular dependency detected for: $serviceClass");
        }

        $instantiating[$serviceClass] = true;

        try {
            $reflectionClass = new ReflectionClass($serviceClass);

            if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
                throw new ReflectionException("Cannot instantiate abstract class or interface: $serviceClass");
            }

            $constructor = $reflectionClass->getConstructor();

            if ($constructor === null) {
                $instance = new $serviceClass();
            } else {
                $dependencies = $this->resolveDependencies($constructor->getParameters());
                $instance = $reflectionClass->newInstanceArgs($dependencies);
            }

            $this->services[$serviceClass] = $instance;
            unset($instantiating[$serviceClass]);
            return $instance;

        } catch (ReflectionException $e) {
            throw new ReflectionException("Cannot instantiate $serviceClass: " . $e->getMessage());
        }
    }

    /**
     * Resolves dependencies for a given set of parameters.
     *
     * @param array $parameters
     * @return array
     * @throws MissingDependencyInjectionParameterException
     * @throws CircularDependencyException
     * @throws ReflectionException
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            $parameterName = $parameter->getName();

            if ($type === null || $type->isBuiltin()) {
                $dependencies[] = $this->resolveParameter($parameterName, $parameter);
            } else {
                $dependencies[] = $this->get($type->getName());
            }
        }

        return $dependencies;
    }

    /**
     * Resolves a scalar parameter.
     *
     * @param string $parameterName
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws MissingDependencyInjectionParameterException|ReflectionException
     */
    private function resolveParameter(string $parameterName, ReflectionParameter $parameter): mixed
    {
        if (isset($this->parameters[$parameterName])) {
            return $this->parameters[$parameterName];
        }

        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        }

        throw new MissingDependencyInjectionParameterException("Cannot resolve parameter: $parameterName");
    }
}
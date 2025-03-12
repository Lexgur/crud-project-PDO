<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

use Crud\Template;
use PDO;
use ReflectionClass;
use ReflectionException;

class Container
{
    private array $services = [];
    private array $config;

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
        if (str_starts_with($id, 'Crud\Model')) {
            throw new ReflectionException("Skipping Model classes: $id");
        }

        if ($this->has($id)) {
            return $this->services[$id];
        }

        if ($id === Template::class) {
            global $config;
            $templatePath = $config['templates'];

            return new Template($templatePath);
        }

        if ($id === PDO::class) {
            global $config;
            $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}";
            $username = $config['db']['username'];
            $password = $config['db']['password'];
            return new PDO($dsn, $username, $password);
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

                    if ($type === null || $type->isBuiltin()) {

                        if ($parameter->isOptional()) {
                            $dependencies[] = $parameter->getDefaultValue();
                        } else {
                            throw new ReflectionException("Cannot resolve parameter: " . $parameter->getName());
                        }
                    } else {

                        $dependencyClass = $type->getName();


                        if ($dependencyClass === 'string' && $parameter->getName() === 'templatePath') {
                            $dependencies[] = $GLOBALS['config']['templates'];
                        } else {
                            $dependencies[] = $this->get($dependencyClass);
                        }
                    }
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

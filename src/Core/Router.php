<?php

declare(strict_types=1);

namespace Crud\Core;

use Crud\Attribute\Path;
use Crud\Exception\IncorrectRoutePathException;
use ReflectionClass;

class Router
{
    private array $routes = [];

    /**
     * @throws IncorrectRoutePathException
     */
    public function registerControllers(): void
    {
        $controllerDir = __DIR__ . '/Controller';

        $files = glob($controllerDir . '/*.php');
        $files = array_merge($files, glob($controllerDir . '/*/*.php'));

        foreach ($files as $file) {
            $className = $this->getFullClassName($file);

            if ($className) {
                try {
                    $reflectionClass = new ReflectionClass($className);
                } catch (\Throwable $e) {
                    throw new \RuntimeException('Failed to reflect class $className: ' . $e->getMessage());
                }

                $classAttributes = $reflectionClass->getAttributes(Path::class);
                if ($classAttributes) {
                    $routePath = $classAttributes[0]->newInstance()->getPath();
                    $this->routes[$routePath] = $className;
                }
            }
        }
    }
    public function getFullClassName(string $filePath): ?string
    {
        $content = file_get_contents($filePath);
        if (!$content) {
            throw new \RuntimeException("Failed to read file: $filePath");
        }

        $namespace = null;
        if (preg_match('/namespace\s+(.+);/', $content, $namespaceMatch)) {
            $namespace = trim($namespaceMatch[1]);
        }
        if (preg_match('/class\s+([^\s{]+)/', $content, $classMatch)) {
            $className = trim($classMatch[1]);
            return $namespace ? $namespace . '\\' . $className : $className;
        }

        throw new IncorrectRoutePathException('Class not found: ' . $filePath);
    }

    public function getController(string $routePath): object
    {
        if (!array_key_exists($routePath, $this->routes)) {
            throw new IncorrectRoutePathException("Route path '$routePath' not found.");
        }

        $controllerClass = $this->routes[$routePath];

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller class '$controllerClass' does not exist.");
        }

        return new $controllerClass();
    }
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

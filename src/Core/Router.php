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
    public function registerControllers(string $controllerDir): void
    {
        $files = glob($controllerDir . '/*.php');
        $files = array_merge($files, glob($controllerDir . '/*/*.php'));

        foreach ($files as $file) {
            $className = $this->getFullClassName($file);

            if ($className) {
                try {
                    $reflectionClass = new ReflectionClass($className);
                } catch (\ReflectionException $e) {
                    throw new \RuntimeException('Failed to reflect class $fullyQualifiedClassName: ' . $e->getMessage());
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

    public function getController(string $routePath): array
    {
        if (!array_key_exists($routePath, $this->routes)) {
            throw new IncorrectRoutePathException("Route path '$routePath' not found.");
        }
        $controllerInfo = $this->routes[$routePath];
        if (is_array($controllerInfo)) {
            [$controllerClass, $methodName] = $controllerInfo;
            return [new $controllerClass(), $methodName];
        } else {
            return [new $controllerInfo(), '__invoke'];
        }
    }
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

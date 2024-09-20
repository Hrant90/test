<?php

namespace Services;

use ReflectionClass;
use ReflectionNamedType;
use Exception;

class Container {
    private array $instances = [];
    private array $parameters = [];
    private array $bindings = [];

    public function bind(string $abstract, string $concrete): void {
        $this->bindings[$abstract] = $concrete;
    }

    public function setParameters(string $class, array $parameters): void {
        $this->parameters[$class] = $parameters;
    }

    public function get(string $class): object {
        if (isset($this->bindings[$class])) {
            $class = $this->bindings[$class];
        }

        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        $reflectionClass = new ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();
        if ($constructor === null) {
            $this->instances[$class] = new $class;
            return $this->instances[$class];
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } else {
                $classParams = $this->parameters[$class] ?? [];

                if (array_key_exists($parameter->getName(), $classParams)) {
                    $dependencies[] = $classParams[$parameter->getName()];
                } else {
                    throw new Exception("Cannot resolve parameter '{$parameter->getName()}' for class '{$class}'");
                }
            }
        }

        $this->instances[$class] = $reflectionClass->newInstanceArgs($dependencies);
        return $this->instances[$class];
    }
}

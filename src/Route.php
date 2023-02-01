<?php

namespace R2SSimpleRouter;

use R2SSimpleRouter\Enums\RouteMethod;

class Route
{
    private string $path;
    private RouteMethod $routeMethod;
    private array $classAndMethod;
    private array $middlewares = [];

    public function __construct(string $path, RouteMethod $routeMethod, array $classAndMethod, array $middlewares = [])
    {
        $this->path = $path;
        $this->routeMethod = $routeMethod;
        $this->classAndMethod = $classAndMethod;
        $this->middlewares = $middlewares;
    }

    public function withMiddlewares(): void
    {
        // TODO: Implement
    }

    public static function get(string $path, array $classAndMethod): Route
    {
        return new self($path, RouteMethod::GET, $classAndMethod);
    }

    public static function post(string $path, array $classAndMethod): Route
    {
        return new self($path, RouteMethod::POST, $classAndMethod);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRouteMethod(): RouteMethod
    {
        return $this->routeMethod;
    }

    public function getClass(): string
    {
        return $this->classAndMethod[0];
    }

    public function instantiateClass()
    {
        $class = $this->getClass();
        return new $class();
    }

    public function getMethodName(): string
    {
        return $this->classAndMethod[1];
    }

    public function getClassAndMethod(): array
    {
        return $this->classAndMethod;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}

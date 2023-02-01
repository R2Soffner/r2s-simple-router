<?php

namespace R2SSimpleRouter\Handlers;

use R2SSimpleRouter\Enums\ResponseStatusCode;
use R2SSimpleRouter\Response;
use R2SSimpleRouter\Route;
use R2SStringHelper\Stringer;

class RouteManager
{
    /** @var Route[] */
    private array $routes;
    private string $url;

    public function __construct(array $routes)
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->routes = $routes;
        $this->handleRequest();
    }

    private function handleRequest(): void
    {
        foreach ($this->routes as $route) {
            $regex = Stringer::fromString($route->getPath())
                ->replace('/', '\/')
                ->replaceWithRegex('/:[A-Za-z]+/', '([0-9]+)')
                ->getString();

            if (preg_match('/^'.$regex.'$/', $this->getParsedUrl())) {
                $params = [];
                foreach (explode( '/', $route->getPath()) as $index => $routeToken) {
                    if (Stringer::fromString($routeToken)->contains(':')) {
                        $params[str_replace(':', '', $routeToken)] = $this->getUri()[$index];
                    }
                }
                if ($route->getRouteMethod()->value != $_SERVER['REQUEST_METHOD']) {
                    Response::error(responseStatusCode: ResponseStatusCode::HTTP_METHOD_NOT_ALLOWED);
                }

                $route->instantiateClass()->{$route->getMethodName()}(...$params);

                Response::success();
            }
        }

        Response::error(responseStatusCode: ResponseStatusCode::HTTP_NOT_FOUND);
    }

    private function getParsedUrl(): bool|int|array|string|null
    {
        return parse_url($this->url, PHP_URL_PATH);
    }

    private function getUri(): array
    {
        return explode( '/', $this->getParsedUrl());
    }
}
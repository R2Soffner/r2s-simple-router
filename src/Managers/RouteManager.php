<?php

namespace R2SSimpleRouter\Managers;

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
                ->replaceWithRegex('/:[A-Za-z_]+/', '([0-9A-Za-z]+)')
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

                $formattedParams = [];
                foreach ($params as $key => $value) {
                    $new_key = lcfirst(str_replace("_", "", ucwords($key, "_")));
                    $formattedParams[$new_key] = $value;
                }
                extract($formattedParams);

                $route->instantiateClass()->{$route->getMethodName()}(...$formattedParams);

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
<?php

namespace R2SSimpleRouter;

use Closure;

class Request
{
    private Closure $routeClosure;

    public function signature(): string
    {
        return sha1(
            implode(
                '|',
                array_merge(
                    [
                        $_SERVER['REMOTE_ADDR'],
                    ],
                    $this->route()
                )
            )
        );
    }

    public static function all(): array
    {
        return array_merge(
            $_GET,
            $_POST,
            json_decode(file_get_contents('php://input'), true) ?? []
        );
    }

    public static function route(): bool|int|array|string|null
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function get(string $parameter, mixed $default = null)
    {
        return @self::all()[$parameter] ?? $default;
    }

    public static function bearerToken()
    {
        return isset($_SERVER['HTTP_AUTHORIZATION'])
            ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION'])
            : '';
    }
}

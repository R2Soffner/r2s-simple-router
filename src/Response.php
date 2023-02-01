<?php

namespace R2SSimpleRouter;

use R2SSimpleRouter\Enums\ResponseStatusCode;

class Response
{
    private static function make(mixed $data = [], string $message, ResponseStatusCode $responseStatusCode, array $headers = []) {
        header("HTTP/1.1 {$responseStatusCode->value}");
        header('Content-Type: application/json; charset=utf-8');
        header_remove('Set-Cookie');
        if (is_array($headers) && count($headers)) {
            foreach ($headers as $header) {
                header($header);
            }
        }
        echo json_encode(
            [
                'message' => $message,
                'data' => $data,
            ]
        );
        exit();
    }

    public static function success(mixed $data = [], string $message = 'Success', ResponseStatusCode $responseStatusCode = ResponseStatusCode::HTTP_OK)
    {
        (new self())->make($data, $message, $responseStatusCode);
    }

    public static function error(mixed $data = [], string $message = 'Error', ResponseStatusCode $responseStatusCode = ResponseStatusCode::HTTP_BAD_REQUEST)
    {
        (new self())->make($data, $message, $responseStatusCode);
    }
}

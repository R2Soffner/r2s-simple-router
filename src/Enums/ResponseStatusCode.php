<?php

namespace R2SSimpleRouter\Enums;

enum ResponseStatusCode: string
{
    case HTTP_OK = '200 Ok';
    case HTTP_CREATED = '201 Created';

    case HTTP_BAD_REQUEST = '400 Bad Request';
    case HTTP_NOT_FOUND = '404 Not Found';
    case HTTP_METHOD_NOT_ALLOWED = '405 Method Not Allowed';
}
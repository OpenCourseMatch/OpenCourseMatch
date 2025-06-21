<?php

class HTTPResponses {
    public static array $RESPONSE_OK = [
        "code" => 200,
        "message" => "OK"
    ];

    public static array $RESPONSE_CREATED = [
        "code" => 201,
        "message" => "Created"
    ];

    public static array $RESPONSE_NO_CONTENT = [
        "code" => 204,
        "message" => "No Content"
    ];

    public static array $RESPONSE_BAD_REQUEST = [
        "code" => 400,
        "message" => "Bad Request"
    ];

    public static array $RESPONSE_UNAUTHORIZED = [
        "code" => 401,
        "message" => "Unauthorized"
    ];

    public static array $RESPONSE_FORBIDDEN = [
        "code" => 403,
        "message" => "Forbidden"
    ];

    public static array $RESPONSE_NOT_FOUND = [
        "code" => 404,
        "message" => "Not Found"
    ];

    public static array $RESPONSE_METHOD_NOT_ALLOWED = [
        "code" => 405,
        "message" => "Method Not Allowed"
    ];

    public static array $RESPONSE_INTERNAL_SERVER_ERROR = [
        "code" => 500,
        "message" => "Internal Server Error"
    ];

    public static array $RESPONSE_NOT_IMPLEMENTED = [
        "code" => 501,
        "message" => "Not Implemented"
    ];

    public static array $RESPONSE_SERVICE_UNAVAILABLE = [
        "code" => 503,
        "message" => "Service Unavailable"
    ];

    private function __construct() {}
}

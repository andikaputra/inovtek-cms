<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Json
{
    /**
     * API Response
     *
     * @var array
     */
    protected static $response = [
        'success' => null,
        'http_status' => null,
        'meta' => [
            'message' => null,
            'error' => null,
        ],
        'data' => null,
    ];

    /**
     * Give success response.
     */
    public static function success(mixed $data): JsonResponse
    {
        self::$response['success'] = true;
        self::$response['http_status'] = Response::HTTP_OK;
        self::$response['meta']['message'] = 'Query Execution Success';
        self::$response['meta']['error'] = [];
        self::$response['data'] = $data;

        return response()->json(self::$response, Response::HTTP_OK);
    }

    /**
     * Give error response.
     */
    public static function error(mixed $error, ?string $httpCode = null): JsonResponse
    {
        self::$response['success'] = false;
        self::$response['http_status'] = $httpCode == null ? Response::HTTP_INTERNAL_SERVER_ERROR : $httpCode;
        self::$response['meta']['message'] = 'Query Execution Failed';
        self::$response['meta']['error'] = is_array($error) ? $error : ucfirst(strtolower($error));
        self::$response['data'] = [];

        return response()->json(self::$response, $httpCode == null || ! self::isValidHttpCode($httpCode) ? Response::HTTP_INTERNAL_SERVER_ERROR : $httpCode);
    }

    /**
     * Array valid response.
     */
    public static function isValidHttpCode($httpCode): bool
    {
        $validErrorCodes = [
            400, // Bad Request
            401, // Unauthorized
            402, // Payment Required
            403, // Forbidden
            404, // Not Found
            405, // Method Not Allowed
            406, // Not Acceptable
            407, // Proxy Authentication Required
            408, // Request Timeout
            409, // Conflict
            410, // Gone
            411, // Length Required
            412, // Precondition Failed
            413, // Payload Too Large
            414, // URI Too Long
            415, // Unsupported Media Type
            416, // Range Not Satisfiable
            417, // Expectation Failed
            418, // I'm a teapot
            421, // Misdirected Request
            422, // Unprocessable Entity
            423, // Locked
            424, // Failed Dependency
            425, // Too Early
            426, // Upgrade Required
            428, // Precondition Required
            429, // Too Many Requests
            431, // Request Header Fields Too Large
            451, // Unavailable For Legal Reasons
            500, // Internal Server Error
            501, // Not Implemented
            502, // Bad Gateway
            503, // Service Unavailable
            504, // Gateway Timeout
            505, // HTTP Version Not Supported
            506, // Variant Also Negotiates
            507, // Insufficient Storage
            508, // Loop Detected
            510, // Not Extended
            511, // Network Authentication Required
        ];

        return in_array($httpCode, $validErrorCodes);
    }
}

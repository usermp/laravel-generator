<?php

/**
 *
 * Response class for handling HTTP responses
 * Contains constants for HTTP status codes and methods for creating responses with those status codes
 */

namespace Usermp\LaravelGenerator\Services;

use Illuminate\Http\JsonResponse;

class Response
{
    const MESSAGE_NOT_FOUND = 'Not found this URL';
    const MESSAGE_UNAUTHORIZED = 'Unauthorized';
    const MESSAGE_FORBIDDEN = 'Forbidden';
    const MESSAGE_METHOD_NOT_ALLOWED = 'Method not allowed';
    const MESSAGE_SERVICE_UNAVAILABLE = 'Service unavailable';
    const MESSAGE_GATEWAY_TIMEOUT = 'Gateway timeout';
    const MESSAGE_INTERNAL_SERVER_ERROR = 'Internal server error';
    const MESSAGE_NOT_IMPLEMENTED = 'Not implemented';

    /**
     * Return a success response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $status
     * @return JsonResponse
     */
    public static function success(string $message = "success", mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => "Success",
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public static function error(string $message, int $status = 500): JsonResponse
    {
        return response()->json([
            'status'  => "Error",
            'message' => $message,
            'data' => null,
        ], $status);
    }

    /**
     * Return a "not found" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = self::MESSAGE_NOT_FOUND): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return an "unauthorized" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message =self::MESSAGE_UNAUTHORIZED): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return a "forbidden" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = self::MESSAGE_FORBIDDEN): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return a "method not allowed" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function methodNotAllowed(string $message = self::MESSAGE_METHOD_NOT_ALLOWED): JsonResponse
    {
        return self::error($message, 405);
    }

    /**
     * Return a "service unavailable" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serviceUnavailable(string $message = self::MESSAGE_SERVICE_UNAVAILABLE): JsonResponse
    {
        return self::error($message, 503);
    }

    /**
     * Return a "gateway timeout" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function gatewayTimeout(string $message = self::MESSAGE_GATEWAY_TIMEOUT): JsonResponse
    {
        return self::error($message, 504);
    }

    /**
     * Return an "internal server error" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function internalServerError(string $message = self::MESSAGE_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return self::error($message, 500);
    }

    /**
     * Return a "not implemented" error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notImplemented(string $message = self::MESSAGE_NOT_IMPLEMENTED): JsonResponse
    {
        return self::error($message, 501);
    }
}

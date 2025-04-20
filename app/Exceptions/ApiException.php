<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiException extends RuntimeException
{
    /** @var mixed|null  */
    private mixed $data;

    /**
     * ApiException constructor.
     *
     * @param int $httpCode
     * @param string $message
     * @param null $data
     * @param Throwable|null $previous
     */
    public function __construct(int $httpCode, string $message, $data = null, Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $httpCode, $previous);
    }

    /**
     * @return mixed|null
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function serviceUnavailable(string $message = 'Service unavailable', $data = null): ApiException
    {
        return new ApiException(Response::HTTP_SERVICE_UNAVAILABLE, $message, $data);
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function badRequest(string $message = "Bad request", $data = null): ApiException
    {
        return new ApiException(Response::HTTP_BAD_REQUEST, $message, $data);
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function forbidden(string $message = "forbidden", $data = null): ApiException
    {
        return new ApiException(Response::HTTP_FORBIDDEN, $message, $data);
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function notFound(string $message = "Not found", $data = null): ApiException
    {
        return new ApiException(Response::HTTP_NOT_FOUND, $message, $data);
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function conflict(string $message = "Conflict", $data = null): ApiException
    {
        return new ApiException(Response::HTTP_CONFLICT, $message, $data);
    }

    /**
     * @param string $message
     * @param null $data
     * @return ApiException
     */
    public static function validation(string $message = "Request invalid", $data = null): ApiException
    {
        return new ApiException(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $data);
    }
}

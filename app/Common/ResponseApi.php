<?php

namespace App\Common;

use RuntimeException;
use \Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 *  Class ResponseApi
 *
 * @package namespace App\Common
 */
class ResponseApi extends RuntimeException
{
    /**
     * Response 200
     *
     * @param array|Collection $payload
     * @param string           $message
     * @return Response|ResponseFactory
     */
    public static function success(
        string $message = '',
        $payload = [],
    ): Response|ResponseFactory {
        $message = $message != '' ? $message : __('message.success');
        return ResponseApi::response(
            $message,
            Response::HTTP_OK,
            $payload
        );
    }

    /**
     * Response
     *
     * @param                  $messages
     * @param int              $responseCode
     * @param                  $payload
     * @param int              $statusCode
     * @return Response|ResponseFactory
     */
    public static function response(
        $message,
        int $responseCode,
        $payload,
        int $statusCode = Response::HTTP_OK,
    ): Response|ResponseFactory {
        $makePayload = [
            "code" => $responseCode,
            "message" => $message,
        ];

        if ($payload && !Arr::get($payload, 'errors')) {
            $makePayload["data"] = $payload;
        } else if (Arr::get($payload, 'errors')) {
            $makePayload["errors"] = $payload['errors'];
        }

        return response($makePayload, $statusCode);
    }

    /**
     * list search
     *
     * @param                      $list
     * @param LengthAwarePaginator $pager
     * @param array                $custom
     * @return ResponseFactory|Response
     */
    public static function search(
        $list,
        LengthAwarePaginator $pager,
        array $custom = [],
    ): Response|ResponseFactory {
        $payload = array_merge([
            "list" => $list,
        ], $custom);

        $response = ResponseApi::response(
            "success",
            Response::HTTP_OK,
            $payload
        );

        $allItem = $pager->total();

        $first = ($pager->currentPage() - 1) * $pager->perPage();
        $until = $pager->currentPage() * $pager->perPage() - 1;
        if ($allItem > 0 && $allItem < $until) {
            $until = $allItem - 1;
        }

        $response->headers->add([
            "Content-Range" => "${first}-${until}/${allItem}",
        ]);

        return $response;
    }

    /**
     * Error 400
     *
     * @param array          $payload
     * @param string         $message
     * @param int            $responseCode
     * @param Exception|null $exception
     * @return Response|ResponseFactory
     */
    public static function bad(
        array     $payload = [],
        string    $message = '',
        int       $responseCode = Response::HTTP_BAD_REQUEST,
        Exception $exception = null,
    ): Response|ResponseFactory {
        if ($exception) {
            $payload['errors'] = [
                'message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ];
        }

        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.error'),
            $responseCode,
            $payload,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Unauthorized
     *
     * @param string $message
     * @param int    $code
     * @return Response|ResponseFactory
     */
    public static function unauthorized(
        string $message = "",
        int $code = Response::HTTP_UNAUTHORIZED
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.unauthorized'),
            Response::HTTP_UNAUTHORIZED,
            [],
            $code
        );
    }

    /**
     * created
     *
     * @param        $payload
     * @param string $message
     * @param int    $responseCode
     * @return Response|ResponseFactory
     */
    public static function created(
        $payload = [],
        string $message = "",
        int    $responseCode = Response::HTTP_CREATED
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.create_success'),
            $responseCode,
            $payload,
            Response::HTTP_CREATED
        );
    }

    /**
     * error 403
     *
     * @param null   $responseCode
     * @param array  $payload
     * @param string $message
     * @return Response|ResponseFactory
     */
    public static function forbidden(
        $responseCode = null,
        array $payload = [],
        string $message = ""
    ): Response|ResponseFactory {
        if (!$responseCode) {
            $responseCode = Response::HTTP_FORBIDDEN;
        }

        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.forbidden'),
            $responseCode,
            $payload,
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * data not found 404
     *
     * @param string $message
     * @param int    $httpResponseCode
     * @param array  $data
     * @return Response|ResponseFactory
     */
    public static function dataNotFound(
        string $message = '',
        int    $httpResponseCode = Response::HTTP_NOT_FOUND,
        array  $data = []
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.not_found_data'),
            $httpResponseCode,
            $data,
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Resource not found
     *
     * @param array  $payload
     * @param string $message
     * @param int    $httpResponseCode
     * @return Response|ResponseFactory
     */
    public static function resourceNotFound(
        array  $payload = [],
        string $message = '',
        int    $httpResponseCode = Response::HTTP_NOT_FOUND,
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.not_found_data'),
            $httpResponseCode,
            $payload,
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Validate
     *
     * @param array $payload
     * @param       $messages
     * @param int   $statusCode
     * @return Response|ResponseFactory
     */
    public static function validationError(
        array $payload,
        $messages,
        int   $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY,
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $messages,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $payload,
            $statusCode
        );
    }

    /**
     * error 500
     *
     * @param string         $message
     * @param null           $responseCode
     * @param Exception|null $exception
     * @return ResponseFactory|Response
     */
    public static function error(
        string    $message = 'Server Error',
        $responseCode = null,
        Exception $exception = null,
    ): Response|ResponseFactory {
        if (!$responseCode) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        if ($exception) {
            $payload['errors'] = [
                'message' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine()
            ];

            return ResponseApi::response(
                $message,
                $responseCode,
                $payload,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return ResponseApi::response(
            $message,
            $responseCode,
            [],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Request expired - 408
     *
     * @param string $message
     * @param int    $responseCode
     *
     * @return ResponseFactory|Response
     */
    public static function expired(
        string $message = '',
        int    $responseCode = Response::HTTP_REQUEST_TIMEOUT,
    ): Response|ResponseFactory {
        return ResponseApi::response(
            $message = $message != '' ? $message : __('message.expired'),
            $responseCode,
            [],
            Response::HTTP_REQUEST_TIMEOUT
        );
    }
}

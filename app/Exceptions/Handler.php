<?php

namespace App\Exceptions;

use App\Common\ResponseApi;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirm',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function(Throwable $exception) {
            //
        });

    }

    /**
     * @param $request
     * @param Throwable $e
     * @return Application|Factory|View|\Illuminate\Foundation\Application|JsonResponse|RedirectResponse|\Illuminate\Http\Response|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): \Illuminate\Foundation\Application|View|Factory|\Illuminate\Http\Response|JsonResponse|RedirectResponse|Application|Response
    {
//        dd($e);
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    public function handleApiException($request, Throwable $e): \Illuminate\Foundation\Application|View|Factory|\Illuminate\Http\Response|JsonResponse|RedirectResponse|Application|Response
    {
        if ($e instanceof HttpException) {
            return $this->buildResponseHttpExceptionAPI($e);
        }

        if ($e instanceof ModelNotFoundException) {
            return ResponseApi::dataNotFound();
        }

        if ($e instanceof AuthorizationException) {
            return ResponseApi::forbidden();
        }

        if ($e instanceof AuthenticationException) {
            return ResponseApi::unauthorized();
        }

        if ($e instanceof RouteNotFoundException) {
            return ResponseApi::bad();
        }

        if($e instanceof ApiException) {
            $message = $e->getMessage();
            $statusCode = $e->getCode();
            $errors = $e->getData();

            return ResponseApi::error($message, $statusCode, $errors);
        }

        return ResponseApi::error();
    }

    public function buildResponseHttpExceptionAPI(HttpException $e): \Illuminate\Foundation\Application|View|Factory|\Illuminate\Http\Response|JsonResponse|RedirectResponse|Application|Response
    {
        $statusCode = $e->getStatusCode();

        return match ($statusCode) {
            400 => ResponseApi::bad(),
            401 => ResponseApi::unauthorized(),
            403 => ResponseApi::forbidden(),
            404 => ResponseApi::dataNotFound(),
            default => ResponseApi::error(),
        };
    }
}

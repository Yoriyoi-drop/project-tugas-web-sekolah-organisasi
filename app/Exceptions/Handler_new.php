<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Exception $e) {
            // Log all exceptions with context
            Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
            ]);
        });

        $this->renderable(function (Exception $e, Request $request) {
            return $this->handleApiException($e, $request);
        });
    }

    /**
     * Handle API exceptions and return consistent JSON responses.
     */
    protected function handleApiException(Exception $e, Request $request): JsonResponse|null
    {
        if (!$request->expectsJson() && !$request->is('api/*')) {
            return null; // Let Laravel handle web requests
        }

        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Internal Server Error';
        $errors = null;

        // Handle specific exception types
        switch (true) {
            case $e instanceof ValidationException:
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
                $message = 'Validation failed';
                $errors = $e->errors();
                break;

            case $e instanceof AuthenticationException:
                $status = Response::HTTP_UNAUTHORIZED;
                $message = 'Unauthenticated';
                break;

            case $e instanceof AuthorizationException:
                $status = Response::HTTP_FORBIDDEN;
                $message = 'Unauthorized';
                break;

            case $e instanceof ModelNotFoundException:
                $status = Response::HTTP_NOT_FOUND;
                $message = 'Resource not found';
                break;

            case $e instanceof NotFoundHttpException:
                $status = Response::HTTP_NOT_FOUND;
                $message = 'Endpoint not found';
                break;

            case $e instanceof MethodNotAllowedHttpException:
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $message = 'Method not allowed';
                break;

            case $e instanceof HttpException:
                $status = $e->getStatusCode();
                $message = $e->getMessage() ?: 'Http Exception';
                break;
        }

        // Log error for debugging
        if ($status >= 500) {
            Log::error('API Error', [
                'status' => $status,
                'message' => $message,
                'exception' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString(),
            'path' => $request->path(),
        ], $status);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'timestamp' => now()->toISOString(),
                'path' => $request->path(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('login'));
    }
}

<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// Exception Type Symfony
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
// Exception Type Illuminate
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
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
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (Throwable $e, Request $request) {
            // Cek jika request bukan dari api 
            if (!$request->is('api/*')) {
                return;
            }

            // Default Error and message
            $statusCode = 500;
            $message = [
                env('APP_ENV') == 'local' && env('APP_DEBUG')
                    ? 'Internal server error ' . $e->getMessage()
                    : 'Internal server error',
            ];

            // Case Exception Validation
            if ($e instanceof ValidationException) {
                $messages = [];

                foreach ($e->errors() as $error) {
                    $messages[] = $error[0];
                }

                $message = $messages;
                $statusCode = 422;
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException || $e instanceof ItemNotFoundException) {
                $statusCode = 404;
                $message = ['The data or route you\'re looking for couldn\'t be found!'];
            }

            if ($e instanceof UnauthorizedException || $e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
                $statusCode = 401;
                $message = $e->getMessage();
            }

            if ($e instanceof UnprocessableEntityHttpException) {
                $statusCode = 400;
                $message = $e->getMessage();
            }

            if ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
                $message = $e->getMessage();
            }

            return response()->json([
                'code' => $statusCode,
                'message' => $message,
                'errors' => []
            ], $statusCode);
        });
    }
}

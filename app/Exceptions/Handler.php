<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
        });
    }
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    // public function render($request, Throwable $e): Response
    // {
    //     // Handle ValidationException specifically
    //     if ($e instanceof ValidationException) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     }

    //     // Handle ModelNotFoundException specifically
    //     if ($e instanceof ModelNotFoundException) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Resource not found',
    //         ], 404);
    //     }

    //     // Handle unexpected errors by catching all other exceptions
    //     if (!$e instanceof ValidationException && !$e instanceof ModelNotFoundException && !$e instanceof AuthenticationException) {
    //         // Log the error for internal tracking, but don't expose sensitive info to users
    //         Log::error('Unexpected error: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An unexpected error occurred. Please try again later.'
    //         ], 400); // Return a 400 Bad Request instead of a 500
    //     }

    //     // Default response if no specific handling applies
    //     return parent::render($request, $e);
    // }
}

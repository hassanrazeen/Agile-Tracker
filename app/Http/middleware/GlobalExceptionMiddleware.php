<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use League\OAuth2\Server\Exception\OAuthServerException;

class GlobalExceptionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (Throwable $exception) {

            if ($request->is('api/*')) {
                return $this->handleApiException($exception);
            }

            return response()->view('errors.500', [], 500);
        }
    }

    private function handleApiException(Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You are not authenticated.',
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'messages' => $exception->errors(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'The requested resource was not found.',
            ], 404);
        }

        if ($exception instanceof OAuthServerException) {
            return response()->json([
                'error' => $exception->getErrorType(),
                'message' => $exception->getMessage(),
                'hint' => $exception->getHint(),
            ], $exception->getHttpStatusCode());
        }

        return response()->json([
            'error' => 'Server Error',
            'message' => "Internal Server Error",
            'error-message' => $exception->getMessage()
        ], 500);
    }
}

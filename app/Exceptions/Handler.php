<?php


namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    // Other methods...

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Check if the request is an API request
        if ($request->wantsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Default response
        $response = [
            'message' => 'An error occurred.',
            'error' => $e->getMessage(),
        ];

        // Customize the response for specific exceptions
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $response['message'] = $e->getMessage() ?: 'HTTP Error';
        } else {
            $statusCode = 500;
        }

        // // Optionally log the exception
        // if (config('app.debug')) {
        //     $response['trace'] = $e->getTrace();
        // }

        return response()->json($response, $statusCode);
    }
}

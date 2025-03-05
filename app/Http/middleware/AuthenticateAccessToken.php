<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class AuthenticateAccessToken
{
    public function handle(Request $request, Closure $next)
    {
        try {

            // Check if Authorization header exists
            $authorization = $request->header('Authorization');

            if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'Access token is required',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Extract the token from the header
            $accessToken = substr($authorization, 7); // Remove "Bearer " prefix

            return $next($request);
        } catch (\Exception $e) {
            Log::error('Token authentication failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'Something went wrong'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

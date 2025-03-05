<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Convert fields to lowercase
            $validatedData['first_name'] = strtolower($validatedData['first_name']);
            $validatedData['last_name'] = strtolower($validatedData['last_name']);
            $validatedData['email'] = strtolower($validatedData['email']);

            // Create the user
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate an access token for the user
            $token = $user->createToken('AuthToken')->accessToken;

            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Log in a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $validatedData['email'] = strtolower($validatedData['email']);

            // Find the user by email
            $user = User::where('email', $validatedData['email'])->first();

            // Check if the user exists
            if (!$user) {
                return response()->json([
                    "message" => "user with email '{$validatedData['email']}' not found",
                    'error' => 'User not found',
                ], 404);
            }

            // Verify the password
            if (!Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'error' => 'Invalid credentials',
                    'message' => 'The provided credentials do not match our records.',
                ], 401);
            }

            // Generate an access token for the user
            $token = $user->createToken('AuthToken')->accessToken;

            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Log the user out (revoke the token).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Revoke the user's token
            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong during logout',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}

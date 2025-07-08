<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientsTransferController extends Controller
{
    /**
     * Register a user from smart system
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register_user(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $request->validate([
                'email' => 'required|email',
                'name' => 'required|string|max:255',
                'phone' => 'sometimes|string|max:20',
            ]);

            // Here you would implement the user registration logic
            // For now, return a success response
            
            return response()->json([
                'success' => true,
                'message' => 'User registration initiated',
                'data' => [
                    'email' => $request->email,
                    'timestamp' => now()->toISOString()
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration'
            ], 500);
        }
    }
}

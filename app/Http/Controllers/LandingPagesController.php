<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LandingPagesController extends Controller
{
    /**
     * Handle lead capture from landing pages
     *
     * @param Request $request
     * @param string|null $source
     * @param int|null $pipeline_id
     * @return JsonResponse
     */
    public function LeadCapture(Request $request, $source = null, $pipeline_id = null): JsonResponse
    {
        try {
            // Log the lead capture attempt
            Log::info('Lead capture attempt', [
                'source' => $source,
                'pipeline_id' => $pipeline_id,
                'data' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Validate basic required fields
            $request->validate([
                'email' => 'required|email',
                'name' => 'sometimes|string|max:255',
                'phone' => 'sometimes|string|max:20',
            ]);

            // Here you would typically save the lead to database
            // For now, we'll just return a success response
            
            return response()->json([
                'success' => true,
                'message' => 'Lead captured successfully',
                'data' => [
                    'source' => $source,
                    'pipeline_id' => $pipeline_id,
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
            Log::error('Lead capture error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while capturing the lead'
            ], 500);
        }
    }
}

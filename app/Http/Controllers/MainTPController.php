<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MainTPController extends Controller
{
    /**
     * Get financial data for Phoenix system
     */
    public function get_financial_data_phoenix($broker_id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'broker_id' => $broker_id
        ]);
    }

    /**
     * Get online status for Phoenix system
     */
    public function get_online_status_phoenix($broker_id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'online' => true,
            'broker_id' => $broker_id
        ]);
    }

    /**
     * Get financial data
     */
    public function get_financial_data($broker_id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'broker_id' => $broker_id
        ]);
    }

    /**
     * Get opened data
     */
    public function get_opened_data($broker_id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
            'broker_id' => $broker_id
        ]);
    }
}

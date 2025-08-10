<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function getBanksByCountry(Request $request)
    {
        try {
            $request->validate([
                'country' => 'required|string'
            ]);

            $banks = Bank::where('country', $request->country)
                        ->where('is_active', true)
                        ->get(['id', 'name', 'country', 'swift_code', 'account_number', 'beneficiary_name', 'iban', 'bic']);
            return response()->json($banks);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getBankDetails(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|integer'
        ]);

        $bank = Bank::where('id', $request->bank_id)
                    ->where('is_active', true)
                    ->first();
        
        if (!$bank) {
            return response()->json(['error' => 'Bank not found'], 404);
        }
        
        return response()->json($bank);
    }
}
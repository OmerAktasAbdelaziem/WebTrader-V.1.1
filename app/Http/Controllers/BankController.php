<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function getBanksByCountry(Request $request)
    {
        $banks = Bank::where('country_code', $request->country_code)->get();
        return response()->json($banks);
    }

    public function getBankDetails(Request $request)
    {
        $bank = Bank::find($request->bank_id);
        return response()->json($bank);
    }
}
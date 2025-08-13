<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Bank;
use App\Models\Client;
use App\Models\MoneyTrx;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

//Service
use App\Http\Services\Api\Crm\CrmApiServiceInterface;

class ClientsController extends Controller
{
    
    protected $crmApiService;
    public function showKycForm()
    {
        return view('clientarea.kyc');
    }

    public function uploadKycPhoto(Request $request)
    {
        $request->validate([
            'ark_data' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photo = $request->file('ark_data');
        $photoPath = $photo->store('kyc_photos', 'public');

        $client = Auth::guard('client')->user();
        if ($client) {
            $client->ark_data = $photoPath;
            $client->save();

            return back()->with('success', __('web.photo_uploaded_successfully'));
        } else {
            return back()->with('error', __('web.user_not_authenticated'));
        }
    }

    public function showTradingPlatform()
    {
        $countries = Bank::distinct('country')->pluck('country');
        return view('clientarea.tradingplatform', compact('countries'));
    }

    public function showResetPasswordForm()
    {
        return view('clientarea.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|confirmed',
        ]);

        $user = Auth::guard('client')->user();
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        $client = Client::find($user->id);

        $options = $client->options??[];
        if (isset($options['forceChangePassword'])) {
            unset($options['forceChangePassword']);
        }

        $client->update([
            'password_text' => $request->new_password,
            'password'      => Hash::make($request->new_password),
            'options'       => $options,
        ]);

        return redirect()->route('client.webtrader')->with('success', __('web.password_successfully_updated'));
    }

    public function showDepositForm(Request $request)
    {
        $user = auth()->guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        $countries          = Bank::distinct('country')->pluck('country');
        $banks              = Bank::where('is_active', 1)->latest()->get();
        $allDeposits        = MoneyTrx::with('bank_details')
                                    ->where('broker_id', $user->broker_id)
                                    ->where('type', 'deposit')
                                    ->orderByDesc('created_at')
                                    ->get();
        $pendingDeposits    = $allDeposits->where('status', 'pending');
        $nonPendingDeposits = $allDeposits->where('status', '!=', 'pending');

        // Get finance data for balance display
        $finance = $this->get_financial_data($user->broker_id);

        // Get USDT wallet address: pipelines.usdt, fallback to clients.usdt
        $usdtWalletAddress = null;
        
        // First try pipeline USDT
        if ($user->pipeline_id) {
            $pipeline = DB::table('pipelines')->where('id', $user->pipeline_id)->first();
            if ($pipeline && !empty($pipeline->usdt)) {
                $pipelineUsdt = $pipeline->usdt;
                
                // Handle JSON format for pipeline USDT
                if (is_string($pipelineUsdt)) {
                    $decodedUsdt = json_decode($pipelineUsdt, true);
                    if (is_array($decodedUsdt)) {
                        // Get address based on client source or first available
                        if ($user->source == 'BNC' && !empty($decodedUsdt['BNC'])) {
                            $usdtWalletAddress = $decodedUsdt['BNC'];
                        } elseif (!empty($decodedUsdt['phoenix'])) {
                            $usdtWalletAddress = $decodedUsdt['phoenix'];
                        } else {
                            // Get the first non-null address
                            foreach ($decodedUsdt as $key => $address) {
                                if (!empty($address) && $address !== null) {
                                    $usdtWalletAddress = $address;
                                    break;
                                }
                            }
                        }
                    } else {
                        $usdtWalletAddress = trim($pipelineUsdt);
                    }
                } elseif (is_array($pipelineUsdt)) {
                    // Handle if already decoded array
                    if ($user->source == 'BNC' && !empty($pipelineUsdt['BNC'])) {
                        $usdtWalletAddress = $pipelineUsdt['BNC'];
                    } elseif (!empty($pipelineUsdt['phoenix'])) {
                        $usdtWalletAddress = $pipelineUsdt['phoenix'];
                    } else {
                        // Get the first non-null address
                        foreach ($pipelineUsdt as $key => $address) {
                            if (!empty($address) && $address !== null) {
                                $usdtWalletAddress = $address;
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        // Fallback to client's own USDT if pipeline doesn't have one
        if (!$usdtWalletAddress && !empty($user->usdt)) {
            $clientUsdt = $user->usdt;
            
            // Handle JSON format for client USDT
            if (is_string($clientUsdt)) {
                $decodedUsdt = json_decode($clientUsdt, true);
                if (is_array($decodedUsdt)) {
                    // Get address based on client source or first available
                    if ($user->source == 'BNC' && !empty($decodedUsdt['BNC'])) {
                        $usdtWalletAddress = $decodedUsdt['BNC'];
                    } elseif (!empty($decodedUsdt['phoenix'])) {
                        $usdtWalletAddress = $decodedUsdt['phoenix'];
                    } else {
                        // Get the first non-null address
                        foreach ($decodedUsdt as $key => $address) {
                            if (!empty($address) && $address !== null) {
                                $usdtWalletAddress = $address;
                                break;
                            }
                        }
                    }
                } else {
                    $usdtWalletAddress = trim($clientUsdt);
                }
            } elseif (is_array($clientUsdt)) {
                // Handle if already decoded array
                if ($user->source == 'BNC' && !empty($clientUsdt['BNC'])) {
                    $usdtWalletAddress = $clientUsdt['BNC'];
                } elseif (!empty($clientUsdt['phoenix'])) {
                    $usdtWalletAddress = $clientUsdt['phoenix'];
                } else {
                    // Get the first non-null address
                    foreach ($clientUsdt as $key => $address) {
                        if (!empty($address) && $address !== null) {
                            $usdtWalletAddress = $address;
                            break;
                        }
                    }
                }
            }
        }

        return view('clientarea.deposit', compact('countries', 'banks', 'pendingDeposits', 'nonPendingDeposits', 'allDeposits', 'finance', 'usdtWalletAddress'));
    }

    public function getBanksByCountry(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
        ]);

        $banks = Bank::where('country', $request->country)->get(['id', 'name']);

        return response()->json($banks);
    }

    public function getBankDetails(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|integer|exists:banks,id',
        ]);

        $bank = Bank::find($request->bank_id);

        if (!$bank) {
            return response()->json(['message' => 'Bank not found'], 404);
        }

        return response()->json($bank);
    }

    public function processDeposit(Request $request)
    {
        Log::info('=== DEPOSIT FORM SUBMISSION START ===', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'files' => $request->allFiles(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);
        
        $user = Auth::guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        // Handle both possible field names for deposit method
        $depositMethod = $request->input('deposit_method') ?? $request->input('payment_method');
        
        // Map payment_method values to deposit_method values if needed
        $methodMapping = [
            'bank_transfer' => 'bank',
            'cryptocurrency' => 'crypto',
            'credit_card' => 'credit_card'
        ];
        
        if (isset($methodMapping[$depositMethod])) {
            $depositMethod = $methodMapping[$depositMethod];
        }
        
        // Basic validation for all deposit types
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);
        
        // Validate that we have a valid deposit method
        if (!in_array($depositMethod, ['bank', 'credit_card', 'crypto'])) {
            return redirect()->back()->with('error', 'Invalid deposit method selected.');
        }
        
        // Debug: Log which method we're processing
        Log::info('Processing deposit method', [
            'method' => $depositMethod,
            'is_credit_card' => $depositMethod === 'credit_card',
            'broker_id' => $user->broker_id
        ]);

        // Handle credit card deposits
        if ($depositMethod === 'credit_card') {
            Log::info('Entering credit card processing section', ['broker_id' => $user->broker_id]);
            
            try {
                // Validate credit card fields - using actual form field names
                $request->validate([
                    'card_number' => 'required|string|min:13|max:19',
                    'card_expiry' => 'required|string|size:5|regex:/^\d{2}\/\d{2}$/',
                    'card_cvv' => 'required|string|min:2|max:4',
                    'card_holder_name' => 'required|string|max:255',
                ]);
                
                Log::info('Credit card validation passed', [
                    'card_number_length' => strlen($request->input('card_number')),
                    'has_cardholder_name' => !empty($request->input('card_holder_name'))
                ]);

                // Store full credit card details (WARNING: Security risk - only for internal use)
                $cardNumber = preg_replace('/\s+/', '', $request->input('card_number'));
                
                $creditCardDetails = [
                    'card_number' => $cardNumber, // WARNING: Storing full card number
                    'card_expiry' => $request->input('card_expiry'),
                    'card_cvv' => $request->input('card_cvv'), // WARNING: Storing CVV
                    'card_holder_name' => $request->input('card_holder_name'),
                    'card_type' => $this->detectCardType($cardNumber),
                    'processed_at' => now()->toISOString(),
                ];

                $depositData = [
                    'broker_id' => $user->broker_id,
                    'bank_id' => null,
                    'receipt' => null,
                    'amount' => $request->input('amount'),
                    'status' => 'pending',
                    'type' => 'deposit',
                    'method' => 'credit_card',
                    'usdt' => null,
                    'bank_details' => null,
                    'credit_card_details' => $creditCardDetails,
                ];

                Log::info('About to create credit card deposit', ['deposit_data' => $depositData]);
                
                $moneyTrx = MoneyTrx::create($depositData);
                Log::info('Credit card deposit created successfully', ['transaction_id' => $moneyTrx->id, 'broker_id' => $user->broker_id]);
                return redirect()->back()->with('success', 'Credit card deposit submitted successfully!');
                
            } catch (\Illuminate\Validation\ValidationException $e) {
                Log::error('Credit card validation failed', ['errors' => $e->errors(), 'broker_id' => $user->broker_id]);
                return redirect()->back()->with('error', 'Please check your credit card details: ' . implode(', ', $e->validator->errors()->all()));
            } catch (\Exception $e) {
                Log::error('Credit card deposit failed', ['error' => $e->getMessage(), 'broker_id' => $user->broker_id]);
                return redirect()->back()->with('error', 'Failed to process credit card deposit. Please try again.');
            }
        }

        // Handle bank transfer deposits
        if ($depositMethod === 'bank') {
            $request->validate([
                'country' => 'required|string',
                'bank_id' => 'required|exists:banks,id',
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $receiptPath = $request->file('receipt')->store('public/receipts');
            $bank = Bank::find($request->input('bank_id'));

            $depositData = [
                'broker_id'    => $user->broker_id,
                'bank_id'      => $bank->id,
                'receipt'      => url(str_replace('public/', 'storage/', $receiptPath)),
                'amount'       => $request->input('amount'),
                'status'       => 'pending',
                'type'         => 'deposit',
                'method'       => 'bank',
                'usdt'         => null,
                'bank_details' => null,
                'credit_card_details' => null,
            ];

            try {
                Log::info('=== ATTEMPTING TO CREATE BANK DEPOSIT ===', [
                    'depositData' => $depositData,
                    'user_id' => $user->id,
                    'broker_id' => $user->broker_id
                ]);
                
                $moneyTrx = MoneyTrx::create($depositData);
                
                Log::info('=== BANK DEPOSIT CREATED SUCCESSFULLY ===', [
                    'transaction_id' => $moneyTrx->id,
                    'broker_id' => $user->broker_id,
                    'method' => $depositMethod,
                    'amount' => $moneyTrx->amount,
                    'status' => $moneyTrx->status
                ]);
                
                return redirect()->back()->with('success', __('web.deposit_request_submitted_successfully'));
            } catch (\Exception $e) {
                Log::error('=== BANK DEPOSIT CREATION FAILED ===', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'broker_id' => $user->broker_id,
                    'data' => $depositData,
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
                return redirect()->back()->with('error', 'Failed to process bank deposit. Error: ' . $e->getMessage());
            }
        }
        // Handle crypto deposits
        elseif ($depositMethod === 'crypto') {
            $request->validate([
                'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $receiptPath = $request->file('receipt')->store('public/receipts');

            $depositData = [
                'broker_id'    => $user->broker_id,
                'bank_id'      => null,
                'receipt'      => url(str_replace('public/', 'storage/', $receiptPath)),
                'amount'       => $request->input('amount'),
                'status'       => 'pending',
                'type'         => 'deposit',
                'method'       => 'crypto',
                'usdt'         => $request->input('wallet_address'),
                'bank_details' => null,
                'credit_card_details' => null,
            ];

            try {
                Log::info('=== ATTEMPTING TO CREATE CRYPTO DEPOSIT ===', [
                    'depositData' => $depositData,
                    'user_id' => $user->id,
                    'broker_id' => $user->broker_id
                ]);
                
                $moneyTrx = MoneyTrx::create($depositData);
                
                Log::info('=== CRYPTO DEPOSIT CREATED SUCCESSFULLY ===', [
                    'transaction_id' => $moneyTrx->id,
                    'broker_id' => $user->broker_id,
                    'method' => $depositMethod,
                    'amount' => $moneyTrx->amount,
                    'status' => $moneyTrx->status
                ]);
                
                return redirect()->back()->with('success', __('web.deposit_request_submitted_successfully'));
            } catch (\Exception $e) {
                Log::error('=== CRYPTO DEPOSIT CREATION FAILED ===', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'broker_id' => $user->broker_id,
                    'data' => $depositData,
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
                return redirect()->back()->with('error', 'Failed to process crypto deposit. Error: ' . $e->getMessage());
            }
        }
        else {
            return redirect()->back()->with('error', 'Invalid deposit method selected.');
        }
    }

    /**
     * Detect credit card type based on card number
     */
    private function detectCardType($cardNumber)
    {
        $cardNumber = preg_replace('/\s+/', '', $cardNumber);
        
        if (preg_match('/^4/', $cardNumber)) {
            return 'Visa';
        } elseif (preg_match('/^5[1-5]/', $cardNumber)) {
            return 'MasterCard';
        } elseif (preg_match('/^3[47]/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6(?:011|5)/', $cardNumber)) {
            return 'Discover';
        } else {
            return 'Unknown';
        }
    }

    public function showWithdrawForm()
    {
        $user = auth()->guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        $finance = $this->get_financial_data($user->broker_id);
        $allWithdrawals = MoneyTrx::where('broker_id', $user->broker_id)
            ->where('type', 'withdraw')
            ->orderByDesc('created_at')
            ->get();
        $acceptedWithdrawals = $allWithdrawals->where('status', 'approved');
        $pendingWithdrawals = $allWithdrawals->where('status', 'pending');
        $rejectedWithdrawals = $allWithdrawals->where('status', 'rejected');

        return view('clientarea.withdraw', compact('allWithdrawals', 'acceptedWithdrawals', 'pendingWithdrawals', 'rejectedWithdrawals', 'finance'));
    }

    public function submitWithdrawForm(Request $request)
    {
        Log::info('=== WITHDRAWAL FORM SUBMISSION START ===', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);
        $bankTransferRule = 'nullable|string|required_if:payment_method,bank_transfer';
        $cryptoRule = 'nullable|string|required_if:payment_method,cryptocurrency';
        
        try {
            $request->validate([
                'payment_method'      => 'required|string|in:bank_transfer,cryptocurrency',
                'amount'              => 'required|numeric|min:1',
                // Bank transfer fields
                'account_name'        => $bankTransferRule,  // Changed from account_holder to account_name
                'bank_name'           => $bankTransferRule,
                'account_number'      => $bankTransferRule,
                'swift_code'          => $bankTransferRule,
                // Cryptocurrency fields
                'crypto_type'         => $cryptoRule,
                'crypto_address'      => $cryptoRule,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e; // Re-throw for normal form handling
        }

        $user    = Auth::guard('client')->user();
        $options = $user->options??[];
        $finance = $this->get_financial_data($user->broker_id);
        $return  = true;

        // Debug: Log the request data
        Log::info('Withdrawal request data', ['data' => $request->all(), 'broker_id' => $user->broker_id]);

        if ($request->amount > ($finance['balance'] ?? 0)) {
            if (!isset($options['canWithdrawalCredit'])) {
                if (!isset($options['canWithdrawalBonus'])) {
                    $return = false;
                }
                if ($request->amount > (($finance['balance'] ?? 0) + ($finance['bonus'] ?? 0))) {
                    $return = false;
                }
            }
            if ($request->amount > (($finance['balance'] ?? 0) + ($finance['credit'] ?? 0))) {
                $return = false;
            }
        }

        if (!$return) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => __('web.not_enough_balance')]);
            }
            return redirect()->back()->with('fail', __('web.not_enough_balance'));
        }

        // Create withdrawal transaction based on payment method
        try {
            if ($request->payment_method === 'cryptocurrency') {
                $moneyTrx = MoneyTrx::create([
                    'broker_id'    => $user->broker_id,
                    'amount'       => $request->amount,
                    'method'       => 'cryptocurrency',
                    'type'         => 'withdraw',
                    'status'       => 'pending',
                    'crypto_details' => [
                        'crypto_type'    => $request->crypto_type,
                        'wallet_address' => $request->crypto_address,
                    ],
                ]);
                Log::info('Cryptocurrency withdrawal created successfully', ['transaction_id' => $moneyTrx->id, 'broker_id' => $user->broker_id]);
            } else {
                $moneyTrx = MoneyTrx::create([
                    'broker_id'    => $user->broker_id,
                    'amount'       => $request->amount,
                    'method'       => 'bank_transfer',
                    'type'         => 'withdraw',
                    'status'       => 'pending',
                    'bank_details' => [
                        'account_holder'  => $request->account_name,  // Changed from account_holder to account_name
                        'bank_name'       => $request->bank_name,
                        'account_number'  => $request->account_number,
                        'swift_code'      => $request->swift_code,
                    ],
                ]);
                Log::info('Bank transfer withdrawal created successfully', ['transaction_id' => $moneyTrx->id, 'broker_id' => $user->broker_id]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => __('web.withdraw_request_submitted_successfully')
                ], 200, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->with('success', __('web.withdraw_request_submitted_successfully'));
        } catch (\Exception $e) {
            Log::error('Withdrawal submission failed', ['error' => $e->getMessage(), 'broker_id' => $user->broker_id, 'trace' => $e->getTraceAsString()]);
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'An error occurred while submitting the withdrawal request: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('fail', 'An error occurred while submitting the withdrawal request: ' . $e->getMessage());
        }
    }

    // New withdrawal methods for modern interface
    public function processWithdrawal(Request $request)
    {
        Log::info('=== NEW WITHDRAWAL SUBMISSION START ===', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        $withdrawal_method = $request->input('withdrawal_method');
        
        // Define validation rules based on withdrawal method
        $rules = [
            'withdrawal_method' => 'required|string|in:bank_transfer,cryptocurrency,paypal',
            'amount' => 'required|numeric|min:10',
        ];

        // Add method-specific validation rules
        switch($withdrawal_method) {
            case 'bank_transfer':
                $rules += [
                    'bank_name' => 'required|string|max:255',
                    'account_number' => 'required|string|max:255',
                    'account_holder_name' => 'required|string|max:255',
                    'swift_code' => 'nullable|string|max:11',
                ];
                break;
            case 'cryptocurrency':
                $rules += [
                    'crypto_type' => 'required|string|in:BTC,ETH,USDT,LTC',
                    'wallet_address' => 'required|string|max:255',
                    'network_type' => 'nullable|string|in:ERC20,TRC20,BEP20',
                ];
                break;
            case 'paypal':
                $rules += [
                    'paypal_email' => 'required|email|max:255',
                    'paypal_confirm_email' => 'required|email|same:paypal_email',
                ];
                break;
        }

        try {
            $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        $user = Auth::guard('client')->user();
        $options = $user->options ?? [];
        $finance = $this->get_financial_data($user->broker_id);

        // Check if user has sufficient balance
        if ($request->amount > ($finance['balance'] ?? 0)) {
            if (!isset($options['canWithdrawalCredit'])) {
                if (!isset($options['canWithdrawalBonus'])) {
                    if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => __('web.not_enough_balance')]);
                    }
                    return redirect()->back()->with('fail', __('web.not_enough_balance'));
                }
                if ($request->amount > (($finance['balance'] ?? 0) + ($finance['bonus'] ?? 0))) {
                    if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => __('web.not_enough_balance')]);
                    }
                    return redirect()->back()->with('fail', __('web.not_enough_balance'));
                }
            }
            if ($request->amount > (($finance['balance'] ?? 0) + ($finance['credit'] ?? 0))) {
                if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => __('web.not_enough_balance')]);
                }
                return redirect()->back()->with('fail', __('web.not_enough_balance'));
            }
        }

        try {
            // Prepare details based on withdrawal method
            $details = [];
            $method_display = '';

            switch($withdrawal_method) {
                case 'bank_transfer':
                    $details = [
                        'bank_name' => $request->bank_name,
                        'account_number' => $request->account_number,
                        'account_holder_name' => $request->account_holder_name,
                        'swift_code' => $request->swift_code,
                    ];
                    $method_display = 'Bank Transfer';
                    break;
                case 'cryptocurrency':
                    $details = [
                        'crypto_type' => $request->crypto_type,
                        'wallet_address' => $request->wallet_address,
                        'network_type' => $request->network_type,
                    ];
                    $method_display = 'Cryptocurrency (' . $request->crypto_type . ')';
                    break;
                case 'paypal':
                    $details = [
                        'paypal_email' => $request->paypal_email,
                    ];
                    $method_display = 'PayPal';
                    break;
            }

            // Create withdrawal transaction
            $moneyTrx = MoneyTrx::create([
                'broker_id' => $user->broker_id,
                'amount' => $request->amount,
                'method' => $withdrawal_method,
                'type' => 'withdraw',
                'status' => 'pending',
                'details' => $details,
                'method_display' => $method_display,
            ]);

            Log::info('New withdrawal created successfully', [
                'transaction_id' => $moneyTrx->id,
                'broker_id' => $user->broker_id,
                'method' => $withdrawal_method,
                'amount' => $request->amount
            ]);

            // Calculate new balance
            $newBalance = $finance['balance'] - $request->amount;

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('web.withdrawal_submitted_successfully'),
                    'new_balance' => $newBalance,
                    'transaction_id' => $moneyTrx->id
                ], 200);
            }
            return redirect()->back()->with('success', __('web.withdrawal_submitted_successfully'));

        } catch (\Exception $e) {
            Log::error('New withdrawal submission failed', [
                'error' => $e->getMessage(),
                'broker_id' => $user->broker_id,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('web.withdrawal_submission_failed') . ': ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('fail', __('web.withdrawal_submission_failed') . ': ' . $e->getMessage());
        }
    }

    public function getWithdrawalHistory(Request $request)
    {
        $user = Auth::guard('client')->user();
        $type = $request->input('type', 'all');
        
        $query = MoneyTrx::where('broker_id', $user->broker_id)
                         ->where('type', 'withdraw')
                         ->orderBy('created_at', 'desc');

        switch($type) {
            case 'pending':
                $query->whereIn('status', ['pending', 'processing']);
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'all':
            default:
                // No additional filter for all withdrawals
                break;
        }

        $withdrawals = $query->get()->map(function($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'created_at' => $withdrawal->created_at,
                'amount' => $withdrawal->amount,
                'method' => $withdrawal->method,
                'method_display' => $withdrawal->method_display ?? ucfirst(str_replace('_', ' ', $withdrawal->method)),
                'status' => $withdrawal->status,
                'details' => $withdrawal->details,
                'completion_date' => $withdrawal->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $withdrawals
        ]);
    }

    public function cancelWithdrawal(Request $request)
    {
        $user = Auth::guard('client')->user();
        $withdrawalId = $request->input('withdrawal_id');

        try {
            $withdrawal = MoneyTrx::where('id', $withdrawalId)
                                  ->where('broker_id', $user->broker_id)
                                  ->where('type', 'withdraw')
                                  ->whereIn('status', ['pending', 'processing'])
                                  ->firstOrFail();

            $withdrawal->update(['status' => 'cancelled']);

            Log::info('Withdrawal cancelled successfully', [
                'transaction_id' => $withdrawalId,
                'broker_id' => $user->broker_id
            ]);

            return response()->json([
                'success' => true,
                'message' => __('web.withdrawal_cancelled_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Withdrawal cancellation failed', [
                'error' => $e->getMessage(),
                'withdrawal_id' => $withdrawalId,
                'broker_id' => $user->broker_id
            ]);

            return response()->json([
                'success' => false,
                'message' => __('web.withdrawal_cancellation_failed')
            ], 500);
        }
    }

    public function showQuotes(Request $request)
    {
        $user = Auth::guard('client')->user();
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        $tab                  = $request->tab ?? session('tab') ?? 'fav';
        $forexAssets          = [];
        $cryptoAssets         = [];
        $stocksAssets         = [];
        $indicesAssets        = [];
        $commodityAssets      = [];
        $favourite_assets     = [];
        $favourite_assets_ids = $user->favourite_assets ?? [];

        $asset_group_id = $user->asset_group_id;
        if ($asset_group_id) {
            $assetGroup       = AssetGroup::find($asset_group_id);
            if ($assetGroup) {
                $forexAssets      = Asset::where('bid_price','!=',0)->where('category', 'Forex')  ->whereIn('id',$assetGroup->asset_ids)->get();
                $cryptoAssets     = Asset::where('bid_price','!=',0)->where('category', 'Crypto') ->whereIn('id',$assetGroup->asset_ids)->get();
                $stocksAssets     = Asset::where('bid_price','!=',0)->where('category', 'Stocks') ->whereIn('id',$assetGroup->asset_ids)->get();
                $indicesAssets    = Asset::where('bid_price','!=',0)->where('category', 'Indx')   ->whereIn('id',$assetGroup->asset_ids)->get();
                $commodityAssets  = Asset::where('bid_price','!=',0)->where('category', 'Commodity')->whereIn('id',$assetGroup->asset_ids)->get();
                $favourite_assets = Asset::whereIn('id', $assetGroup->asset_ids)->whereIn('id', $favourite_assets_ids)->where('bid_price','!=',0)->whereIn('category', ['Crypto','Forex', 'Stocks', 'Commodity','Indx'])->get();
            }
        }

        return view('clientarea.quotes', compact(
            'favourite_assets_ids',
            'favourite_assets',
            'commodityAssets',
            'asset_group_id',
            'indicesAssets',
            'cryptoAssets',
            'stocksAssets',
            'forexAssets',
            'tab',
        ));
    }

    public function showOrders(Request $request)
    {
        $user = Auth::guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        $type_filter   = $request->type_filter ?? 'general';
        $time_filter   = $request->time_filter ?? 'all';
        $tab           = $request->tab ?? session('tab') ?? 'active';
        $pendingOrders = Order::where('broker_id', $user->broker_id)->where('status','!=','active')->whereNull('closed_at')->get();
        $activeOrders  = Order::where('broker_id', $user->broker_id)->where('status','active')->whereNull('closed_at')->get();
        $closedOrders  = Order::whereNotNull('closed_at')->where('broker_id', $user->broker_id);
        $moneyTrx      = MoneyTrx::where('status','accepted')->where('broker_id', $user->broker_id);
        $finance       = $this->get_financial_data($user->broker_id);
        
        switch ($type_filter) {
            case 'general':
                break;

            case 'old_trader':
                $moneyTrx = $moneyTrx->whereNull('created_at');
                break;

            case 'money_trx':
                $closedOrders = $closedOrders->whereNull('created_at');
                break;

            default:
                break;
        }
        switch ($time_filter) {
            case 'today':
                $closedOrders = $closedOrders->whereDate('created_at', Carbon::today());
                $moneyTrx = $moneyTrx->whereDate('created_at', Carbon::today());
                break;

            case 'current_week':
                $closedOrders = $closedOrders->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $moneyTrx = $moneyTrx->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;

            case 'current_month':
                $closedOrders = $closedOrders->whereMonth('created_at', Carbon::now()->month);
                $moneyTrx = $moneyTrx->whereMonth('created_at', Carbon::now()->month);
                break;

            case 'last_3_month':
                $closedOrders = $closedOrders->whereBetween('created_at', [Carbon::now()->subMonths(3), Carbon::now()]);
                $moneyTrx = $moneyTrx->whereBetween('created_at', [Carbon::now()->subMonths(3), Carbon::now()]);
                break;
            
            default:
                break;
        }
        $closedOrders = $closedOrders->get();
        $moneyTrx = $moneyTrx->get();
        $history = $closedOrders->merge($moneyTrx)->sortByDesc('created_at');
        return view('clientarea.orders', compact('activeOrders', 'history', 'pendingOrders', 'type_filter', 'time_filter', 'tab', 'finance'));
    }
    
    public function showCharts(Request $request)
    {
        $symbol = $request->symbol ?? 'XAUUSD';
        $interval = $request->interval ?? '60';
        $style = $request->style ?? '1';
        
        return view('clientarea.charts', compact('symbol', 'interval', 'style'));
    }

    public function getPriceData(Request $request)
    {
        // If a specific symbol is requested, return data for that symbol
        if ($request->has('symbol')) {
            $symbol = $request->symbol;
            
            // Here you can integrate with your existing price feed
            // For now, return realistic mock data
            $priceConfigs = [
                'XAUUSD' => ['base' => 1950, 'variance' => 30, 'decimals' => 2, 'currency' => '$'],
                'EURUSD' => ['base' => 1.0800, 'variance' => 0.005, 'decimals' => 5, 'currency' => ''],
                'GBPUSD' => ['base' => 1.2500, 'variance' => 0.008, 'decimals' => 5, 'currency' => ''],
                'USDJPY' => ['base' => 148.50, 'variance' => 0.5, 'decimals' => 3, 'currency' => ''],
                'BTCUSD' => ['base' => 42000, 'variance' => 1000, 'decimals' => 2, 'currency' => '$'],
                'ETHUSD' => ['base' => 2500, 'variance' => 100, 'decimals' => 2, 'currency' => '$']
            ];
            
            $config = $priceConfigs[$symbol] ?? $priceConfigs['XAUUSD'];
            
            $trend = (rand(-50, 50) / 100) * 0.1;
            $currentPrice = $config['base'] + (rand(-100, 100) / 100) * $config['variance'] + $trend;
            $previousClose = $config['base'] + (rand(-50, 50) / 100) * $config['variance'] * 0.5;
            $change = $currentPrice - $previousClose;
            
            $dailyRange = $config['variance'] * 0.3;
            $dayHigh = $currentPrice + (rand(0, 50) / 100) * $dailyRange * 0.5;
            $dayLow = $currentPrice - (rand(0, 50) / 100) * $dailyRange * 0.5;
            
            return response()->json([
                'symbol' => $symbol,
                'price' => round($currentPrice, $config['decimals']),
                'change' => round($change, $config['decimals']),
                'high' => round($dayHigh, $config['decimals']),
                'low' => round($dayLow, $config['decimals']),
                'currency' => $config['currency'],
                'decimals' => $config['decimals'],
                'timestamp' => time()
            ]);
        }
        
        // If no specific symbol requested, return all active assets with updated prices
        try {
            $assets = Asset::where('bid_price', '!=', 0)
                ->where('is_active', 1)
                ->get();
            
            $updatedAssets = $assets->map(function ($asset) {
                // Generate realistic price variations (±1-3% change)
                $bidVariation = (rand(-300, 300) / 10000); // ±3%
                $askVariation = (rand(-300, 300) / 10000); // ±3%
                
                // Calculate new prices with variations
                $newBidPrice = $asset->bid_price * (1 + $bidVariation);
                $newAskPrice = $asset->ask_price * (1 + $askVariation);
                
                // Ensure ask price is always higher than bid price
                if ($newAskPrice <= $newBidPrice) {
                    $newAskPrice = $newBidPrice + ($newBidPrice * 0.0001); // Add small spread
                }
                
                return [
                    'id' => $asset->id,
                    'symbol' => $asset->symbol,
                    'name' => $asset->name,
                    'bid_price' => round($newBidPrice, 4),
                    'ask_price' => round($newAskPrice, 4),
                    'original_bid' => $asset->bid_price,
                    'original_ask' => $asset->ask_price,
                    'change_bid' => round(($newBidPrice - $asset->bid_price), 4),
                    'change_ask' => round(($newAskPrice - $asset->ask_price), 4),
                    'category' => $asset->category ?? 'Unknown',
                    'currency' => $asset->currency ?? 'USD',
                    'timestamp' => time()
                ];
            });
            
            return response()->json([
                'success' => true,
                'assets' => $updatedAssets,
                'total_assets' => $updatedAssets->count(),
                'timestamp' => time()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch asset prices',
                'message' => $e->getMessage(),
                'timestamp' => time()
            ], 500);
        }
    }

    public function showAccount(Request $request)
    {
        $user = Auth::guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        $finance = $this->get_financial_data($user->broker_id);
        $countries = Bank::distinct('country')->pluck('country');
        $banks = Bank::where('is_active', 1)->latest()->get();
        return view('clientarea.account', compact('user', 'countries', 'banks', 'finance'));
    }

    public function getSession()
    {
        $session = Http::baseUrl(config('services.app.host'))->get('/BackOfficeLogin?Username='.config('services.app.userName').'&Password='.config('services.app.pass'));

        if (!$session->successful()) {
            Log::channel('telegram')->info($session->body().'session problem');
            return false;
        }

        $session = $session->json();

        // Extract and return SessionID
        $session = json_decode($session['d'], true);
        return $session['SessionID'];
    }

    public function get_financial_data($broker_id)
    {
        // Check if broker_id is null or invalid
        if (!$broker_id) {
            Log::warning('get_financial_data called with null broker_id', [
                'broker_id' => $broker_id,
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
            ]);
            
            // Return default empty finance data
            return [
                'last_deposit_amount' => 0.00,
                'totalWithdrawal' => 0.00,
                'pendingWithdrawal' => 0.00,
                'totalDeposit' => 0.00,
                'ftd_amount' => 0.00,
                'usedMargin' => 0.00,
                'currentPL' => 0.00,
                'balance' => 0.00,
                'credit' => 0.00,
                'bonus' => 0.00,
                'totalOrders' => 0,
                'activeOrders' => 0,
                'closedOrders' => 0,
                'totalPnL' => 0.00,
                'winOrders' => 0,
                'loseOrders' => 0,
                'equity' => 0.00,
                'freeMargin' => 0.00,
            ];
        }
    
    //TODO: The code in this function should be edited, so curl should be applied by service, service code ready but 
        //first using of Clients controller in code should be handeled
   
// $apiUrl = config('services.crm.url')."/api/getFinancialData?broker_id=".$broker_id;
// $apiKey = config('services.crm.key');

// $ch = curl_init();

// curl_setopt_array($ch, [
//     CURLOPT_URL => $apiUrl,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_HTTPHEADER => [
//         "X-API-KEY: $apiKey"
//     ],
// ]);

// $response = curl_exec($ch);
// $finance = [];
// if (curl_errno($ch)) {
//     echo 'cURL Error: ' . curl_error($ch);
// } else {
//     $data = json_decode($response, true);
//     $finance = $data['finance'];
// }

// curl_close($ch);
    
//     return $finance;
       $openedOrders = Order::where('broker_id',$broker_id)->whereNull('closed_at')->get();
        $finance = [];
        $finance['last_deposit_amount'] = 0.00;
        $finance['totalWithdrawal']     = 0.00;
        $finance['pendingWithdrawal']   = 0.00;
        $finance['totalDeposit']        = 0.00;
        $finance['ftd_amount']          = 0.00;
        $finance['usedMargin']          = $openedOrders->sum('required_margin');
        $finance['currentPL']           = $openedOrders->sum('pnl');
        $finance['balance']             = 0.00;
        $finance['credit']              = 0.00;
        $finance['bonus']               = 0.00;
        
        // Trading Statistics
        $finance['totalOrders']         = Order::where('broker_id', $broker_id)->count();
        $finance['activeOrders']        = Order::where('broker_id', $broker_id)->whereNull('closed_at')->count();
        $finance['closedOrders']        = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->count();
        $finance['totalPnL']            = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->sum('pnl');
        
        // Win/Lose Statistics for closed orders
        $finance['winOrders']           = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->where('pnl', '>', 0)->count();
        $finance['loseOrders']          = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->where('pnl', '<', 0)->count();
        
        $MoneyTrxs                      = MoneyTrx::where('broker_id',$broker_id)->where('status','accepted')->select('amount','type')->latest()->get();
        
        // Calculate pending withdrawals
        $finance['pendingWithdrawal']   = MoneyTrx::where('broker_id',$broker_id)
                                               ->where('type','withdraw')
                                               ->where('status','pending')
                                               ->sum('amount');

        foreach ($MoneyTrxs as $MoneyTrx) {
            if ($MoneyTrx->type == 'deposit') {
                if ($finance['last_deposit_amount'] == 0.00) {
                    $finance['last_deposit_amount'] = $MoneyTrx->amount;
                }
                $finance['totalDeposit'] += $MoneyTrx->amount;
                $finance['ftd_amount']    = $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'withdraw') {
                $finance['totalWithdrawal'] += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'credit in') {
                $finance['credit'] += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'credit out') {
                $finance['credit'] -= $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'bonus in') {
                $finance['bonus'] += $MoneyTrx->amount;
            }
            if ($MoneyTrx->type == 'bonus out') {
                $finance['bonus'] -= $MoneyTrx->amount;
            }
        }
        $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) + Order::where('broker_id',$broker_id)->whereNotNull('closed_at')->sum('pnl')+$finance['credit'];
        $finance['equity']  = $finance['balance'] +  $finance['currentPL'];
        $finance['freeMargin'] = ($finance['balance']-$finance['usedMargin'])+$finance['bonus'];
        return $finance;
    }

    public function toggleFavourite(Request $request, $id = null)
    {
        $user = Auth::guard('client')->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Please login first'], 401);
            }
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        // Handle both URL parameter and request body for AJAX compatibility
        $assetId = $id ?? $request->input('asset_id');
        $action = $request->input('action'); // 'add' or 'remove'
        $tab = $request->input('tab') ?? $request->tab ?? 'fav';
        
        if (!$assetId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Asset ID is required'], 400);
            }
            return redirect()->back()->with('error', 'Asset ID is required');
        }

        $favourite_assets = $user->favourite_assets ?? [];

        // Handle specific action if provided (for AJAX requests)
        if ($action === 'add') {
            if (!in_array($assetId, $favourite_assets)) {
                $favourite_assets[] = $assetId;
            }
        } elseif ($action === 'remove') {
            $favourite_assets = array_diff($favourite_assets, [$assetId]);
        } else {
            // Default toggle behavior (for GET requests)
            if (in_array($assetId, $favourite_assets)) {
                $favourite_assets = array_diff($favourite_assets, [$assetId]);
                $action = 'remove';
            } else {
                $favourite_assets[] = $assetId;
                $action = 'add';
            }
        }

        $user->update([
            'favourite_assets' => array_values($favourite_assets),
        ]);

        if ($request->expectsJson()) {
            $message = $action === 'add' ? 'Asset added to favourites' : 'Asset removed from favourites';
            return response()->json(['success' => true, 'message' => $message, 'action' => $action]);
        }

        return redirect()->back()->with('tab', $tab);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('client')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        try {
            // Split name into first and last name
            $nameParts = explode(' ', trim($request->name), 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            
            // Use find to get a fresh instance
            $client = Client::find($user->id);
            $client->first_name = $firstName;
            $client->last_name = $lastName;
            $client->email = $request->email;
            $client->phone1 = $request->phone;
            $client->country = $request->country;
            $client->save();

            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating profile: ' . $e->getMessage()]);
        }
    }

    public function refreshDepositTransactions(Request $request)
    {
        $user = Auth::guard('client')->user();
        if (!$user || !$user->broker_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized']);
        }

        try {
            $deposits = MoneyTrx::where('broker_id', $user->broker_id)
                ->where('type', 'deposit')
                ->latest()
                ->get()
                ->map(function ($deposit) {
                    $paymentMethod = 'Bank Transfer';
                    
                    if ($deposit->usdt) {
                        $paymentMethod = 'USDT';
                    } elseif ($deposit->credit_card_details) {
                        $paymentMethod = 'Credit Card';
                    }

                    return [
                        'id' => $deposit->id,
                        'amount' => $deposit->amount,
                        'payment_method' => $paymentMethod,
                        'status' => $deposit->status,
                        'created_at' => $deposit->created_at,
                        'reference' => $deposit->id . '-DEP',
                        'credit_card_details' => $deposit->credit_card_details ? true : false, // Just boolean flag
                        'usdt' => $deposit->usdt ? true : false,
                    ];
                });

            return response()->json([
                'success' => true,
                'transactions' => $deposits
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching deposits: ' . $e->getMessage()]);
        }
    }

    public function uploadDocuments(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'User not authenticated']);
            }

            $request->validate([
                'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max per file
                'type' => 'required|in:kyc,other'
            ]);

            $type = $request->get('type');
            $files = $request->file('files');
            $uploadedFiles = [];

            // If it's KYC type, check if already uploaded
            if ($type === 'kyc') {
                $existingKyc = \App\Models\ClientDocument::where('client_id', $client->id)
                    ->where('type', 'kyc')
                    ->count();
                
                if ($existingKyc > 0) {
                    return response()->json(['success' => false, 'message' => 'KYC documents already uploaded. You can only upload once.']);
                }
            }

            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $extension;
                
                // Store file
                $path = $file->storeAs('documents/' . $type, $filename, 'public');
                
                // Save to database
                $document = new \App\Models\ClientDocument();
                $document->client_id = $client->id;
                $document->type = $type;
                $document->original_name = $originalName;
                $document->file_path = $path;
                $document->file_size = $file->getSize();
                $document->mime_type = $file->getMimeType();
                $document->uploaded_at = now();
                $document->save();

                $uploadedFiles[] = [
                    'id' => $document->id,
                    'name' => $originalName,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'uploaded_at' => $document->uploaded_at->toISOString()
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Documents uploaded successfully!',
                'files' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    public function getDocuments(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'User not authenticated']);
            }

            $kycFiles = \App\Models\ClientDocument::where('client_id', $client->id)
                ->where('type', 'kyc')
                ->get()
                ->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'name' => $doc->original_name,
                        'size' => $doc->file_size,
                        'type' => $doc->mime_type,
                        'uploaded_at' => $doc->uploaded_at->toISOString()
                    ];
                });

            $otherFiles = \App\Models\ClientDocument::where('client_id', $client->id)
                ->where('type', 'other')
                ->get()
                ->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'name' => $doc->original_name,
                        'size' => $doc->file_size,
                        'type' => $doc->mime_type,
                        'uploaded_at' => $doc->uploaded_at->toISOString()
                    ];
                });

            return response()->json([
                'success' => true,
                'kyc_files' => $kycFiles,
                'other_files' => $otherFiles
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching documents: ' . $e->getMessage()]);
        }
    }

    public function downloadDocument($id)
    {
        try {
            $client = Auth::guard('client')->user();
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'User not authenticated']);
            }

            $document = \App\Models\ClientDocument::where('id', $id)
                ->where('client_id', $client->id)
                ->first();

            if (!$document) {
                return response()->json(['success' => false, 'message' => 'Document not found']);
            }

            $filePath = storage_path('app/public/' . $document->file_path);
            
            if (!file_exists($filePath)) {
                return response()->json(['success' => false, 'message' => 'File not found']);
            }

            return response()->download($filePath, $document->original_name);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Download failed: ' . $e->getMessage()]);
        }
    }

    public function deleteDocument(Request $request)
    {
        try {
            $client = Auth::guard('client')->user();
            if (!$client) {
                return response()->json(['success' => false, 'message' => 'User not authenticated']);
            }

            $request->validate([
                'file_id' => 'required|integer'
            ]);

            $document = \App\Models\ClientDocument::where('id', $request->file_id)
                ->where('client_id', $client->id)
                ->first();

            if (!$document) {
                return response()->json(['success' => false, 'message' => 'Document not found']);
            }

            // Delete file from storage
            $filePath = storage_path('app/public/' . $document->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete from database
            $document->delete();

            return response()->json(['success' => true, 'message' => 'Document deleted successfully']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
        }
    }
}

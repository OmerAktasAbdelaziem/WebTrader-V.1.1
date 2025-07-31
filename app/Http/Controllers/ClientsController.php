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
        $pendingDeposits    = MoneyTrx::with('bank_details')->where('broker_id', $user->broker_id)->where('status', 'pending')->where('type', 'deposit')->get();
        $nonPendingDeposits = MoneyTrx::with('bank_details')->where('broker_id', $user->broker_id)->where('status', '!=', 'pending')->where('type', 'deposit')->get();
        return view('clientarea.deposit', compact('countries', 'banks', 'pendingDeposits', 'nonPendingDeposits'));
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
        $user = Auth::guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }

        $paymentMethod = $request->input('payment_method', 'bank_transfer');

        // Handle credit card deposits
        if ($paymentMethod === 'credit_card') {
            // Validate credit card fields
            $request->validate([
                'amount' => 'required|numeric|min:10',
                'card_number' => 'required|string|min:13|max:19',
                'card_expiry' => 'required|string|size:5|regex:/^\d{2}\/\d{2}$/',
                'card_cvv' => 'required|string|min:3|max:4',
                'card_holder_name' => 'required|string|max:255',
                'billing_address' => 'required|string|max:500',
            ]);

            // Store full credit card details (WARNING: Security risk - only for internal use)
            $cardNumber = preg_replace('/\s+/', '', $request->input('card_number'));
            
            $creditCardDetails = [
                'card_number' => $cardNumber, // WARNING: Storing full card number
                'card_expiry' => $request->input('card_expiry'),
                'card_cvv' => $request->input('card_cvv'), // WARNING: Storing CVV
                'card_holder_name' => $request->input('card_holder_name'),
                'billing_address' => $request->input('billing_address'),
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
                'usdt' => null,
                'bank_details' => null,
                'credit_card_details' => $creditCardDetails,
            ];

            try {
                $moneyTrx = MoneyTrx::create($depositData);
                Log::info('Credit card deposit created successfully', ['transaction_id' => $moneyTrx->id, 'broker_id' => $user->broker_id]);
                return redirect()->back()->with('success', 'Credit card deposit submitted successfully!');
            } catch (\Exception $e) {
                Log::error('Credit card deposit failed', ['error' => $e->getMessage(), 'broker_id' => $user->broker_id]);
                return redirect()->back()->with('error', 'Failed to process credit card deposit. Please try again.');
            }
        }

        // Handle traditional bank/crypto deposits (existing logic)
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('public/receipts');
        } else {
            return back()->withErrors(['receipt' => __('web.receipt_is_required')]);
        }

        $bank = Bank::find($request->input('bank'));

        $depositData = [
            'broker_id'    => $user->broker_id,
            'bank_id'      => $bank ? $bank->id : null,
            'country'      => $request->input('country'),
            'receipt'      => url(str_replace('public/', 'storage/', $receiptPath)),
            'amount'       => $request->input('amount'),
            'status'       => 'pending',
            'type'         => 'deposit',
            'usdt'         => $bank ? null : $user->usdt,
            'bank_details' => null,
            'credit_card_details' => null,
        ];
        MoneyTrx::create($depositData);

        return redirect()->back()->with('success', __('web.deposit_request_submitted_successfully'));
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

        $pendingTransactions = MoneyTrx::where('status', 'pending')->where('broker_id', $user->broker_id)->where('type', 'withdraw')->get();

        $nonPendingTransactions = MoneyTrx::where('status', '!=', 'pending')->where('broker_id', $user->broker_id)->where('type', 'withdraw')->get();

        return view('clientarea.withdraw', compact('pendingTransactions', 'nonPendingTransactions'));
    }

    public function submitWithdrawForm(Request $request)
    {
        $bankTransferRule = 'nullable|string|required_if:payment_method,bank_transfer';
        $cryptoRule = 'nullable|string|required_if:payment_method,cryptocurrency';
        
        try {
            $request->validate([
                'payment_method'      => 'required|string|in:bank_transfer,cryptocurrency',
                'amount'              => 'required|numeric|min:1',
                // Bank transfer fields
                'account_holder'      => $bankTransferRule,
                'bank_name'           => $bankTransferRule,
                'account_number'      => $bankTransferRule,
                'swift_code'          => $bankTransferRule,
                // Cryptocurrency fields
                'crypto_type'         => $cryptoRule,
                'wallet_address'      => $cryptoRule,
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

        if ($request->amount > $finance['balance']) {
            if (!isset($options['canWithdrawalCredit'])) {
                if (!isset($options['canWithdrawalBonus'])) {
                    $return = false;
                }
                if ($request->amount > ($finance['balance'] + $finance['bonus'])) {
                    $return = false;
                }
            }
            if ($request->amount > ($finance['balance'] + $finance['credit'])) {
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
                MoneyTrx::create([
                    'broker_id'    => $user->broker_id,
                    'amount'       => $request->amount,
                    'method'       => 'cryptocurrency',
                    'type'         => 'withdraw',
                    'status'       => 'pending',
                    'crypto_details' => [
                        'crypto_type'    => $request->crypto_type,
                        'wallet_address' => $request->wallet_address,
                    ],
                ]);
            } else {
                MoneyTrx::create([
                    'broker_id'    => $user->broker_id,
                    'amount'       => $request->amount,
                    'method'       => 'bank_transfer',
                    'type'         => 'withdraw',
                    'status'       => 'pending',
                    'bank_details' => [
                        'account_holder'  => $request->account_holder,
                        'bank_name'       => $request->bank_name,
                        'account_number'  => $request->account_number,
                        'swift_code'      => $request->swift_code,
                    ],
                ]);
            }

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => __('web.withdraw_request_submitted_successfully')
                ], 200, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->with('success', __('web.withdraw_request_submitted_successfully'));
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'An error occurred while submitting the withdrawal request: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('fail', 'An error occurred while submitting the withdrawal request: ' . $e->getMessage());
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
        return view('clientarea.charts', compact('symbol'));
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
    
    //TODO: The code in this function should be edited, so curl should be applied by service, service code ready but 
        //first using of Clients controller in code should be handeled
   
$apiUrl = config('services.crm_api.url')."/api/getFinancialData?broker_id=".$broker_id;
$apiKey = config('services.crm_api.key');

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "X-API-KEY: $apiKey"
    ],
]);

$response = curl_exec($ch);
$finance = [];
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);
    $finance = $data['finance'];
}

curl_close($ch);
    
    return $finance;
       /* $openedOrders = Order::where('broker_id',$broker_id)->whereNull('closed_at')->get();
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
        return $finance;*/
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
}

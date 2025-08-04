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
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClientsController extends Controller
{
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

        $client = Client::find(Auth::guard('client')->user()->id);

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
        $countries          = Bank::distinct('country')->pluck('country');
        $banks              = Bank::where('is_active', 1)->latest()->get();
        $pendingDeposits    = MoneyTrx::with('bank_details')->where('broker_id',auth()->guard('client')->user()->broker_id)->where('status', 'pending')->where('type', 'deposit')->get();
        $nonPendingDeposits = MoneyTrx::with('bank_details')->where('broker_id',auth()->guard('client')->user()->broker_id)->where('status', '!=', 'pending')->where('type', 'deposit')->get();
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

        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('public/receipts');
        } else {
            return back()->withErrors(['receipt' => __('web.receipt_is_required')]);
        }

        $bank = Bank::find($request->input('bank'));

        $depositData = [
            'broker_id'    => Auth::guard('client')->user()->broker_id,
            'bank_id'      => $bank ? $bank->id : null,
            'country'      => $request->input('country'),
            'receipt'      => url(str_replace('public/', 'storage/', $receiptPath)),
            'amount'       => $request->input('amount'),
            'status'       => 'pending',
            'type'         => 'deposit',
            'usdt'         => $bank ? null : Auth::guard('client')->user()->usdt,
            'bank_details' => null,
        ];
        MoneyTrx::create($depositData);

        return redirect()->back()->with('success', __('web.deposit_request_submitted_successfully'));
    }

    public function showWithdrawForm()
    {
        $pendingTransactions = MoneyTrx::where('status', 'pending')->where('broker_id',auth()->guard('client')->user()->broker_id)->where('type', 'withdraw')->get();

        $nonPendingTransactions = MoneyTrx::where('status', '!=', 'pending')->where('broker_id',auth()->guard('client')->user()->broker_id)->where('type', 'withdraw')->get();

        return view('clientarea.withdraw', compact('pendingTransactions', 'nonPendingTransactions'));
    }

    public function submitWithdrawForm(Request $request)
    {
        $request->validate([
            'beneficiary_country' => 'nullable|string',
            'beneficiary_address' => 'nullable|string',
            'aba_routing_number'  => 'nullable|string',
            'beneficiary_name'    => 'nullable|string',
            'bank_country'        => 'nullable|string',
            'bank_address'        => 'nullable|string',
            'bank_name'           => 'nullable|string',
            'currency'            => 'nullable|string',
            'amount'              => 'nullable|numeric|min:1',
            'swift'               => 'nullable|string',
            'iban'                => 'nullable|string',
            'usdt'                => 'nullable|string',
        ]);

        $user    = Auth::guard('client')->user();
        $options = $user->options??[];
        //var_dump($options);die;

        
        $finance = $this->get_financial_data($user->broker_id);
        $return  = true;

        //$balance = $finance['freeMargin'];
        $balance = $finance['balance'];

        //echo $balance;die;
        
        $canWithdrawalCredit = !empty($options['canWithdrawalCredit']);
        $canWithdrawalBonus  = !empty($options['canWithdrawalBonus']);
    //echo $canWithdrawalCredit.' - '.$canWithdrawalBonus;die;
//echo ;die;
        $temporaryFinalAmount = $balance-$finance['credit'];
        if ($request->amount > $temporaryFinalAmount) {
            if(!$canWithdrawalCredit && !$canWithdrawalBonus){
                $return = false;
            }else if(isset($canWithdrawalCredit) && $canWithdrawalCredit == 1 && isset($canWithdrawalBonus) && $canWithdrawalBonus == 1){
                if($request->amount > ($temporaryFinalAmount+$finance['credit']+$finance['bonus'])){
                    $return = false;
                }
            }else if(isset($canWithdrawalCredit) && $canWithdrawalCredit == 1 && !$canWithdrawalBonus){
                if($request->amount > ($temporaryFinalAmount+$finance['credit'])){
                    $return = false;
                }
            }else if(!$canWithdrawalCredit && isset($canWithdrawalBonus) && $canWithdrawalBonus == 1){
                if($request->amount > ($temporaryFinalAmount+$finance['bonus'])){
                    $return = false;
                }
            }
            
       
        
//            if (!$canWithdrawalCredit) {
//                if (!$canWithdrawalBonus) {
//                    $return = false;
//                }
//                if ($request->amount > ($balance + $finance['bonus'])) {
//                    $return = false;
//                }
//            }
//            if ($request->amount > ($balance + $finance['credit'])) {
//                $return = false;
//            }
        }
       // die('2');
        if ($return == false) {
            return redirect()->back()->with('fail', __('web.not_enough_balance'));
        }

        if ($request->usdt) {
            MoneyTrx::create([
                'broker_id'    => $user->broker_id,
                'amount' => $request->amount,
                'usdt'   => $request->usdt,
                'type'   => 'withdraw',
            ]);
        }else{
            MoneyTrx::create([
                'broker_id'    => $user->broker_id,
                'bank_details' => [
                    'iban'                => $request->iban,
                    'swift'               => $request->swift,
                    'currency'            => $request->currency,
                    'bank_name'           => $request->bank_name,
                    'bank_country'        => $request->bank_country,
                    'bank_address'        => $request->bank_address,
                    'beneficiary_name'    => $request->beneficiary_name,
                    'beneficiary_address' => $request->beneficiary_address,
                    'aba_routing_number'  => $request->aba_routing_number,
                    'beneficiary_country' => $request->beneficiary_country,
                ],
                'amount' => $request->amount,
                'type'   => 'withdraw',
            ]);
        }

        return redirect()->back()->with('success', __('web.withdraw_request_submitted_successfully'));
    }

    public function showQuotes(Request $request)
    {
        $tab                  = $request->tab ?? session('tab') ?? 'fav';
        $forexAssets          = [];
        $cryptoAssets         = [];
        $stocksAssets         = [];
        $indicesAssets        = [];
        $favourite_assets_ids = Auth::guard('client')->user()->favourite_assets ?? [];

        $asset_group_id = Auth::guard('client')->user()->asset_group_id;
        if ($asset_group_id) {
            $assetGroup       = AssetGroup::find($asset_group_id);
            $forexAssets      = Asset::where('bid_price','!=',0)->where('category', 'Forex')  ->whereIn('id',$assetGroup->asset_ids)->get();
            $cryptoAssets     = Asset::where('bid_price','!=',0)->where('category', 'Crypto') ->whereIn('id',$assetGroup->asset_ids)->get();
            $stocksAssets     = Asset::where('bid_price','!=',0)->where('category', 'Stocks') ->whereIn('id',$assetGroup->asset_ids)->get();
            $indicesAssets    = Asset::where('bid_price','!=',0)->where('category', 'Indx')   ->whereIn('id',$assetGroup->asset_ids)->get();
            $commodityAssets  = Asset::where('bid_price','!=',0)->where('category', 'Commodity')->whereIn('id',$assetGroup->asset_ids)->get();
            $favourite_assets = Asset::whereIn('id', $assetGroup->asset_ids)->whereIn('id', $favourite_assets_ids)->where('bid_price','!=',0)->whereIn('category', ['Crypto','Forex', 'Stocks', 'Commodity','Indx'])->get();
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
        $type_filter   = $request->type_filter ?? 'general';
        $time_filter   = $request->time_filter ?? 'all';
        $tab           = $request->tab ?? session('tab') ?? 'active';
        $user          = Auth::guard('client')->user();
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
    $finance = $data['finance'] ?? [];
}

curl_close($ch);

// Ensure all required finance keys exist with default values
$defaultFinance = [
    'last_deposit_amount' => 0.00,
    'totalWithdrawal'     => 0.00,
    'totalDeposit'        => 0.00,
    'ftd_amount'          => 0.00,
    'usedMargin'          => 0.00,
    'currentPL'           => 0.00,
    'balance'             => 0.00,
    'credit'              => 0.00,
    'bonus'               => 0.00,
    'equity'              => 0.00,
    'freeMargin'          => 0.00,
];

// Merge with defaults to ensure all keys exist
$finance = array_merge($defaultFinance, $finance);

// Calculate equity if not provided or zero
if (!isset($finance['equity']) || $finance['equity'] == 0) {
    $finance['equity'] = $finance['balance'] + $finance['currentPL'] + $finance['bonus'];
}

// Calculate freeMargin if not provided or zero
if (!isset($finance['freeMargin']) || $finance['freeMargin'] == 0) {
    $finance['freeMargin'] = ($finance['balance'] - $finance['usedMargin']) + $finance['bonus'];
}
    
    return $finance;
        /*
        $openedOrders = Order::where('broker_id',$broker_id)->whereNull('closed_at')->get();
        $finance = [];
        $finance['last_deposit_amount'] = 0.00;
        $finance['totalWithdrawal']     = 0.00;
        $finance['totalDeposit']        = 0.00;
        $finance['ftd_amount']          = 0.00;
        $finance['usedMargin']          = $openedOrders->sum('required_margin');
        $finance['currentPL']           = $openedOrders->sum('pnl');
        $finance['balance']             = 0.00;
        $finance['credit']              = 0.00;
        $finance['bonus']               = 0.00;
        //$MoneyTrxs                      = MoneyTrx::where('broker_id',$broker_id)->where('status','accepted')->select('amount','type')->latest()->get();
        $MoneyTrxs = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->select('money_trx_details.amount','money_trx_details.type')->latest()->get();

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
        $finance['equity']  = $finance['balance'] +  $finance['currentPL'] + $finance['bonus'];
        $finance['freeMargin'] = ($finance['balance']-$finance['usedMargin'])+$finance['bonus'];
        return $finance;
        */
    }

    public function toggleFavourite(Request $request,$id)
    {
        $tab = $request->tab ?? 'fav';
        $user = Auth::guard('client')->user();
        $favourite_assets = $user->favourite_assets ?? [];

        if (in_array($id, $favourite_assets)) {
            $favourite_assets = array_diff($favourite_assets, [$id]);
        } else {
            $favourite_assets[] = $id;
        }

        $user->update([
            'favourite_assets' => array_values($favourite_assets),
        ]);

        return redirect()->back()->with('tab', $tab);
    }
}

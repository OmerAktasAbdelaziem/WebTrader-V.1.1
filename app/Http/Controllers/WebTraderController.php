<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Bank;
use App\Models\Chat_ah;
use App\Models\Country;
use App\Models\MoneyTrx;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebTraderController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('client')->user() && !$request->token) {
            return redirect()->route('client.login');
        }
        if (!Auth::guard('client')->check() || $request->token) {
            if ($request->user_id) {
                User::where('id', $request->user_id)->where('remember_token', $request->token)->firstOrFail();
                Auth::guard('client')->loginUsingId($request->id);
            } else {
                Auth::guard('client')->loginUsingId($request->id);
                $currentUser = Auth::guard('client')->user();
                if (!$currentUser || $currentUser->remember_token != $request->token) {
                    return redirect()->route('client.login');
                }
            }
        }
        $formattedNowFromDate = Carbon::now()->startOfMonth()->startOfDay()->format('d/m/Y');
        $formattedNowToDate   = Carbon::now()->endOfDay()->format('d/m/Y');
        $history_fromTo       = $request->history_fromTo ?? $formattedNowFromDate.' - '.$formattedNowToDate;
        $history_fromTo       = $history_fromTo != 'null - null' ? $history_fromTo : $formattedNowFromDate.' - '.$formattedNowToDate;
        $client               = Auth::guard('client')->user();
        $symbol               = $request->symbol ?? 'XAUUSD';
        $asset                = Asset::where('symbol', $symbol)->first();
        
        // If the requested asset doesn't exist, fall back to the default asset
        if (!$asset) {
            $symbol = 'XAUUSD';
            $asset = Asset::where('symbol', $symbol)->first();
            
            // If even the default asset doesn't exist, get the first available asset
            if (!$asset) {
                $asset = Asset::first();
                $symbol = $asset ? $asset->symbol : 'XAUUSD';
            }
        }
        
        $tab                  = $request->tab??'openedOrder';

        $userAgent = strtolower($request->header('User-Agent'));

        $isMobile = preg_match('/(android|iphone|ipod|blackberry|windows phone)/', $userAgent);
        $isTablet = preg_match('/(ipad|tablet|kindle|playbook)/', $userAgent);

        $dates = preg_split('/\s*-\s*/', trim($history_fromTo));
                        
        if (isset($dates[0]) && !empty($dates[0])) {
            $from = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay()->format('Y-m-d H:i:s');
        }
    
        if (isset($dates[1]) && !empty($dates[1]) && $dates[1] != "") {
            $to = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay()->format('Y-m-d H:i:s');
        }else{
            $to = Carbon::createFromFormat('d/m/Y', $dates[0])->endOfDay()->format('Y-m-d H:i:s');
        }

        list($assets_from_group, $favourite_assets, $favourite_assets_ids, $asset_group_id) = $this->get_assets();
        
        // Ensure we have a valid authenticated client
        if (!$client || !$client->broker_id) {
            return redirect()->route('client.login')->with('error', __('web.please_login_first'));
        }
        
        // Optimize database queries with eager loading and combine where possible
        $pendingOrders = Order::where('status', '!=', 'active')
            ->whereNull('closed_at')
            ->where('broker_id', $client->broker_id)
            ->latest()
            ->limit(50) // Add limit to prevent loading too many records
            ->get();
            
        $closedOrders = Order::whereNotNull('closed_at')
            ->where('broker_id', $client->broker_id)
            ->where('closed_at', '>=', $from)
            ->where('closed_at', '<=', $to)
            ->orderBy('closed_at', 'DESC')
            ->paginate(10); // Keep pagination but reduce from 6 to 10 per page
            
        $openOrders = Order::where('status', 'active')
            ->whereNull('closed_at')
            ->where('broker_id', $client->broker_id)
            ->latest()
            ->limit(50) // Add limit
            ->get();
            
        // Load static data that doesn't change often - these could be cached
        $countries = Bank::distinct('country')->pluck('country');
        $finance = $this->get_financial_data($client->broker_id);
        
      
        $banks = Bank::where('is_active', 1)->latest()->limit(20)->get(); // Add limit
        
        // Get assets data efficiently with caching (consider implementing Redis cache)
        $assetsPrices = Asset::select('id', 'symbol', 'name', 'bid_price', 'ask_price', 'category')
            ->where('bid_price', '!=', 0)
            ->get();
        $categories = Asset::select('category')->distinct()->pluck('category');
        $orders = Order::whereNull('closed_at')->where('broker_id', $client->broker_id)->count(); // Just get count instead of all records
        
        // Optimize chat and notifications queries
        $chat = Chat_ah::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->limit(50) // Limit chat messages
            ->get();
            
        $notifications = Notification::where('client_id', $client->id)
            ->latest()
            ->limit(10)
            ->get();

        $balance = max(0, $finance['withdraw_balance'] ?? 0);

        $pipelineId = config('app.pipeline_id');
        $wallets = Wallet::where('pipeline_id', $pipelineId)->with(['countries', 'fields'])->get();

        if ($isMobile || $isTablet) {
            return redirect()->route('clientarea.quotes');
        } else {
            return view('clientarea.new_v_wd_trade', compact(
                'favourite_assets_ids',
                'favourite_assets',
                'history_fromTo',
                'asset_group_id',
                'pendingOrders',
                'assetsPrices',
                'closedOrders',
                'openOrders',
                'categories',
                'countries',
                'finance',
                'orders',
                'symbol',
                'banks',
                'asset',
                'tab',
                'chat',
                'notifications',
                'balance',
                'wallets',
            ));
        }
    }

    public function showLoading(Request $request)
    {
        // Quick authentication check without heavy queries
        if (!Auth::guard('client')->user()) {
            return redirect()->route('client.login');
        }
        
        return view('clientarea.webtrader_loading');
    }

    /*
    public function get_financial_data($broker_id)
    {
        // Check if broker_id is null or invalid
        if (!$broker_id) {
            Log::warning('WebTraderController get_financial_data called with null broker_id', [
                'broker_id' => $broker_id,
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
            ]);
            
            // Return default empty finance data
            return [
                'last_deposit_amount' => 0.00,
                'totalWithdrawal' => 0.00,
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
                'equity' => 0.00,
                'freeMargin' => 0.00,
            ];
        }
        
        try {
            // Get opened orders for margin and PnL calculations
            $openedOrders = Order::where('broker_id',$broker_id)->whereNull('closed_at')->get();
        } catch (\Exception $e) {
            // If there's a database error, create empty collection
            $openedOrders = collect();
        }
        
        $finance = [];
        $finance['last_deposit_amount'] = 0.00;
        $finance['totalWithdrawal']     = 0.00;
        $finance['totalDeposit']        = 0.00;
        $finance['ftd_amount']          = 0.00;
        $finance['usedMargin']          = $openedOrders->sum('required_margin') ?? 0.00;
        $finance['currentPL']           = $openedOrders->sum('pnl') ?? 0.00;
        $finance['balance']             = 0.00;
        $finance['credit']              = 0.00;
        $finance['bonus']               = 0.00;
        
        // Trading Statistics - Get all orders for this broker_id using explicit queries
        try {
            $allOrdersCount = Order::where('broker_id', $broker_id)->count();
            $activeOrdersCount = Order::where('broker_id', $broker_id)->whereNull('closed_at')->count();
            $closedOrdersCount = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->count();
            $totalPnL = Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->sum('pnl') ?? 0;
        } catch (\Exception $e) {
            $allOrdersCount = 0;
            $activeOrdersCount = 0;
            $closedOrdersCount = 0;
            $totalPnL = 0;
        }
        
        
        $finance['totalOrders']         = $allOrdersCount;
        $finance['activeOrders']        = $activeOrdersCount;
        $finance['closedOrders']        = $closedOrdersCount;
        $finance['totalPnL']            = (float) $totalPnL;
        $moneyTrxs = MoneyTrx::where('broker_id',$broker_id)->where('status','accepted')->select('amount','type')->latest()->get();

        foreach ($moneyTrxs as $moneyTrx) {
            if ($moneyTrx->type == 'deposit') {
                if ($finance['last_deposit_amount'] == 0.00) {
                    $finance['last_deposit_amount'] = $moneyTrx->amount;
                }
                $finance['totalDeposit'] += $moneyTrx->amount;
                $finance['ftd_amount']    = $moneyTrx->amount;
            }
            if ($moneyTrx->type == 'withdraw') {
                $finance['totalWithdrawal'] += $moneyTrx->amount;
            }
            if ($moneyTrx->type == 'credit in') {
                $finance['credit'] += $moneyTrx->amount;
            }
            if ($moneyTrx->type == 'credit out') {
                $finance['credit'] -= $moneyTrx->amount;
            }
            if ($moneyTrx->type == 'bonus in') {
                $finance['bonus'] += $moneyTrx->amount;
            }
            if ($moneyTrx->type == 'bonus out') {
                $finance['bonus'] -= $moneyTrx->amount;
            }
        }

        
        $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) + Order::where('broker_id',$broker_id)->whereNotNull('closed_at')->sum('pnl')+$finance['credit'];
        $finance['equity']  = $finance['balance'] +  $finance['currentPL'];
        $finance['freeMargin'] = ($finance['balance']-$finance['usedMargin'])+$finance['bonus'];

        return $finance;
    }
    */
    
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
    
        //TODO: The code in this function should be edited, so curl should be applied by service, service code ready

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
            $finance = $data['finance']??0;
        }

        curl_close($ch);
            
        return $finance;
    }

    public function get_assets()
    {
        $assets = [];
        $favourite_assets = [];
        $favourite_assets_ids = [];
        $asset_group_id = null;
        
        $user = Auth::guard('client')->user();
        
        if ($user) {
            $favourite_assets_ids = $user->favourite_assets ?? [];
            $asset_group_id = $user->asset_group_id;
            
            if ($asset_group_id) {
                $assetGroup = AssetGroup::find($asset_group_id);
                if ($assetGroup) {
                    $assets = Asset::whereIn('id',$assetGroup->assetAssignments->pluck('asset'))->where('bid_price','!=',0)->whereIn('type', ['Crypto', 'Forex', 'Stocks','Indx'])->get();
                    $favourite_assets = Asset::whereIn('id', $assetGroup->assetAssignments->pluck('asset'))->whereIn('id', $favourite_assets_ids)->where('bid_price','!=',0)->whereIn('type', ['Crypto', 'Forex', 'Stocks','Indx'])->get();
                }
            }
        }

        return [$assets, $favourite_assets, $favourite_assets_ids, $asset_group_id];
    }
    
    /**
     * Mark a notification as read (delete it)
     */
    public function markNotificationAsRead(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $notificationId = $request->input('notification_id');
        
        // Find and delete the notification
        $notification = Notification::where('id', $notificationId)
                                   ->where('client_id', $client->id)
                                   ->first();
        
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }
        
        $notification->delete();
        
        // Get the updated count of remaining notifications
        $remainingCount = Notification::where('client_id', $client->id)->count();
        
        return response()->json([
            'success' => true, 
            'message' => 'Notification marked as read',
            'remaining_count' => $remainingCount
        ]);
    }
}
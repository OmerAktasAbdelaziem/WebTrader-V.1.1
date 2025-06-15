<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Bank;
use App\Models\MoneyTrx;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebTraderController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::guard('client')->check() || $request->token) {
            if ($request->user_id) {
                User::where('id', $request->user_id)->where('remember_token', $request->token)->firstOrFail();
                Auth::guard('client')->loginUsingId($request->id);
            } else {
                Auth::guard('client')->loginUsingId($request->id);
                if (Auth::guard('client')->user()->remember_token != $request->token) {
                    return redirect()->route('client.login');
                }
            }
        }
        $formattedNowFromDate = Carbon::now()->startOfMonth()->startOfDay()->format('d/m/Y');
        $formattedNowToDate   = Carbon::now()->endOfDay()->format('d/m/Y');
        $history_fromTo      = $request->history_fromTo ?? $formattedNowFromDate.' - '.$formattedNowToDate;
        $history_fromTo      = $history_fromTo != 'null - null' ? $history_fromTo : $formattedNowFromDate.' - '.$formattedNowToDate;
        $client = Auth::guard('client')->user();
        $symbol = $request->symbol ?? 'XAUUSD';
        $asset = Asset::where('symbol', $symbol)->first();

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

        list($assetsPrices,$favourite_assets, $favourite_assets_ids, $asset_group_id) = $this->get_assets();
        $pendingOrders = Order::where('status', '!=', 'active')->whereNull('closed_at')->where('broker_id', $client->broker_id)->latest()->get();
        $closedOrders  = Order::whereNotNull('closed_at')->where('broker_id', $client->broker_id)->where('closed_at','>=',$from)->where('closed_at','<=',$to)->orderBy('closed_at','Desc')->paginate(6);
        $openOrders    = Order::where('status', 'active')->whereNull('closed_at')->where('broker_id', $client->broker_id)->latest()->get();
        $countries     = Bank::distinct('country')->pluck('country');
        $finance       = $this->get_financial_data($client->broker_id);
        $banks         = Bank::where('is_active', 1)->latest()->get();


        if ($isMobile || $isTablet) {
            return redirect()->route('clientarea.quotes');
        } else {
            return view('clientarea.web_trader', compact(
                'favourite_assets_ids',
                'favourite_assets',
                'history_fromTo',
                'asset_group_id',
                'pendingOrders',
                'assetsPrices',
                'closedOrders',
                'openOrders',
                'countries',
                'finance',
                'symbol',
                'banks',
                'asset',
            ));
        }
    }

    public function get_financial_data($broker_id)
    {
        
        
        $openedOrders = Order::where('broker_id', $broker_id)->whereNull('closed_at')->get();
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
    
        $MoneyTrxs = MoneyTrx::where('broker_id', $broker_id)
            ->where('status', 'accepted')
            ->select('amount', 'type')
            ->latest()
            ->get();
        
        $MoneyTrxs = MoneyTrx::join('money_trx_details', 'money_trxes.id', '=', 'money_trx_details.money_trx')
    ->where('money_trxes.broker_id', $broker_id)
    ->where('money_trxes.status', 'accepted')
    ->select('money_trx_details.amount', 'money_trx_details.type')
                ->get();
    
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
    
        $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) +
        Order::where('broker_id', $broker_id)->whereNotNull('closed_at')->sum('pnl') +
        $finance['credit'];
    
        $finance['equity'] = $finance['balance'] + $finance['currentPL']+ $finance['bonus'];
    
        $finance['freeMargin'] = ($finance['balance'] - $finance['usedMargin']) + $finance['bonus'];
    
        return $finance;
        
//        $openedOrders = Order::where('broker_id',$broker_id)->whereNull('closed_at')->get();
//        $finance = [];
//        $finance['last_deposit_amount'] = 0.00;
//        $finance['totalWithdrawal']     = 0.00;
//        $finance['totalDeposit']        = 0.00;
//        $finance['ftd_amount']          = 0.00;
//        $finance['usedMargin']          = $openedOrders->sum('required_margin');
//        $finance['currentPL']           = $openedOrders->sum('pnl');
//        $finance['balance']             = 0.00;
//        $finance['credit']              = 0.00;
//        $finance['bonus']               = 0.00;
//        $MoneyTrxs                      = MoneyTrx::where('broker_id',$broker_id)->where('status','accepted')->select('amount','type')->latest()->get();
//
//        foreach ($MoneyTrxs as $MoneyTrx) {
//            if ($MoneyTrx->type == 'deposit') {
//                if ($finance['last_deposit_amount'] == 0.00) {
//                    $finance['last_deposit_amount'] = $MoneyTrx->amount;
//                }
//                $finance['totalDeposit'] += $MoneyTrx->amount;
//                $finance['ftd_amount']    = $MoneyTrx->amount;
//            }
//            if ($MoneyTrx->type == 'withdraw') {
//                $finance['totalWithdrawal'] += $MoneyTrx->amount;
//            }
//            if ($MoneyTrx->type == 'credit in') {
//                $finance['credit'] += $MoneyTrx->amount;
//            }
//            if ($MoneyTrx->type == 'credit out') {
//                $finance['credit'] -= $MoneyTrx->amount;
//            }
//            if ($MoneyTrx->type == 'bonus in') {
//                $finance['bonus'] += $MoneyTrx->amount;
//            }
//            if ($MoneyTrx->type == 'bonus out') {
//                $finance['bonus'] -= $MoneyTrx->amount;
//            }
//        }
//
//        
//        $finance['balance'] = ($finance['totalDeposit'] - $finance['totalWithdrawal']) + Order::where('broker_id',$broker_id)->whereNotNull('closed_at')->sum('pnl')+$finance['credit'];
//        $finance['equity']  = $finance['balance'] +  $finance['currentPL'];
//        $finance['freeMargin'] = ($finance['balance']-$finance['usedMargin'])+$finance['bonus'];
//
//        return $finance;
    }

    public function get_assets()
    {
        $assets          = [];
        $favourite_assets_ids = Auth::guard('client')->user()->favourite_assets ?? [];

        $asset_group_id = Auth::guard('client')->user()->asset_group_id;
        if ($asset_group_id) {
            $assetGroup       = AssetGroup::find($asset_group_id);
            $assets           = Asset::whereIn('id',$assetGroup->asset_ids)->where('bid_price','!=',0)->whereIn('type', ['Crypto', 'Forex', 'Stocks','Indx'])->get();
            $favourite_assets = Asset::whereIn('id', $assetGroup->asset_ids)->whereIn('id', $favourite_assets_ids)->where('bid_price','!=',0)->whereIn('type', ['Crypto', 'Forex', 'Stocks','Indx'])->get();
        }

        return [$assets, $favourite_assets, $favourite_assets_ids, $asset_group_id];
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MoneyHistory;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Check if user is authenticated
        $user = auth()->guard('client')->user();
        if (!$user || !$user->broker_id) {
            return redirect()->route('client.login')->with('fail', 'Please login first');
        }

        $inputs = $request->only(
            'open_at_price',
            'currency',
            'amount',
            'type',
            's_l',
            's_p',
        );
        $tab = $request->tab ?? 'fav';
        $asset = Asset::find($request->currency);
        
        // Validate that the asset exists
        if (!$asset) {
            return redirect()->back()->with('fail', __('web.invalid_asset_selected'));
        }
        
        if ($asset->type != 'Crypto' && in_array(Carbon::now()->format('D'), ['Sat', 'Sun'])) {
            return redirect()->back()->with('fail', __('web.market_closed'));
        }
        
        $broker_id = $user->broker_id;
        $finance = (new \App\Http\Controllers\ClientsController)->get_financial_data($broker_id);
        
        $inputs['required_margin'] = 0;
        $inputs['ref_currency'] = $asset->currency;
        
        // Get current bid/ask prices from the selected asset instead of request
        $inputs['open_price'] = $request->type == 1 ? $asset->ask_price : $asset->bid_price;
        
        $inputs['broker_id'] = $broker_id;
        $inputs['status'] = $request->status ?? 'active';
        if (!$request->type) {
            $inputs['type'] = $request->status == 'buy_limit' ? 1 : 2;
            $inputs['open_price'] = $request->open_at_price;
        }

        $order = Order::create($inputs);
        $group_id = $user->asset_group_id;

        if (str_starts_with($order->asset->symbol, 'USD') || (!strpos($order->asset->symbol, 'USD') && $order->asset->currency !== "USD")) {
            $reqMargin = (($request->amount * $inputs['open_price'] * $order->asset->size[$group_id]) / $order->asset->leverage[$group_id]) * (1/$inputs['open_price']);
        } else {//dd($order->asset->size[$group_id]);
            $reqMargin = (($request->amount * $inputs['open_price'] * $order->asset->size[$group_id]) / $order->asset->leverage[$group_id]) * (1/$inputs['open_price']);
        }
        if (($order->asset->is_percentage[$group_id]??0)==1) {
            $reqMargin = ($request->amount * $inputs['open_price'] * $order->asset->size[$group_id]) / $order->asset->leverage[$group_id];
        }

        if (!$request->type) {
            $reqMargin = 0;
        }

        if ($reqMargin > $finance['freeMargin']) {
            $order->delete();
            return redirect()->back()->with('fail', __('web.not_enough_margin'));
        }

        $order->update(['required_margin' => $reqMargin]);

        if (!isset($order->client->options['ignoreLiquidation']) && $request->type) {
            $loop = true;
    
            while ($loop) {
                $order = Order::find($order->id);
                if ($order->pnl != null) {
                    $finance = (new \App\Http\Controllers\ClientsController)->get_financial_data($order->broker_id);
                    if ($finance['equity'] < 0) {
                        $order->delete();
                        return redirect()->back()->with('fail', __('web.liquidation_failed_equity_is_less_than'));
                    }
                    $loop = false;
                }
            }
        }

        $history_inputs = [
            'operation_id' => $order->id,
            'client_id'    => $order->client->id,
            'user_id'      => 0,  
            'type'         => 'New',
            'part'         => 'Order',
            'text'         => 'Opened New order <b>'.($request->type == 1 ? 'Buy' : 'Sell').' ('.$order->asset->name.')</b> with <b>'.$request->amount.'</b> amount.',
        ];
        MoneyHistory::create($history_inputs);

        return redirect()->back()->with(['success'=> __('web.order_created_successfully'),'tab'=>$tab]);
    }

    public function close(Request $request, $id)
    {
        $tab = $request->tab ?? 'active';
        $order = Order::find($id);
        if ($order->closed_at) {
            return redirect()->back()->with('fail', __('web.order_already_closed'));
        }
        if (isset($order->client->options['noCloseAtLoss'])) {
            if ($order->pnl < 0.01) {
                return redirect()->back()->with(['fail'=> __('web.order_cant_close_on_loss'),'tab'=>$tab]);
            }
        }
        $order->update(['closed_at' => Carbon::now()]);
        $history_inputs = [
            'operation_id' => $order->id,
            'client_id'    => $order->client->id,
            'user_id'      => 0,  
            'type'         => 'Close',
            'part'         => 'Order',
            'text'         => 'Closed order <b>'.($order->type == 1 ? 'Buy' : 'Sell').' ('.$order->asset->name.')</b> with <b>'.$order->amount.'</b> amount.',
        ];
        MoneyHistory::create($history_inputs);
        return redirect()->back()->with(['success'=> __('web.order_closed_successfully'),'tab'=>$tab]);
    }

    public function delete(Request $request, $id)
    {
        $tab = $request->tab ?? 'active';
        $order = Order::find($id);
        $order->delete();
        $history_inputs = [
            'operation_id' => $order->id,
            'client_id'    => $order->client->id,
            'user_id'      => 0,  
            'type'         => 'Delete',
            'part'         => 'Order',
            'text'         => 'Deleted order <b>'.($order->type == 1 ? 'Buy' : 'Sell').' ('.$order->asset->name.')</b> with <b>'.$order->amount.'</b> amount.',
        ];
        MoneyHistory::create($history_inputs);
        return redirect()->back()->with(['success'=> __('web.order_deleted_successfully'),'tab'=>$tab]);
    }

    public function update(Request $request, $id)
    {
        // Debug: Log all request data to check what's being received
        Log::info('Order update request received', [
            'id' => $id,
            'method' => $request->method(),
            'url' => $request->url(),
            'all_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        
        $tab = $request->tab ?? 'active';
        $inputs = $request->only(
            's_l',
            's_p',
        );
        $order = Order::find($id);
        if ($order->closed_at) {
            return redirect()->back()->with(['success'=> __('web.order_already_closed'),'tab'=>$tab]);
        }
        $order->update($inputs);
        return redirect()->back()->with(['success'=> __('web.order_updated_successfully'),'tab'=>$tab]);
    }

    public function multiClose(Request $request)
    {
        $tab = $request->tab ?? 'active';
        if ($request->order_id) {
            $orders = Order::whereIn('id', $request->order_id)->get();
            foreach ($orders as $order) {
                if ($order->closed_at) {
                    return redirect()->back()->with('fail', __('web.order_already_closed'));
                }
                if (isset($order->client->options['noCloseAtLoss'])) {
                    if ($order->pnl < 0.01) {
                        return redirect()->back()->with(['fail'=> __('web.order_cant_close_on_loss'),'tab'=>$tab]);
                    }
                }
                $order->update(['closed_at' => Carbon::now()]);
                $history_inputs = [
                    'operation_id' => $order->id,
                    'client_id'    => $order->client->id,
                    'user_id'      => 0,  
                    'type'         => 'Close',
                    'part'         => 'Order',
                    'text'         => 'Closed order <b>'.($order->type == 1 ? 'Buy' : 'Sell').' ('.$order->asset->name.')</b> with <b>'.$order->amount.'</b> amount.',
                ];
                MoneyHistory::create($history_inputs);
            }
            return redirect()->back()->with(['success'=> __('web.orders_closed_successfully'),'tab'=>$tab]);
        }
        return redirect()->back()->with(['success'=> __('web.no_orders_selected'),'tab'=>$tab]);
    }

    public function getPnlData(Request $request)
    {
        $user = auth()->guard('client')->user();
        if (!$user || !$user->broker_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orders = Order::where('broker_id', $user->broker_id)
                      ->where('status', 'active')
                      ->with('asset')
                      ->get();

        $pnlData = [];
        foreach ($orders as $order) {
            $asset = $order->asset;
            if ($asset) {
                // Calculate current PnL based on current market price
                $currentPrice = $order->type == 1 ? $asset->bid_price : $asset->ask_price;
                $pnl = 0;
                
                if ($order->type == 1) { // Buy order
                    $pnl = ($currentPrice - $order->open_price) * $order->amount;
                } else { // Sell order
                    $pnl = ($order->open_price - $currentPrice) * $order->amount;
                }
                
                $pnlData[] = [
                    'order_id' => $order->id,
                    'pnl' => number_format($pnl, 2),
                    'pnl_raw' => $pnl,
                    'current_price' => $currentPrice,
                    'open_price' => $order->open_price,
                    'type' => $order->type,
                    'amount' => $order->amount
                ];
            }
        }

        return response()->json($pnlData);
    }
}
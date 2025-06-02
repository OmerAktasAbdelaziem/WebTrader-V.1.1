<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\MoneyHistory;
use App\Models\Notification;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
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
        $broker_id = auth()->guard('client')->user()->broker_id;
        $finance = (new ClientsController)->get_financial_data($broker_id);
        $inputs['required_margin'] = 0;
        $inputs['ref_currency'] = $asset->currency;
        $inputs['open_price'] = $request->type == 1 ? $request->ask : $request->bid;
        $inputs['broker_id'] = $broker_id;
        $inputs['status'] = $request->status ?? 'active';
        if (!$request->type) {
            $inputs['type'] = $request->status == 'buy_limit' ? 1 : 2;
            $inputs['open_price'] = $request->open_at_price;
        }

        $order = Order::create($inputs);
        $group_id = auth()->guard('client')->user()->asset_group_id;

        if (str_starts_with($order->asset->symbol, 'USD') || (!strpos($order->asset->symbol, 'USD') && $order->asset->currency !== "USD")) {
            $reqMargin = (($request->amount * $inputs['open_price'] * $order->asset->size[$group_id]) / $order->asset->leverage[$group_id]) * (1/$inputs['open_price']);
        } else {
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
                    $finance = (new ClientsController)->get_financial_data($order->broker_id);
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
}
@extends('layouts.mobile')
<link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v1.599') }}">
<link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v1.599') }}">
<style>
    .balance-card{
        top: calc(100% - 105px);
    }
    .card_balance{
        top: calc(100% - 200px);
    }
    .nav-tabs .nav-link {
        padding: 10px 20px;
        font-size: 14px;
        background-color: #f8f9fa;
        color: #000;
        border-radius: 0 !important;
    }
    .nav-tabs .nav-link.active {
        background-color: #007bff !important;
        color: #fff !important;
        border-radius: 0 !important;
    }
    .container {
        text-align: center;
    }
    .center-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin-top: 150px;
    }
    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    select {
        padding: 8px;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .history-container {
        text-align: center;
        margin-top: 150px;
    }
    .iconify {
        font-size: 60px;
        color: #6c757d;
    }
    .green-text {
        color: green;
    }
    .rtl{
        direction: rtl;
    }
</style>

@section('content')
<div class="container-fluid p-0">
    <ul class="nav nav-tabs w-100" id="quotesTabs" role="tablist" style="display: flex; justify-content: space-between;">
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if((($tab == 'active' || $tab == 'fav') && !session('tab')) || (session('tab') == 'active' || session('tab') == 'fav')) active @endif" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">{{__('web.active')}} ({{$activeOrders->count()}})</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'pending' && !session('tab')) || session('tab') == 'pending') active @endif" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">{{__('web.pending')}} ({{$pendingOrders->count()}})</button>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'history' && !session('tab')) || session('tab') == 'history') active @endif" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">{{__('web.history')}}</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="firstTabSetContent">
        <div class="tab-pane fade @if((($tab == 'active' || $tab == 'fav') && !session('tab')) || (session('tab') == 'active' || session('tab') == 'fav')) show active @endif" id="active" role="tabpanel" aria-labelledby="active-tab">
            @if($activeOrders->isEmpty())
                <div class="mt-2">
                    <span class="iconify" data-icon="mdi:clipboard-text-outline" data-width="50" data-height="50"></span>
                    <div>{{__('web.no_active_orders')}}</div>
                </div>
            @else
                <div class="container p-0">
                    <div class="table-responsive" style="max-height: 60%; overflow-y: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="max-width: 19px;">
                                        <input class="form-check-input me-3 check-all-table" data-target="check-active" type="checkbox">
                                    </th>
                                    <th>{{__('web.symbol')}}</th>
                                    <th>{{__('web.type')}}</th>
                                    <th>{{__('web.amount')}}</th>
                                    <th>{{__('web.profitloss')}}</th>
                                    <th>
                                        @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#multiCloseModal" style="font-size: 11.6px;">{{__('web.close')}}</button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeOrders as $index => $order)
                                    <tr>
                                        <td style="max-width: 19px;@if ($index %2 == 0) background: rgba(0, 0, 0, 0.05); @endif">
                                            <input class="form-check-input me-3 check-active check-number" type="checkbox" form="multiCloseForm" name="order_id[]" value="{{$order->id}}" aria-label="...">
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">{{ $order->asset->name }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">
                                            @if($order->type == 1)
                                                {{ __('web.buy') }}
                                            @elseif($order->type == 2)
                                                {{ __('web.sell') }}
                                            @else
                                                {{ $order->type }}
                                            @endif
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">{{ $order->amount }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}" class="pnl active_pnl" data-order-id="{{$order->id}}">
                                            <div class="{{$order->pnl < 0 ? 'text-danger' : 'text-success'}}">
                                                {{ number_format($order->pnl, 2) }}
                                            </div>
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">
                                        </td>
                                    </tr>
                                    <tr id="orderDetails{{ $order->id }}" class="collapse asset-details">
                                        <td colspan="6" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                            <div class="card card-body text-center" style="font-size: 10px;">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p><strong>{{__('web.id')}} :</strong> {{ $order->id }}</p>
                                                        <p><strong>{{__('web.created_at')}} :</strong> {{ $order->created_at }}</p>
                                                        <p><strong>{{__('web.open_price')}} :</strong> {{ $order->open_price }}</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><strong>{{__('web.tp')}} :</strong> {{ $order->s_p }}</p>
                                                        <p><strong>{{__('web.sl')}} :</strong> -{{ $order->s_l }}</p>
                                                        <p><strong>{{__('web.required_margin')}} :</strong> {{ number_format($order->required_margin,'2','.',',') }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editOrderModal{{$order->id}}">{{__('web.edit_order')}}</button>
                                                    </div>
                                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                                        <div class="col-6">
                                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#closeOrderModal{{$order->id}}" style="font-size: 11.6px;">{{__('web.close')}}</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="card balance-card @if ($locale == 'ar') rtl @endif" style="position: fixed;">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.05)">
                    <div class="row g-3">
                        <div class="col-12 text-center m-0 collabse_balance m-2">
                            <strong> {{__('web.balance')}} : $ {{number_format($finance['balance'],'2','.',',')}} ▼ </strong>
                        </div>
                        <div class="col-6" style="border-right: 1px solid black!important">
                            <div class="row g-3">
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.balance')}} :
                                    <div class="border-radius">$ {{number_format($finance['balance'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.profitloss')}} :
                                    <div class="border-radius currentPL">$ {{number_format($finance['currentPL'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.free')}} :
                                    <div class="border-radius">$ {{number_format($finance['freeMargin'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.id')}} :
                                    <div class="border-radius">{{auth()->guard('client')->user()->id}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row g-3">
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.equity')}} :
                                    <div class="border-radius equity">$ {{number_format($finance['equity'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.margin')}} :
                                    <div class="border-radius">$ {{number_format($finance['usedMargin'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.bonus')}} :
                                    <div class="border-radius">$ {{number_format($finance['bonus'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.credit')}} :
                                    <div class="border-radius">$ {{number_format($finance['credit'],'2','.',',')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if(($tab == 'pending' && !session('tab')) || session('tab') == 'pending') show active @endif" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            @if($pendingOrders->isEmpty())
                <div class="mt-2">
                    <span class="iconify" data-icon="mdi:clipboard-text-outline" data-width="50" data-height="50"></span>
                    <div>{{__('web.no_pending_orders')}}</div>
                </div>
            @else
                <div class="container p-0">
                    <div class="table-responsive" style="max-height: 60%; overflow-y: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{__('web.symbol')}}</th>
                                    <th>{{__('web.type')}}</th>
                                    <th>{{__('web.amount')}}</th>
                                    <th>{{__('web.limit')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingOrders as $index => $order)
                                    <tr>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>{{ $order->asset->name }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                            @if($order->type == 1)
                                                {{ __('web.buy') }}
                                            @elseif($order->type == 2)
                                                {{ __('web.sell') }}
                                            @else
                                                {{ $order->type }}
                                            @endif
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>{{ $order->amount }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>{{ $order->open_price }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                            <form action="{{ route('order.delete', ['id'=>$order->id]) }}" class="d-none" method="POST" id="deleteOrderForm{{ $order->id }}">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" name="tab" value="pending">
                                            </form>
                                            <button type="submit" class="btn btn-danger btn-sm" form="deleteOrderForm{{ $order->id }}" style="font-size: 11.6px;">{{__('web.delete')}}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            <div class="card balance-card @if ($locale == 'ar') rtl @endif" style="position: fixed;">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.05)">
                    <div class="row g-3">
                        <div class="col-12 text-center m-0 collabse_balance m-2">
                            <strong> {{__('web.balance')}} : $ {{number_format($finance['balance'],'2','.',',')}} ▼ </strong>
                        </div>
                        <div class="col-6" style="border-right: 1px solid black!important">
                            <div class="row g-3">
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.balance')}} :
                                    <div class="border-radius">$ {{number_format($finance['balance'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.profitloss')}} :
                                    <div class="border-radius currentPL">$ {{number_format($finance['currentPL'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.free')}} :
                                    <div class="border-radius">$ {{number_format($finance['freeMargin'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.id')}} :
                                    <div class="border-radius">{{auth()->guard('client')->user()->id}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row g-3">
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.equity')}} :
                                    <div class="border-radius equity">$ {{number_format($finance['equity'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.margin')}} :
                                    <div class="border-radius">$ {{number_format($finance['usedMargin'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.bonus')}} :
                                    <div class="border-radius">$ {{number_format($finance['bonus'],'2','.',',')}}</div>
                                </div>
                                <div class="col-12 d-flex text-start border-radius border-dark">
                                    {{__('web.credit')}} :
                                    <div class="border-radius">$ {{number_format($finance['credit'],'2','.',',')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade @if(($tab == 'history' && !session('tab')) || session('tab') == 'history') show active @endif" id="history" role="tabpanel" aria-labelledby="history-tab">
            <form class="ajax-form" method="GET" data-tab="history">
                <input type="hidden" name="tab" value="history">
                <div class="row g-0">
                    <div class="col-6 p-1">
                        <select class="w-100" name="type_filter" onchange="this.form.submit()">
                            <option value="general"    @if ($type_filter == 'general') selected @endif>{{__('web.general_report')}}</option>
                            <option value="old_trader" @if ($type_filter == 'old_trader') selected @endif>{{__('web.old_trader')}}</option>
                            <option value="money_trx"  @if ($type_filter == 'money_trx') selected @endif>{{__('web.money_trx')}}</option>
                        </select>
                    </div>
                    <div class="col-6 p-1">
                        <select class="w-100" name="time_filter" onchange="this.form.submit()">
                            <option value="all"           @if ($time_filter == 'all') selected @endif>{{__('web.all')}}</option>
                            <option value="today"         @if ($time_filter == 'today') selected @endif>{{__('web.today')}}</option>
                            <option value="current_week"  @if ($time_filter == 'current_week') selected @endif>{{__('web.current_week')}}</option>
                            <option value="current_month" @if ($time_filter == 'current_month') selected @endif>{{__('web.current_month')}}</option>
                            <option value="last_3_month"  @if ($time_filter == 'last_3_month') selected @endif>{{__('web.last_3_month')}}</option>
                        </select>
                    </div>
                </div>
            </form>
        
            <div class="container p-0">
                <div class="table-responsive" style="max-height: 49%; overflow-y: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('web.symbol')}}</th>
                                <th class="text-center">{{__('web.type')}}</th>
                                <th class="text-center">{{__('web.amount')}}</th>
                                <th class="text-end">{{__('web.profitloss')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalWithdraw = 0;
                                $totalBonusOut = 0;
                                $totalDeposit = 0;
                                $totalBonusIn = 0;
                                $totalPnl = 0;
                                $index = 0;
                            @endphp
                            @foreach($history as $order)
                                @if (isset($order->closed_at) && $order->closed_at != null)
                                    @php
                                        $totalPnl += $order->pnl;
                                    @endphp
                                    <tr data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>{{ $order->asset->name }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-center">
                                            @if($order->type == 1)
                                                {{ __('web.buy') }}
                                            @elseif($order->type == 2)
                                                {{ __('web.sell') }}
                                            @else
                                                {{ __('web.'.$order->type) }}
                                            @endif
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-center">{{ $order->amount }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-end">
                                            <div class="{{$order->pnl < 0 ? 'text-danger' : 'text-success'}}">
                                                {{ number_format($order->pnl, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="orderDetails{{ $order->id }}" class="collapse asset-details">
                                        <td colspan="4" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                            <div class="card card-body text-center" style="font-size: 10px;">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.id')}} :</strong> {{ $order->id }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.created_at')}} :</strong> {{ $order->created_at }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.close_time')}} :</strong> {{ $order->closed_at }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.close_price')}} :</strong> {{ $order->close_price }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.tp')}} :</strong> {{ $order->s_p }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.sl')}} :</strong> -{{ $order->s_l }}</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <p><strong>{{__('web.required_margin')}} :</strong> {{ number_format($order->required_margin,'2','.',',') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @if ($order->type == 'deposit')
                                        @php
                                            $totalDeposit += $order->amount;
                                        @endphp
                                    @elseif($order->type == 'withdraw')
                                        @php
                                            $totalWithdraw += $order->amount;
                                        @endphp
                                    @elseif($order->type == 'bonus in')
                                        @php
                                            $totalBonusIn += $order->amount;
                                        @endphp
                                    @elseif($order->type == 'bonus out')
                                        @php
                                            $totalBonusOut += $order->amount;
                                        @endphp
                                    @endif
                                    <tr>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>{{ $order->id }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-center">
                                            {{ __('web.'.$order->type) }}
                                        </td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-center">{{ $order->amount }}</td>
                                        <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="text-end">{{ $order->amount }}</td>
                                    </tr>
                                @endif
                                @php
                                    $index++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card card_balance m-0" style="position: absolute;">
                    <div class="card-body" style="background: rgba(0, 0, 0, 0.05)">
                        <div class="row g-3">
                            <div class="col-6 text-center">
                                <label for="" class="form-label">{{__('web.deposit')}}</label>
                                <div>$ {{number_format($totalDeposit,'2','.',',')}}</div>
                            </div>
                            <div class="col-6 text-center">
                                <label for="" class="form-label">{{__('web.withdraw')}}</label>
                                <div>$ {{number_format($totalWithdraw,'2','.',',')}}</div>
                            </div>
                            <div class="col-4 text-center">
                                <label for="" class="form-label">{{__('web.bonus_in')}}</label>
                                <div>$ {{number_format($totalBonusIn,'2','.',',')}}</div>
                            </div>
                            <div class="col-4 text-center">
                                <label for="" class="form-label">{{__('web.bonus_out')}}</label>
                                <div>$ {{number_format($totalBonusOut,'2','.',',')}}</div>
                            </div>
                            <div class="col-4 text-center">
                                <label for="" class="form-label">{{__('web.pnl')}}</label>
                                <div class="@if ($totalPnl<0) text-danger @else text-success @endif">$ {{number_format($totalPnl,'2','.',',')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="multiCloseModal" tabindex="-1" aria-labelledby="multiCloseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multiCloseModalLabel">{{__('web.confirm_close')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('order.multiClose') }}" id="multiCloseForm" method="POST" class="d-none">
                    @csrf
                </form>
                <div>{{__('web.confirm_close_order')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('web.close')}}</button>
                <button type="submit" class="btn btn-primary" form="multiCloseForm">{{__('web.confirm')}}</button>
            </div>
        </div>
    </div>
</div>

    @foreach($activeOrders as $index => $order)
        <div class="modal fade" id="closeOrderModal{{$order->id}}" tabindex="-1" aria-labelledby="closeOrderModal{{$order->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="closeOrderModal{{$order->id}}Label">{{__('web.confirm_close')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('order.close',$order->id) }}" id="closeForm{{$order->id}}" method="POST" class="d-none">
                            @csrf,
                            <input type="hidden" name="tab" value="active">
                        </form>
                        <div>{{__('web.confirm_close_order')}}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('web.close')}}</button>
                        <button type="submit" class="btn btn-primary" form="closeForm{{$order->id}}">{{__('web.confirm')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editOrderModal{{$order->id}}" tabindex="-1" aria-labelledby="editOrderModal{{$order->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModal{{$order->id}}Label">{{__('web.edit_order')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tab" value="active">
                            <div class="row g-3 justify-content-center">
                                <div class="col-6">
                                    <div class="mt-2">
                                        <label for="s_l{{$order->id}}" class="form-label">{{__('web.set_stop_loss')}}</label>
                                        <input type="number" class="form-control" id="s_l{{$order->id}}" step="any" value="{{$order->s_l}}" name="s_l">
                                    </div>
                                </div>
                            
                                <div class="col-6">
                                    <div class="mt-2">
                                        <label for="s_p{{$order->id}}" class="form-label">{{__('web.set_take_profit')}}</label>
                                        <input type="number" class="form-control" id="s_p{{$order->id}}" step="any" value="{{$order->s_p}}" name="s_p">
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <button type="submit" class="btn btn-primary">{{ __('web.update_order') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        var client_id = {{auth()->guard('client')->user()->id}};
        var assetId = 1;
    </script>
    <script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>
    <script>
        $(document).ready(function () {
            $('.collabse_balance').on('click', function () {
                let $card = $(this).closest('.card');
                if ($card.length) {
                    if ($(this).hasClass('clicked')) {
                        $card.css('top', '');
                        $(this).removeClass('clicked');
                    }else{
                        let cardHeight = $card.find('.row').outerHeight();
                        let currentTop = parseInt($card.css('top'));
                        let newTop = currentTop - cardHeight;

                        $card.animate({ top: newTop + 'px' }, 300);

                        $(this).addClass('clicked');
                    }
                    
                }
            });
        });
    </script>
@endsection
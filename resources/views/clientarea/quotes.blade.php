@extends('layouts.mobile')
<style>
    /* Modern redesign with glassmorphism and interactive effects */
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    }
    .container.p-0 {
        margin-top: 20px;
        margin-bottom: 20px;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        padding: 18px 8px 18px 8px;
    }
    .nav-tabs {
        border: none;
        background: transparent;
    }
    .nav-tabs .nav-link {
        padding: 10px 0;
        font-size: 15px;
        background: rgba(255,255,255,0.5);
        color: #222;
        border: none;
        border-radius: 12px 12px 0 0;
        margin: 0 2px;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .nav-tabs .nav-link.active {
        background: linear-gradient(90deg, #4699D9 0%, #6ee7b7 100%);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(70,153,217,0.12);
    }
    .nav-item {
        flex: 1;
    }
    .star-icon, .fa-star {
        cursor: pointer;
        color: #cbd5e1;
        transition: color 0.2s, transform 0.2s;
    }
    .fa-star.text-warning, .star-icon.favorited {
        color: #facc15 !important;
        transform: scale(1.2) rotate(-10deg);
        text-shadow: 0 2px 8px #fde68a;
    }
    .fa-star.text-secondary {
        color: #cbd5e1 !important;
    }
    table.table {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        background: rgba(255,255,255,0.85);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 0;
    }
    thead th {
        background: linear-gradient(90deg, #e0e7ff 0%, #f8fafc 100%);
        color: #222;
        font-size: 13px;
        border: none;
        font-weight: 600;
    }
    th, td {
        border: none;
        padding: 12px 8px;
        text-align: center;
        font-size: 13px;
        vertical-align: middle;
    }
    tr.asset-row {
        transition: background 0.18s, box-shadow 0.18s;
        cursor: pointer;
    }
    tr.asset-row:hover {
        background: #e0f2fe !important;
        box-shadow: 0 2px 12px #bae6fd;
    }
    tr.collapse.asset-details > td {
        background: rgba(236, 254, 255, 0.25) !important;
        border-top: 1px solid #bae6fd;
    }
    .card.card-body {
        background: rgba(255,255,255,0.95);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(70,153,217,0.08);
        border: none;
        padding: 18px 8px;
        margin-bottom: 0;
    }
    .btn {
        border-radius: 8px !important;
        font-size: 13px;
        font-weight: 500;
        transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.18s;
        box-shadow: 0 2px 8px rgba(70,153,217,0.08);
    }
    .btn-success {
        background: linear-gradient(90deg, #34d399 0%, #10b981 100%);
        border: none;
    }
    .btn-success:hover {
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        transform: translateY(-2px) scale(1.04);
    }
    .btn-danger {
        background: linear-gradient(90deg, #f87171 0%, #ef4444 100%);
        border: none;
    }
    .btn-danger:hover {
        background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
        transform: translateY(-2px) scale(1.04);
    }
    .btn-primary {
        background: linear-gradient(90deg, #60a5fa 0%, #2563eb 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
        transform: translateY(-2px) scale(1.04);
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: rgba(255,255,255,0.85);
        font-size: 13px;
        transition: border 0.18s, box-shadow 0.18s;
    }
    .form-control:focus, .form-select:focus {
        border: 1.5px solid #60a5fa;
        box-shadow: 0 2px 8px #bae6fd;
    }
    .search {
        margin-bottom: 18px;
        box-shadow: 0 2px 8px #bae6fd;
    }
    .rtl{
        direction: rtl;
    }
    /* Responsive */
    @media (max-width: 600px) {
        .container.p-0 {
            padding: 6px 2px;
        }
        .card.card-body {
            padding: 10px 2px;
        }
        th, td {
            padding: 7px 2px;
        }
    }
</style>

@section('content')
<div class="container p-0">
    <ul class="nav nav-tabs mb-3 w-100" id="quotesTabs" role="tablist" style="display: flex; justify-content: space-between;">
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') active @endif" id="fav-tab" data-bs-toggle="tab" data-bs-target="#fav" type="button" role="tab" aria-controls="fav" aria-selected="true">{{__('web.favorites')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') active @endif" id="forex-tab" data-bs-toggle="tab" data-bs-target="#forex" type="button" role="tab" aria-controls="forex" aria-selected="false">{{__('web.forex')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') active @endif" id="cfd-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="false">{{__('web.crypto')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') active @endif" id="ai-tab" data-bs-toggle="tab" data-bs-target="#stocks" type="button" role="tab" aria-controls="stocks" aria-selected="false">{{__('web.stocks')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') active @endif" id="indices-tab" data-bs-toggle="tab" data-bs-target="#indices" type="button" role="tab" aria-controls="indices" aria-selected="false">{{__('web.indices')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <button class="nav-link w-100 @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') active @endif" id="commodity-tab" data-bs-toggle="tab" data-bs-target="#commodity" type="button" role="tab" aria-controls="commodity" aria-selected="false">{{__('web.commodity')}}</button>
        </li>
    </ul>
    <div class="tab-content" id="quotesTabsContent">
        <div class="tab-pane fade @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') show active @endif" id="fav" role="tabpanel" aria-labelledby="fav-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_fav_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead style="position: sticky; top: 0; background-color: #fff;">
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="favAssets">
                        @foreach($favourite_assets as $index => $asset)
                            @if(is_object($asset))
                                <tr class="asset-row">
                                    <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                        <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'fav'])}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">
                                            {{ $asset->name }}
                                        </span>
                                    </td>
                                    <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}"  data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                    <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}"  data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                                </tr>
                                <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                    <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                        <div class="card card-body text-center" style="font-size: 10px;">
                                            <div class="row g-3" style="font-size: 12px">
                                                <div class="col-6">
                                                    <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                                </div>
                                            </div>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="fav" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="fav" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') show active @endif" id="forex" role="tabpanel" aria-labelledby="forex-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_forex_assets')}}">
            <div class="table-responsive" style="max-height: 68%; width: 100%; overflow-y: auto;">
                <table class="table" style="width: 100%;">
                    <thead style="position: sticky; top: 0; background-color: #fff;">
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="forexAssets">
                        @foreach($forexAssets as $index => $asset)
                            <tr class="asset-row">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'forex'])}}" style="text-decoration: none;">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name"  data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}"  data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}"  data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="card card-body text-center" style="font-size: 10px;">
                                        <div class="row g-3" style="font-size: 12px">
                                            <div class="col-6">
                                                <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="forex" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="forex" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#tradeHoursModal">{{__('web.trade_hours')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') show active @endif" id="crypto" role="tabpanel" aria-labelledby="crypto-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_crypto_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="cryptoAssets">
                        @foreach($cryptoAssets as $index => $asset)
                        <tr class="asset-row" >
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'crypto'])}}" style="text-decoration: none;">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="card card-body text-center" style="font-size: 10px;">
                                        <div class="row g-3" style="font-size: 12px">
                                            <div class="col-6">
                                                <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="crypto" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="crypto" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#CryptoHoursModal">{{__('web.trade_hours')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') show active @endif" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_stocks_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="stocksAssets">
                        @foreach($stocksAssets as $index => $asset)
                        <tr class="asset-row">
                            <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'stocks'])}}" style="text-decoration: none;">
                                    <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                </a>
                                <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">
                                    {{ $asset->name }}
                                </span>
                            </td>
                            <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                            <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                        </tr>
                        <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                            <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                <div class="card card-body text-center" style="font-size: 10px;">
                                    <div class="row g-3" style="font-size: 12px">
                                        <div class="col-6">
                                            <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="stocks" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="stocks" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#StocksHoursModal">{{__('web.trade_hours')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') show active @endif" id="indices" role="tabpanel" aria-labelledby="indices-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_indices_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="indicesAssets">
                        @foreach($indicesAssets as $index => $asset)
                            <tr class="asset-row">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'indices'])}}" style="text-decoration: none;">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="card card-body text-center" style="font-size: 10px;">
                                        <div class="row g-3" style="font-size: 12px">
                                            <div class="col-6">
                                                <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="indices" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="indices" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#IndicesHoursModal">{{__('web.trade_hours')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') show active @endif" id="commodity" role="tabpanel" aria-labelledby="commodity-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_commodity_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-start">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="commodityAssets">
                        @foreach($commodityAssets as $index => $asset)
                            <tr class="asset-row">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'commodity'])}}" style="text-decoration: none;">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}" aria-expanded="false" aria-controls="assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="card card-body text-center" style="font-size: 10px;">
                                        <div class="row g-3" style="font-size: 12px">
                                            <div class="col-6">
                                                <p><strong>{{__('web.symbol')}} :</strong> {{ $asset->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.type')}} :</strong> {{ $asset->type }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.contract_size')}} :</strong> {{ $asset->size[$asset_group_id] }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>{{__('web.leverage')}} :</strong> {{ $asset->leverage[$asset_group_id] }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <button class="btn btn-success btn-sm w-100 new_order" data-asset="{{$asset->id}}" data-tab="commodity" data-bs-toggle="modal" data-bs-target="#newOrderModal">{{__('web.new_order')}}</button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-danger btn-sm w-100 pending_order" data-asset="{{$asset->id}}" data-tab="commodity" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">{{__('web.new_pending_order')}}</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-success btn-sm w-100">{{__('web.new_chart')}}</a>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#CommodityHoursModal">{{__('web.trade_hours')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@if(!isset(auth()->guard('client')->user()->options['cantOpen']))
    <!-- New Order Modal -->
    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                <!-- New Market Order Tab -->
                    <div class="tab-content mt-3" id="orderTabsContent">
                        <!-- New Market Order -->
                        <div class="tab-pane fade show active" id="marketOrder" role="tabpanel">
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab" id="newTab">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="asset-select" class="form-label">{{__('web.item')}}</label>
                                        <select class="single-select form-select inside-modal me-2" id="asset-select" name="currency">
                                            @foreach ($forexAssets as $item)
                                                <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                            @endforeach
                                            @foreach ($cryptoAssets as $item)
                                                <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                            @endforeach
                                            @foreach ($stocksAssets as $item)
                                                <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                            @endforeach
                                            @foreach ($indicesAssets as $item)
                                                <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                            @endforeach
                                            @foreach ($commodityAssets as $item)
                                                <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="newAmount" class="form-label">{{__('web.amount')}}</label>
                                        <input type="number" class="form-control" id="newAmount" name="amount" min="0.01" step="any" value="0.01">
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="stopLossSwitch">
                                            <label class="form-check-label" for="stopLossSwitch">{{__('web.set_stop_loss')}}</label>
                                        </div>
                                        <div id="stopLossContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="stopLossInput" step="any" name="s_l">
                                        </div>
                                    </div>
                                
                                    <!-- Set Take Profit -->
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitch">
                                            <label class="form-check-label" for="takeProfitSwitch">{{__('web.set_take_profit')}}</label>
                                        </div>
                                        <div id="takeProfitContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="takeProfitInput" step="any" name="s_p">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-danger btn-md me-2" formaction="{{route('order.store',['type' => 2])}}">
                                        <span>{{__('web.sell')}} <strong id="sell-price"> 0</strong></span>
                                    </button>
                                    <button type="submit" class="btn btn-success btn-md ms-2" formaction="{{route('order.store',['type' => 1])}}">
                                        <span>{{__('web.buy')}} <strong id="buy-price">0 </strong></span>
                                    </button>
                                </div>
                                <input type="hidden" class="form-control" name="bid" id="bid" value="0" readonly>
                                <input type="hidden" class="form-control" name="ask" id="ask" value="0" readonly>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Order Modal -->
    <div class="modal fade" id="newPendingOrderModal" tabindex="-1" aria-labelledby="newPendingOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPendingOrderModalLabel">{{__('web.new_pending_order')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your form or content here -->
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="currency" id="currency">
                        <input type="hidden" name="tab" id="pendingTab">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="orderType" class="form-label">{{__('web.order_type')}}</label>
                                <select class="form-select" id="orderType" name="status">
                                    <option value="buy_limit"> {{__('web.buy_limit') }} </option>
                                    <option value="sell_limit">{{__('web.sell_limit') }}</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="orderOpenAt" class="form-label">{{__('web.value')}}</label>
                                <input type="number" class="form-control" id="orderOpenAt" step="any" name="open_at_price">
                            </div>
                            <div class="col-12">
                                <label for="orderAmount" class="form-label">{{__('web.amount')}}</label>
                                <input type="number" class="form-control" id="orderAmount" min="0.01" name="amount" step="any" value="0.01">
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input me-2" type="checkbox" id="stopLossSwitchPending">
                                    <label class="form-check-label" for="stopLossSwitchPending"Pending>{{__('web.set_stop_loss')}}</label>
                                </div>
                                <div id="stopLossContainerPending" class="mt-2" style="display: none;">
                                    <input type="number" class="form-control" id="stopLossInput" step="any" name="s_l">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitchPending">
                                    <label class="form-check-label" for="takeProfitSwitchPending">{{__('web.set_take_profit')}}</label>
                                </div>
                                <div id="takeProfitContainerPending" class="mt-2" style="display: none;">
                                    <input type="number" class="form-control" id="takeProfitInput" step="any" name="s_p">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">{{ __('web.place_pending_order') }}</button>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="bid" value="0" readonly>
                        <input type="hidden" class="form-control" name="ask" value="0" readonly>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Trade Hours Modal Forex -->
<div class="modal fade" id="tradeHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th class="text-center">{{__('web.day_of_week')}}</th>
                        <th class="text-center">{{__('web.open_time')}}</th>
                        <th class="text-center">{{__('web.close_time')}}</th>
                    </tr>
                    <tr><td>{{__('web.monday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.tuesday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.wednesday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.thursday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.friday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Crypto -->
<div class="modal fade" id="CryptoHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th class="text-center">{{__('web.day_of_week')}}</th>
                        <th class="text-center">{{__('web.open_time')}}</th>
                        <th class="text-center">{{__('web.close_time')}}</th>
                    </tr>
                    <tr><td>{{__('web.monday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.tuesday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.wednesday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.thursday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.friday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.saturday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                    <tr><td>{{__('web.sunday')}}</td><td>03:00 AM</td><td>02:59 AM</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Stocks -->
<div class="modal fade" id="StocksHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">{{__('web.american_market')}}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">{{__('web.british_market')}}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>11:00 AM</td><td>7:30 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>11:00 AM</td><td>7:30 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>11:00 AM</td><td>7:30 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>11:00 AM</td><td>7:30 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>11:00 AM</td><td>7:30 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">{{__('web.german_market')}}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>10:00 AM</td><td>6:30 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>10:00 AM</td><td>6:30 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>10:00 AM</td><td>6:30 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>10:00 AM</td><td>6:30 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>10:00 AM</td><td>6:30 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">{{__('web.japanese_market')}}</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>3:00 AM</td><td>9:00 AM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>3:00 AM</td><td>9:00 AM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>3:00 AM</td><td>9:00 AM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>3:00 AM</td><td>9:00 AM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>3:00 AM</td><td>9:00 AM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Indices -->
<div class="modal fade" id="IndicesHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">Dow Jones</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">Nasdaq</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">Nikkei</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">FTSE 100</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Commodity -->
<div class="modal fade" id="CommodityHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <th class="text-center">{{__('web.day_of_week')}}</th>
                        <th class="text-center">{{__('web.open_time')}}</th>
                        <th class="text-center">{{__('web.close_time')}}</th>
                    </tr>
                    <tr><td>{{__('web.monday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.tuesday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.wednesday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.thursday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.friday')}}</td><td>12:00 AM</td><td>11:59 PM</td></tr>
                    <tr><td>{{__('web.saturday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                    <tr><td>{{__('web.sunday')}}</td><td>{{__('web.closed')}}</td><td>{{__('web.closed')}}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

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

        $(".search").on("keyup", function () {
            const searchTerm = $(this).val().trim().toLowerCase();

            $(this).closest('.tab-pane').find('.asset-row').each(function () {
                const $row = $(this);
                const $nameElement = $row.find(".text-start .name");

                const assetName = $nameElement.length ? $nameElement.text().trim().toLowerCase() : "";

                if (assetName.includes(searchTerm)) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
        });
        document.getElementById('stopLossSwitch').addEventListener('change', function() {
            document.getElementById('stopLossContainer').style.display = this.checked ? 'block' : 'none';
        });

        // Take Profit Toggle
        document.getElementById('takeProfitSwitch').addEventListener('change', function() {
            document.getElementById('takeProfitContainer').style.display = this.checked ? 'block' : 'none';
        });

        document.getElementById('stopLossSwitchPending').addEventListener('change', function() {
            document.getElementById('stopLossContainerPending').style.display = this.checked ? 'block' : 'none';
        });

        // Take Profit Toggle
        document.getElementById('takeProfitSwitchPending').addEventListener('change', function() {
            document.getElementById('takeProfitContainerPending').style.display = this.checked ? 'block' : 'none';
        });
        
        $('#asset-select').on('change', function() {
            const selectedOption = $(this).find(':selected');
            
            const bidPrice = selectedOption.data('bid'); 
            const askPrice = selectedOption.data('ask'); 
            
            $('#bid').val(bidPrice);
            $('#ask').val(askPrice);

            $('#sell-price').text(bidPrice);
            $('#buy-price').text(askPrice);
        });

        document.querySelectorAll('.new_order').forEach(button => {
            button.addEventListener('click', function() {
                const assetId = this.getAttribute('data-asset');
                const tab     = this.getAttribute('data-tab');

                const assetSelect = document.getElementById('asset-select');
                const newTab      = document.getElementById('newTab');

                assetSelect.value = assetId;
                newTab.value = tab;

                assetSelect.dispatchEvent(new Event('change'));
            });
        });

        document.querySelectorAll('.pending_order').forEach(button => {
            button.addEventListener('click', function() {
                const pendingTab = document.getElementById('pendingTab');
                const currency   = document.getElementById('currency');
                const assetId    = this.getAttribute('data-asset');
                const tab        = this.getAttribute('data-tab');
                
                pendingTab.value = tab;
                currency.value   = assetId;
            });
        });
        
    });
</script>

@endsection
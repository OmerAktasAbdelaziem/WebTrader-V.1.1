@extends('layouts.mobile')
<style>
    .balance-card{
        top: calc(100% - 105px);
    }
    .nav-tabs .nav-link {
        padding: 5px 5px;
        font-size: 12px;
        background-color: #cccccc;
        color: #000;
        border-radius: 0 !important;
    }
    .nav-tabs .nav-link.active {
        background-color: #4699D9 !important;
        color: #fff !important;
        border-radius: 0 !important;
    }
    .nav-item {
        flex: 1;
    }
    .star-icon {
        cursor: pointer;
        color: gray;
    }
    .star-icon.favorited {
        color: yellow;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #ddd;
    }

    .rtl{
        direction: rtl;
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
            <div class="card balance-card @if ($locale == 'ar') rtl @endif" style="position: fixed;">
                <div class="card-body" style="background: rgba(0, 0, 0, 0.05)">
                    <div class="row g-3">
                        <div class="col-12 text-center m-0 collabse_balance m-2">
                            <strong> {{__('web.balance')}} : $ {{number_format($finance['balance'],'2','.',',')}} ▼ </strong>
                        </div>
                        <div class="col-6" @if ($locale == 'ar') style="border-left: 1px solid black!important" @else style="border-right: 1px solid black!important" @endif >
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
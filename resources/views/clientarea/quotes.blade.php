@extends('layouts.mobile')
<style>
    /* Clean minimalist trading dashboard with off-white and black design */
    :root {
        --primary-bg: #fafafa;
        --secondary-bg: #f5f5f5;
        --card-bg: #ffffff;
        --border-color: #e5e5e5;
        --text-primary: #1a1a1a;
        --text-secondary: #666666;
        --text-muted: #999999;
        --hover-bg: #f0f0f0;
        --active-bg: #e8e8e8;
        --shadow-soft: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-hover: 0 4px 16px rgba(0, 0, 0, 0.12);
        --border-radius: 8px;
        --animation-speed: 0.2s;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--primary-bg);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
        margin: 0;
        color: var(--text-primary);
        line-height: 1.6;
    }

    .container.p-0 {
        margin: 20px auto;
        max-width: 95vw;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 24px 20px;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Clean Navigation Tabs */
    .nav-tabs {
        border: none;
        background: var(--secondary-bg);
        margin-bottom: 20px;
        display: flex;
        gap: 4px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        border-radius: var(--border-radius);
        padding: 4px;
    }

    .nav-tabs::-webkit-scrollbar {
        display: none;
    }

    .nav-tabs .nav-link {
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        background: var(--card-bg);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        border-radius: calc(var(--border-radius) - 2px);
        margin: 0;
        transition: all var(--animation-speed) ease;
        white-space: nowrap;
        min-width: fit-content;
    }

    .nav-tabs .nav-link.active {
        background: var(--text-primary);
        color: var(--card-bg);
        border-color: var(--text-primary);
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        background: var(--hover-bg);
        color: var(--text-primary);
        border-color: var(--text-secondary);
    }

    /* Gold Star Icons */
    .star-icon, .fa-star {
        cursor: pointer;
        color: var(--text-muted);
        transition: all var(--animation-speed) ease;
        font-size: 1.1em;
    }

    .fa-star.text-warning, .star-icon.favorited {
        color: #FFD700 !important; /* Gold color */
        font-weight: 900;
        text-shadow: 0 0 2px rgba(255, 215, 0, 0.3);
    }

    .fa-star.text-secondary {
        color: var(--text-muted) !important;
    }

    .fa-star:hover {
        color: #FFD700 !important;
        transform: scale(1.1);
    }

    /* Clean Table Design */
    .table-responsive {
        border-radius: var(--border-radius);
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        max-height: 70vh;
    }

    table.table {
        margin-bottom: 0;
        background: transparent;
    }

    thead th {
        background: var(--secondary-bg);
        color: var(--text-primary);
        font-size: 12px;
        font-weight: 600;
        border: none;
        padding: 12px 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 1px solid var(--border-color);
    }

    tbody tr.asset-row {
        border: none;
        background: var(--card-bg);
        transition: all var(--animation-speed) ease;
        cursor: pointer;
        border-bottom: 1px solid var(--border-color);
    }

    tbody tr.asset-row:hover {
        background: var(--hover-bg) !important;
    }

    tbody tr.asset-row:nth-child(even) {
        background: var(--secondary-bg);
    }

    tbody tr.asset-row:nth-child(even):hover {
        background: var(--hover-bg) !important;
    }

    td {
        border: none;
        padding: 12px 10px;
        font-size: 13px;
        vertical-align: middle;
        color: var(--text-primary);
    }

    /* Simple Price Indicators */
    .bid_price, .ask_price {
        font-weight: 600;
        font-family: 'SF Mono', 'Monaco', 'Cascadia Code', monospace;
        color: var(--text-primary);
    }

    .price-up {
        color: var(--text-primary);
        background: var(--hover-bg);
    }

    .price-down {
        color: var(--text-secondary);
        background: var(--secondary-bg);
    }

    @keyframes fadeInOut {
        0%, 100% { opacity: 0; }
        50% { opacity: 1; }
    }

    @keyframes priceFlashGreen {
        0% { background: var(--hover-bg); }
        100% { background: transparent; }
    }

    @keyframes priceFlashRed {
        0% { background: var(--secondary-bg); }
        100% { background: transparent; }
    }

    /* Clean Cards */
    .card.card-body {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-soft);
        margin-bottom: 0;
        padding: 16px;
        animation: cardSlideIn 0.3s ease-out;
    }

    @keyframes cardSlideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Clean Buttons */
    .btn {
        border-radius: var(--border-radius) !important;
        font-size: 12px;
        font-weight: 500;
        padding: 8px 16px;
        border: 1px solid var(--border-color);
        transition: all var(--animation-speed) ease;
        text-transform: none;
        letter-spacing: normal;
    }

    .btn-success {
        background: var(--text-primary);
        color: var(--card-bg);
        border-color: var(--text-primary);
    }

    .btn-success:hover {
        background: var(--text-secondary);
        border-color: var(--text-secondary);
        color: var(--card-bg);
    }

    .btn-danger {
        background: var(--card-bg);
        color: var(--text-primary);
        border-color: var(--text-primary);
    }

    .btn-danger:hover {
        background: var(--text-primary);
        color: var(--card-bg);
        border-color: var(--text-primary);
    }

    .btn-primary {
        background: var(--secondary-bg);
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .btn-primary:hover {
        background: var(--hover-bg);
        color: var(--text-primary);
        border-color: var(--text-secondary);
    }

    /* Clean Form Controls */
    .form-control, .form-select {
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-primary);
        font-size: 14px;
        padding: 10px 12px;
        transition: all var(--animation-speed) ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.1);
        background: var(--card-bg);
        outline: none;
    }

    .search {
        margin-bottom: 16px;
    }

    .search::placeholder {
        color: var(--text-muted);
    }

    /* Simple Loading States */
    .loading {
        position: relative;
        opacity: 0.7;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(26, 26, 26, 0.1), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Performance optimizations */
    .asset-row {
        will-change: transform;
    }

    .nav-link {
        will-change: transform, background;
    }

    /* Market Status Bar */
    .market-status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--secondary-bg);
        border-radius: var(--border-radius);
        padding: 12px 16px;
        margin-bottom: 16px;
        border: 1px solid var(--border-color);
    }

    .market-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--text-primary);
    }

    .status-indicator.live {
        background: var(--text-primary);
    }

    .status-indicator.closed {
        background: var(--text-muted);
    }

    .market-time {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--text-secondary);
    }

    /* Asset Row Dropdown */
    .asset-dropdown {
        display: none;
        background: var(--secondary-bg);
        border: 1px solid var(--border-color);
        border-top: none;
        padding: 12px;
        margin: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        animation: slideDown var(--animation-speed) ease;
    }

    .asset-details {
        display: none;
    }

    .asset-details.show {
        display: table-row !important;
        animation: slideDown 0.3s ease-out;
    }

    .asset-details.show .asset-dropdown {
        display: block;
    }

    .asset-dropdown-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-start;
        align-items: center;
    }

    .dropdown-btn {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 6px 12px;
        border-radius: var(--border-radius);
        cursor: pointer;
        font-size: 0.85em;
        transition: all var(--animation-speed) ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        min-width: fit-content;
        white-space: nowrap;
    }

    .dropdown-btn:hover {
        background: var(--hover-bg);
        border-color: var(--text-muted);
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: var(--text-primary);
        text-decoration: none;
    }

    .dropdown-btn i {
        font-size: 0.9em;
    }

    /* Row Hover Effect */
    .asset-row {
        cursor: pointer;
        transition: all var(--animation-speed) ease;
    }

    .asset-row:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .asset-row.active {
        background-color: rgba(0, 0, 0, 0.03);
        border-left: 3px solid var(--text-primary);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
        to {
            opacity: 1;
            max-height: 200px;
            padding-top: 12px;
            padding-bottom: 12px;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container.p-0 {
            margin: 10px 5px;
            padding: 16px 12px;
            border-radius: var(--border-radius);
        }

        .nav-tabs .nav-link {
            font-size: 12px;
            padding: 8px 12px;
        }

        thead th {
            font-size: 11px;
            padding: 10px 8px;
        }

        td {
            font-size: 12px;
            padding: 8px 6px;
        }

        .btn {
            font-size: 11px;
            padding: 6px 12px;
        }

        .card.card-body {
            padding: 12px 8px;
        }

        .table-responsive {
            max-height: 60vh;
        }

        .market-status-bar {
            flex-direction: column;
            gap: 8px;
            text-align: center;
            padding: 10px 12px;
        }
    }

    @media (max-width: 480px) {
        .nav-tabs {
            gap: 2px;
            margin-bottom: 12px;
            padding: 2px;
        }

        .nav-tabs .nav-link {
            font-size: 11px;
            padding: 6px 8px;
            min-width: auto;
        }

        .container.p-0 {
            padding: 12px 8px;
        }

        thead th {
            font-size: 10px;
            padding: 8px 4px;
        }

        td {
            font-size: 11px;
            padding: 6px 4px;
        }
    }

    /* Accessibility */
    .nav-link:focus,
    .btn:focus,
    .form-control:focus {
        outline: 2px solid var(--text-primary);
        outline-offset: 2px;
    }

    /* RTL Support */
    .rtl {
        direction: rtl;
    }

    /* Print Styles */
    @media print {
        body {
            background: white !important;
        }
        .container.p-0 {
            box-shadow: none !important;
            background: white !important;
            border: 1px solid #ccc !important;
        }
    }
</style>

@section('content')
<div class="container p-0">
    <!-- Market Status Indicator -->
    <div class="market-status-bar">
        <div class="market-status">
            <div class="status-indicator live"></div>
            <span>{{__('web.market_live')}}</span>
        </div>
        <div class="market-time" id="marketTime">
            <i class="fas fa-clock"></i>
            <span id="currentTime"></span>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3 w-100" id="quotesTabs" role="tablist" style="display: flex; justify-content: space-between;">
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') active @endif" id="fav-tab" data-bs-toggle="tab" data-bs-target="#fav" type="button" role="tab" aria-controls="fav" aria-selected="true"><i class="fas fa-star me-1"></i>{{__('web.favorites')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') active @endif" id="forex-tab" data-bs-toggle="tab" data-bs-target="#forex" type="button" role="tab" aria-controls="forex" aria-selected="false"><i class="fas fa-exchange-alt me-1"></i>{{__('web.forex')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') active @endif" id="cfd-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="false"><i class="fab fa-bitcoin me-1"></i>{{__('web.crypto')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') active @endif" id="ai-tab" data-bs-toggle="tab" data-bs-target="#stocks" type="button" role="tab" aria-controls="stocks" aria-selected="false"><i class="fas fa-chart-line me-1"></i>{{__('web.stocks')}}</button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') active @endif" id="indices-tab" data-bs-toggle="tab" data-bs-target="#indices" type="button" role="tab" aria-controls="indices" aria-selected="false"><i class="fas fa-chart-area me-1"></i>{{__('web.indices')}}</button>
        </li>
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') active @endif" id="commodity-tab" data-bs-toggle="tab" data-bs-target="#commodity" type="button" role="tab" aria-controls="commodity" aria-selected="false"><i class="fas fa-seedling me-1"></i>{{__('web.commodity')}}</button>
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
                                <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                    <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                        <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'fav'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'fav')">
                                            <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <span class="name">
                                            {{ $asset->name }}
                                        </span>
                                    </td>
                                    <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                    <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                                </tr>
                                <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                    <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                        <div class="asset-dropdown">
                                            <div class="asset-dropdown-buttons">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                    <i class="fas fa-chart-line"></i>
                                                    {{__('web.new_chart')}}
                                                </a>
                                                @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                    <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'fav')">
                                                        <i class="fas fa-plus"></i>
                                                        {{__('web.new_order')}}
                                                    </button>
                                                    <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'fav')">
                                                        <i class="fas fa-clock"></i>
                                                        {{__('web.new_pending_order')}}
                                                    </button>
                                                @endif
                                                <button type="button" class="dropdown-btn" onclick="showAssetDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                    <i class="fas fa-info-circle"></i>
                                                    {{__('web.details')}}
                                                </button>
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
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'forex'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'forex')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'forex')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'forex')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showForexDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
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
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'crypto'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'crypto')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'crypto')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'crypto')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showCryptoDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
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
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'stocks'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'stocks')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'stocks')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'stocks')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showStocksDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
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
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'indices'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'indices')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'indices')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'indices')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showIndicesDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
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
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'commodity'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'commodity')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'commodity')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'commodity')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showCommodityDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
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
    var client_id = {{auth()->guard('client')->user()->id}};
    var assetId = 1;

    // Enhanced dynamic functionality
    $(document).ready(function () {
        // Initialize real-time clock
        updateMarketTime();
        setInterval(updateMarketTime, 1000);

        // Initialize loading states
        showLoadingStates();

        // Enhanced search with debouncing and visual feedback
        let searchTimeout;
        $(".search").on("input", function () {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val().trim().toLowerCase();
            const tabContainer = $(this).closest('.tab-pane');
            const searchInput = $(this);
            
            // Add loading state to search
            searchInput.addClass('loading');
            
            searchTimeout = setTimeout(() => {
                filterAssets(tabContainer, searchTerm);
                searchInput.removeClass('loading');
                
                // Show results count
                const visibleRows = tabContainer.find('.asset-row:visible').length;
                showSearchResults(visibleRows, searchTerm);
            }, 300);
        });

        // Enhanced asset row interactions with click effects
        $('.asset-row').hover(
            function() {
                $(this).addClass('loading').delay(200).queue(function(next) {
                    $(this).removeClass('loading');
                    next();
                });
            }
        );

        // Initialize asset row dropdown functionality
        initializeAssetRowClicks();

        // Enhanced price change animations with sound effect simulation
        function animatePriceChange(element, isIncrease) {
            element.addClass(isIncrease ? 'price-up' : 'price-down');
            
            // Add particle effect
            createPriceParticle(element, isIncrease);
            
            setTimeout(() => {
                element.removeClass('price-up price-down');
            }, 1000);
        }

        // Create price change particle effect
        function createPriceParticle(element, isIncrease) {
            const particle = $('<div class="price-particle"></div>');
            const rect = element[0].getBoundingClientRect();
            
            particle.css({
                position: 'fixed',
                left: rect.left + rect.width / 2,
                top: rect.top,
                width: '4px',
                height: '4px',
                background: isIncrease ? '#10b981' : '#ef4444',
                borderRadius: '50%',
                pointerEvents: 'none',
                zIndex: 9999,
                animation: `priceParticle${isIncrease ? 'Up' : 'Down'} 1s ease-out forwards`
            });
            
            $('body').append(particle);
            setTimeout(() => particle.remove(), 1000);
        }

        // Simulate real-time price updates (replace with actual WebSocket)
        function simulatePriceUpdates() {
            $('.bid_price, .ask_price').each(function() {
                if (Math.random() > 0.7) { // 30% chance of price change
                    const currentPrice = parseFloat($(this).text());
                    const change = (Math.random() - 0.5) * 0.001 * currentPrice;
                    const newPrice = (currentPrice + change).toFixed(5);
                    
                    $(this).text(newPrice);
                    animatePriceChange($(this), change > 0);
                }
            });
        }

        // Start price simulation (remove in production)
        // setInterval(simulatePriceUpdates, 3000);

        // Enhanced modal interactions
        $('#asset-select').on('change', function() {
            const selectedOption = $(this).find(':selected');
            const bidPrice = selectedOption.data('bid');
            const askPrice = selectedOption.data('ask');
            
            $('#bid').val(bidPrice);
            $('#ask').val(askPrice);
            $('#sell-price').text(bidPrice);
            $('#buy-price').text(askPrice);

            // Add selection animation
            $(this).addClass('loading').delay(300).queue(function(next) {
                $(this).removeClass('loading');
                next();
            });
        });

        // Toggle switches for stop loss and take profit
        $('#stopLossSwitch').on('change', function() {
            const container = $('#stopLossContainer');
            if (this.checked) {
                container.slideDown(300);
            } else {
                container.slideUp(300);
            }
        });

        $('#takeProfitSwitch').on('change', function() {
            const container = $('#takeProfitContainer');
            if (this.checked) {
                container.slideDown(300);
            } else {
                container.slideUp(300);
            }
        });

        $('#stopLossSwitchPending').on('change', function() {
            const container = $('#stopLossContainerPending');
            if (this.checked) {
                container.slideDown(300);
            } else {
                container.slideUp(300);
            }
        });

        $('#takeProfitSwitchPending').on('change', function() {
            const container = $('#takeProfitContainerPending');
            if (this.checked) {
                container.slideDown(300);
            } else {
                container.slideUp(300);
            }
        });

        // Enhanced order button interactions
        $('.new_order, .pending_order').on('click', function() {
            const assetId = this.getAttribute('data-asset');
            const tab = this.getAttribute('data-tab');

            // Add click animation
            $(this).css('transform', 'scale(0.95)');
            setTimeout(() => {
                $(this).css('transform', '');
            }, 150);

            if ($(this).hasClass('new_order')) {
                $('#newTab').val(tab);
                $('#asset-select').val(assetId).trigger('change');
            } else {
                $('#pendingTab').val(tab);
                $('#currency').val(assetId);
            }
        });

        // Enhanced star favorites
        $('.fa-star').on('click', function(e) {
            e.preventDefault();
            const star = $(this);
            
            // Add click animation
            star.css('transform', 'scale(1.5) rotate(360deg)');
            setTimeout(() => {
                star.css('transform', '');
            }, 300);

            // Simulate toggle (replace with actual AJAX call)
            if (star.hasClass('text-warning')) {
                star.removeClass('text-warning').addClass('text-secondary');
            } else {
                star.removeClass('text-secondary').addClass('text-warning');
            }
        });

        // Tab switching enhancements
        $('.nav-link').on('click', function() {
            // Add loading state to target tab content
            const targetId = $(this).attr('data-bs-target');
            $(targetId).addClass('loading').delay(500).queue(function(next) {
                $(this).removeClass('loading');
                next();
            });
        });

        // Form validation enhancements
        $('form').on('submit', function(e) {
            const submitBtn = $(this).find('button[type="submit"]:focus');
            submitBtn.addClass('loading');
            
            // Basic validation
            const amount = $(this).find('input[name="amount"]').val();
            if (!amount || parseFloat(amount) < 0.01) {
                e.preventDefault();
                alert('Please enter a valid amount (minimum 0.01)');
                submitBtn.removeClass('loading');
                return false;
            }
        });

        // Initialize tooltips (if Bootstrap tooltips are available)
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });
        }
    });

    // Utility functions
    function filterAssets(tabContainer, searchTerm) {
        tabContainer.find('.asset-row').each(function () {
            const assetName = $(this).find(".name").text().trim().toLowerCase();
            if (assetName.includes(searchTerm)) {
                $(this).show().addClass('filtered-in');
            } else {
                $(this).hide().removeClass('filtered-in');
            }
        });
    }

    function showLoadingStates() {
        // Add initial loading animation to tables
        $('.table-responsive').addClass('loading').delay(1000).queue(function(next) {
            $(this).removeClass('loading');
            next();
        });
    }

    function updateMarketTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', {
            hour12: true,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        $('#currentTime').text(timeString);
        
        // Update market status based on time (simplified logic)
        const hour = now.getHours();
        const isWeekend = now.getDay() === 0 || now.getDay() === 6;
        const isMarketOpen = !isWeekend && hour >= 9 && hour < 17;
        
        const statusIndicator = $('.status-indicator');
        const statusText = $('.market-status span');
        
        if (isMarketOpen) {
            statusIndicator.removeClass('closed').addClass('live');
            statusText.text('{{__('web.market_open')}}');
        } else {
            statusIndicator.removeClass('live').addClass('closed');
            statusText.text('{{__('web.market_closed')}}');
        }
    }

    function showSearchResults(count, term) {
        // Remove existing notification
        $('.search-results').remove();
        
        if (term && count >= 0) {
            const notification = $(`
                <div class="search-results" style="
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: rgba(255,255,255,0.9);
                    backdrop-filter: blur(10px);
                    border-radius: 8px;
                    padding: 8px 12px;
                    font-size: 12px;
                    color: var(--text-secondary);
                    z-index: 10;
                    animation: slideDown 0.3s ease;
                ">
                    ${count} {{__('web.results_found')}} for "${term}"
                </div>
            `);
            
            $('.search').first().parent().css('position', 'relative').append(notification);
            
            // Auto-remove after 3 seconds
            setTimeout(() => notification.fadeOut(), 3000);
        }
    }

    // WebSocket connection (placeholder for real implementation)
    function initializeWebSocket() {
        // Replace with actual WebSocket implementation
        console.log('WebSocket initialization placeholder');
        // const ws = new WebSocket('wss://your-websocket-endpoint');
        // ws.onmessage = function(event) {
        //     const data = JSON.parse(event.data);
        //     updatePrices(data);
        // };
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            $('.modal.show').modal('hide');
        }
        
        // Quick search focus (Ctrl/Cmd + K)
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            $('.search:visible').first().focus();
        }
    });

    // Asset row dropdown functionality
    function initializeAssetRowClicks() {
        // Remove any existing event listeners
        document.querySelectorAll('.asset-row').forEach(row => {
            row.replaceWith(row.cloneNode(true));
        });

        document.querySelectorAll('.asset-row').forEach(row => {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on star icon or other interactive elements
                if (e.target.closest('.fa-star') || e.target.closest('a[href*="toggle.favourite"]')) {
                    return;
                }
                
                e.preventDefault();
                e.stopPropagation();
                
                // Get asset ID from data attribute
                const assetId = this.dataset.assetId;
                
                if (!assetId) return;
                
                // Close any other open dropdowns/details
                document.querySelectorAll('.asset-details').forEach(dropdown => {
                    if (dropdown.id !== `assetDetails${assetId}`) {
                        dropdown.classList.remove('show');
                        dropdown.style.display = 'none';
                        
                        // Remove active class from corresponding row
                        const correspondingRow = dropdown.previousElementSibling;
                        if (correspondingRow && correspondingRow.classList.contains('asset-row')) {
                            correspondingRow.classList.remove('active');
                        }
                    }
                });
                
                // Find and toggle current dropdown
                let dropdown = document.getElementById(`assetDetails${assetId}`);
                
                if (dropdown) {
                    const isVisible = dropdown.classList.contains('show');
                    
                    if (isVisible) {
                        dropdown.classList.remove('show');
                        dropdown.style.display = 'none';
                        this.classList.remove('active');
                    } else {
                        dropdown.classList.add('show');
                        dropdown.style.display = 'table-row';
                        this.classList.add('active');
                    }
                }
            });
        });
    }

    // Toggle favorite function with gold star
    function toggleFavorite(event, assetId, tab = 'fav') {
        event.stopPropagation(); // Prevent row click
        
        const starIcon = event.target;
        
        // Add loading state animation
        starIcon.classList.add('fa-spin');
        
        // Navigate to toggle route
        setTimeout(() => {
            window.location.href = `/toggle-favourite/${assetId}?tab=${tab}`;
        }, 300);
    }

    // Open new order modal
    function openNewOrderModal(assetId, tab) {
        const modal = document.getElementById('newOrderModal');
        if (modal) {
            // Set asset data
            const assetSelect = modal.querySelector('#asset-select');
            if (assetSelect) {
                assetSelect.value = assetId;
                $(assetSelect).trigger('change');
            }
            
            // Store tab info
            modal.querySelector('.new_order')?.setAttribute('data-asset', assetId);
            modal.querySelector('.new_order')?.setAttribute('data-tab', tab);
            
            // Show modal
            $(modal).modal('show');
        }
    }

    // Open pending order modal
    function openPendingOrderModal(assetId, tab) {
        const modal = document.getElementById('newPendingOrderModal');
        if (modal) {
            // Set asset data
            const assetSelect = modal.querySelector('#asset-select-pending');
            if (assetSelect) {
                assetSelect.value = assetId;
                $(assetSelect).trigger('change');
            }
            
            // Store tab info
            modal.querySelector('.pending_order')?.setAttribute('data-asset', assetId);
            modal.querySelector('.pending_order')?.setAttribute('data-tab', tab);
            
            // Show modal
            $(modal).modal('show');
        }
    }

    // Show trading hours function
    function showTradingHours(symbol) {
        // Determine which modal to show based on symbol type
        let modalId = 'tradeHoursModal'; // Default to forex
        
        if (symbol.includes('BTC') || symbol.includes('ETH') || symbol.includes('LTC')) {
            modalId = 'CryptoHoursModal';
        } else if (symbol.includes('USD') || symbol.includes('EUR') || symbol.includes('GBP')) {
            modalId = 'tradeHoursModal';
        } else if (symbol.includes('AAPL') || symbol.includes('GOOGL') || symbol.includes('MSFT')) {
            modalId = 'StocksHoursModal';
        } else if (symbol.includes('DOW') || symbol.includes('NASDAQ') || symbol.includes('SP500')) {
            modalId = 'IndicesHoursModal';
        } else if (symbol.includes('GOLD') || symbol.includes('OIL') || symbol.includes('SILVER')) {
            modalId = 'CommodityHoursModal';
        }
        
        const modal = document.getElementById(modalId);
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Show asset details function for favorites
    function showAssetDetails(symbol, assetId) {
        showTradingHours(symbol);
    }

    // Show forex trading hours
    function showForexDetails(symbol, assetId) {
        const modal = document.getElementById('tradeHoursModal');
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Show crypto trading hours
    function showCryptoDetails(symbol, assetId) {
        const modal = document.getElementById('CryptoHoursModal');
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Show stocks trading hours
    function showStocksDetails(symbol, assetId) {
        const modal = document.getElementById('StocksHoursModal');
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Show indices trading hours
    function showIndicesDetails(symbol, assetId) {
        const modal = document.getElementById('IndicesHoursModal');
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Show commodity trading hours
    function showCommodityDetails(symbol, assetId) {
        const modal = document.getElementById('CommodityHoursModal');
        if (modal) {
            $(modal).modal('show');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeAssetRowClicks();
        
        // Re-initialize after any dynamic content updates
        if (typeof MutationObserver !== 'undefined') {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        initializeAssetRowClicks();
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    });

    // Intersection Observer for performance optimization
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        });

        // Observe asset rows for lazy loading effects
        document.querySelectorAll('.asset-row').forEach(row => {
            observer.observe(row);
        });
    }
</script>

@endsection
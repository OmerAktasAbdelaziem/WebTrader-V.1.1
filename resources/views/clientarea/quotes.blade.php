@extends('layouts.mobile')
<style>
    /* Clean Monochrome Trading Platform */
    :root {
        --bg-primary: #fafafa;
        --bg-secondary: #f5f5f5;
        --bg-card: #ffffff;
        --bg-accent: #f0f0f0;
        --border-light: #e5e5e5;
        --border-medium: #cccccc;
        --border-dark: #999999;
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        --hover-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        --text-primary: #1a1a1a;
        --text-secondary: #404040;
        --text-muted: #666666;
        --text-light: #888888;
        --accent-primary: #333333;
        --accent-secondary: #555555;
        --accent-dark: #1a1a1a;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--bg-primary);
        font-family: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        font-weight: 400;
        line-height: 1.5;
    }

    .container.p-0 {
        margin: 20px auto;
        max-width: 1400px;
        border-radius: 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
        padding: 24px;
    }

    /* Market Status Bar */
    .market-status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-secondary);
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border: 1px solid var(--border-light);
    }

    .market-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--text-primary);
    }

    .market-time {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 400;
    }

    /* Navigation Tabs */
    .nav-tabs {
        border: none;
        background: var(--bg-secondary);
        border-radius: 8px;
        padding: 4px;
        margin-bottom: 20px;
        display: flex;
        gap: 2px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .nav-tabs::-webkit-scrollbar {
        display: none;
    }

    .nav-tabs .nav-link {
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 500;
        background: transparent;
        color: var(--text-muted);
        border: none;
        border-radius: 6px;
        margin: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-tabs .nav-link i {
        font-size: 12px;
        margin-right: 6px;
    }

    .nav-tabs .nav-link.active {
        background: var(--bg-card);
        color: var(--text-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        background: var(--bg-accent);
        color: var(--text-secondary);
    }

    .nav-item {
        flex: 1;
        min-width: 0;
    }

    /* Search Bar */
    .search {
        margin-bottom: 20px;
        position: relative;
        border-radius: 8px;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .search:focus-within {
        border-color: var(--border-medium);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .search::before {
        content: '🔍';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        z-index: 2;
        color: var(--text-muted);
    }

    .search input {
        padding: 12px 12px 12px 40px;
        border: none;
        background: transparent;
        font-size: 14px;
        font-weight: 400;
        width: 100%;
        outline: none;
        color: var(--text-primary);
    }

    .search input::placeholder {
        color: var(--text-muted);
        font-weight: 400;
    }

    /* Star Icons */
    .star-icon, .fa-star {
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .fa-star.text-dark, .star-icon.favorited {
        color: var(--text-primary) !important;
    }

    .fa-star.text-secondary {
        color: var(--text-muted) !important;
    }

    .fa-star:hover {
        color: var(--text-secondary) !important;
    }

    /* Clean Table Design */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
    }

    table.table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: transparent;
    }

    thead th {
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 600;
        padding: 16px 12px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        border-bottom: 1px solid var(--border-light);
    }

    .table-header {
        position: sticky;
        top: 0;
        background: var(--bg-secondary);
        z-index: 10;
    }

    th:first-child {
        text-align: left !important;
        padding-left: 20px;
    }

    th, td {
        border: none;
        padding: 14px 12px;
        font-size: 14px;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    td:first-child {
        padding-left: 20px;
    }

    /* Asset Rows */
    tr.asset-row {
        transition: all 0.2s ease;
        cursor: pointer;
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-light);
    }

    tr.asset-row:hover {
        background: var(--bg-secondary);
    }

    tr.asset-row:nth-child(even) {
        background: var(--bg-accent);
    }

    tr.asset-row:nth-child(even):hover {
        background: var(--bg-secondary);
    }

    tr.asset-row td {
        font-weight: 500;
        text-align: center;
    }

    tr.asset-row td:first-child {
        text-align: left;
    }

    .asset-row .name {
        font-weight: 600;
        color: var(--text-primary);
        margin-left: 8px;
        display: inline-block;
    }

    .asset-row:hover .name {
        color: var(--text-secondary);
    }

    /* Price Styling */
    .bid_price, .ask_price {
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.5px;
        color: var(--text-primary);
        background: var(--bg-secondary);
        border-radius: 4px;
        padding: 8px 12px;
        margin: 2px;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .bid_price:hover, .ask_price:hover {
        background: var(--bg-accent);
        border-color: var(--border-medium);
    }

    /* Asset Details */
    tr.collapse.asset-details > td {
        background: var(--bg-secondary) !important;
        border-top: 1px solid var(--border-light);
        padding: 0;
    }

    .card.card-body {
        background: var(--bg-card);
        border-radius: 8px;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin: 12px;
    }

    /* Asset Info Grid */
    .asset-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .info-item {
        background: var(--bg-secondary);
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: var(--bg-accent);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-item .label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-item .value {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
    }

    /* Action Buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-top: 16px;
    }

    /* Buttons */
    .btn {
        border-radius: 6px !important;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 16px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-success {
        background: var(--text-primary);
        color: var(--bg-card);
        border-color: var(--text-primary);
    }

    .btn-success:hover {
        background: var(--text-secondary);
        border-color: var(--text-secondary);
        color: var(--bg-card);
    }

    .btn-danger {
        background: var(--bg-card);
        color: var(--text-primary);
        border-color: var(--border-medium);
    }

    .btn-danger:hover {
        background: var(--bg-secondary);
        border-color: var(--border-dark);
        color: var(--text-primary);
    }

    .btn-primary {
        background: var(--text-secondary);
        color: var(--bg-card);
        border-color: var(--text-secondary);
    }

    .btn-primary:hover {
        background: var(--text-primary);
        border-color: var(--text-primary);
        color: var(--bg-card);
    }

    /* Enhanced Form Controls */
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid var(--border-medium);
        background: var(--bg-card);
        font-size: 14px;
        font-weight: 400;
        padding: 12px 16px;
        transition: all 0.3s ease;
        color: var(--text-primary);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        background: var(--bg-card);
    }

    /* Modern Modal Design */
    .modal {
        backdrop-filter: blur(4px);
    }

    .modal-dialog {
        margin: 2rem auto;
        max-width: 90%;
    }

    .modal-content {
        border-radius: 8px;
        border: 1px solid var(--border-light);
        background: var(--bg-card);
        box-shadow: var(--hover-shadow);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 20px 24px 16px;
        background: var(--bg-secondary);
    }

    .modal-title {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 18px;
    }

    .modal-body {
        padding: 24px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Enhanced Form Labels */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-size: 14px;
    }

    /* Switch Toggle Styling */
    .form-check-input {
        width: 44px;
        height: 22px;
        border-radius: 11px;
        background-color: var(--border-medium);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background: var(--text-primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    /* Table in Modals */
    .modal table {
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid var(--border-light);
    }

    .modal table th {
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-weight: 600;
        text-align: center;
        padding: 16px;
    }

    .modal table td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-light);
        text-align: center;
    }

    .modal table tbody tr:hover {
        background: var(--bg-primary);
    }

    /* Enhanced Select Dropdown */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px 12px;
    }

    .form-select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    }

    /* Scrollbar Styling */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: var(--bg-secondary);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }

    /* Responsive Design - Enhanced */
    @media (max-width: 768px) {
        .container.p-0 {
            margin: 16px 8px;
            padding: 24px 16px;
            border-radius: 16px;
        }

        .nav-tabs .nav-link {
            padding: 14px 10px;
            font-size: 12px;
            flex-direction: column;
            gap: 6px;
        }

        .nav-tabs .nav-link i {
            font-size: 14px;
        }

        th, td {
            padding: 16px 10px;
            font-size: 13px;
        }

        .card.card-body {
            padding: 20px;
            margin: 12px;
        }

        .btn {
            padding: 12px 18px;
            font-size: 13px;
        }

        .asset-info-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .action-buttons {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }

        .info-item {
            padding: 14px;
        }

        .info-item .label {
            font-size: 11px;
        }

        .info-item .value {
            font-size: 15px;
        }

        .market-status-bar {
            padding: 14px 16px;
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }

        .dropdown-btn {
            padding: 8px 12px;
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .container.p-0 {
            margin: 8px 4px;
            padding: 20px 12px;
        }

        .nav-tabs {
            padding: 4px;
        }

        .nav-tabs .nav-link {
            padding: 12px 8px;
            font-size: 10px;
            flex-direction: column;
            gap: 4px;
        }

        .nav-tabs .nav-link i {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
            font-size: 12px;
        }

        .search input {
            padding: 16px 16px 16px 44px;
            font-size: 14px;
        }

        .search::before {
            left: 14px;
            font-size: 14px;
        }

        .asset-info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 16px;
        }

        .action-buttons {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .info-item {
            padding: 12px;
        }

        .info-item .label {
            font-size: 10px;
        }

        .info-item .value {
            font-size: 14px;
        }

        .action-buttons .btn {
            padding: 12px 16px;
            font-size: 12px;
        }

        .market-status-bar {
            padding: 12px;
        }

        .market-status {
            font-size: 13px;
        }

        .market-time {
            font-size: 12px;
        }

        .dropdown-btn {
            padding: 6px 10px;
            font-size: 10px;
            gap: 4px;
        }

        .asset-dropdown {
            padding: 12px;
        }

        .asset-dropdown-buttons {
            gap: 8px;
        }
    }

    /* Accessibility improvements */
    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        outline: 2px solid var(--text-secondary);
        outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }

    /* Loading Animation */
    .loading {
        opacity: 0.6;
    }

    /* Modal Design */
    .modal {
        backdrop-filter: blur(4px);
    }

    .modal-dialog {
        margin: 2rem auto;
        max-width: 90%;
    }

    .modal-content {
        border-radius: 8px;
        border: 1px solid var(--border-light);
        background: var(--bg-card);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 20px 24px 16px;
        background: var(--bg-secondary);
    }

    .modal-title {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 18px;
    }

    .modal-body {
        padding: 24px;
        background: var(--bg-card);
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        opacity: 0.7;
        transition: all 0.2s ease;
        color: var(--text-secondary);
    }

    .btn-close:hover {
        opacity: 1;
        color: var(--text-primary);
    }

    /* Asset Dropdown Buttons */
    .asset-dropdown {
        padding: 16px;
        background: var(--bg-secondary);
        border-radius: 8px;
    }

    .asset-dropdown-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }

    .dropdown-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: var(--bg-card);
        color: var(--text-primary);
        text-decoration: none;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .dropdown-btn:hover {
        background: var(--text-primary);
        color: var(--bg-card);
        border-color: var(--text-primary);
    }

    .dropdown-btn i {
        font-size: 11px;
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
            <button class="nav-link w-100 @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') active @endif" id="fav-tab" data-bs-toggle="tab" data-bs-target="#fav" type="button" role="tab" aria-controls="fav" aria-selected="true">
                <i class="fas fa-star me-2"></i>{{__('web.favorites')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') active @endif" id="forex-tab" data-bs-toggle="tab" data-bs-target="#forex" type="button" role="tab" aria-controls="forex" aria-selected="false">
                <i class="fas fa-exchange-alt me-2"></i>{{__('web.forex')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') active @endif" id="cfd-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="false">
                <i class="fab fa-bitcoin me-2"></i>{{__('web.crypto')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') active @endif" id="ai-tab" data-bs-toggle="tab" data-bs-target="#stocks" type="button" role="tab" aria-controls="stocks" aria-selected="false">
                <i class="fas fa-chart-line me-2"></i>{{__('web.stocks')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') active @endif" id="indices-tab" data-bs-toggle="tab" data-bs-target="#indices" type="button" role="tab" aria-controls="indices" aria-selected="false">
                <i class="fas fa-chart-bar me-2"></i>{{__('web.indices')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') active @endif" id="commodity-tab" data-bs-toggle="tab" data-bs-target="#commodity" type="button" role="tab" aria-controls="commodity" aria-selected="false">
                <i class="fas fa-seedling me-2"></i>{{__('web.commodity')}}
            </button>
        </li>
    </ul>
    <div class="tab-content" id="quotesTabsContent">
        <div class="tab-pane fade @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') show active @endif" id="fav" role="tabpanel" aria-labelledby="fav-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_fav_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="favAssets">
                        @foreach($favourite_assets as $index => $asset)
                            @if(is_object($asset))
                                <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                    <td style="text-align: left;">
                                        <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'fav'])}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                        </a>
                                        <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">
                                            {{ $asset->name }}
                                        </span>
                                    </td>
                                    <td class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                    <td class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetails{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                                </tr>
                                <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
                                    <td colspan="3">
                                        <div class="card card-body">
                                            <div class="asset-info-grid">
                                                <div class="info-item">
                                                    <span class="label">{{__('web.symbol')}}</span>
                                                    <span class="value">{{ $asset->name }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">{{__('web.type')}}</span>
                                                    <span class="value">{{ $asset->type }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">{{__('web.contract_size')}}</span>
                                                    <span class="value">{{ $asset->size[$asset_group_id] }}</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">{{__('web.leverage')}}</span>
                                                    <span class="value">{{ $asset->leverage[$asset_group_id] }}</span>
                                                </div>
                                            </div>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <div class="action-buttons">
                                                    <button class="btn btn-success new_order" data-asset="{{$asset->id}}" data-tab="fav" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                                        <i class="fas fa-plus"></i>{{__('web.new_order')}}
                                                    </button>
                                                    <button class="btn btn-danger pending_order" data-asset="{{$asset->id}}" data-tab="fav" data-bs-toggle="modal" data-bs-target="#newPendingOrderModal">
                                                        <i class="fas fa-clock"></i>{{__('web.new_pending_order')}}
                                                    </button>
                                                    <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-primary">
                                                        <i class="fas fa-chart-area"></i>{{__('web.new_chart')}}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="action-buttons">
                                                    <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="btn btn-primary">
                                                        <i class="fas fa-chart-area"></i>{{__('web.new_chart')}}
                                                    </a>
                                                </div>
                                            @endif
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
                    <thead class="table-header">
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="forexAssets">
                        @foreach($forexAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'forex'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'forex')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
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
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="cryptoAssets">
                        @foreach($cryptoAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'crypto'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'crypto')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
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
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="stocksAssets">
                        @foreach($stocksAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'stocks'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'stocks')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
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
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="indicesAssets">
                        @foreach($indicesAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'indices'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'indices')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
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
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="commodityAssets">
                        @foreach($commodityAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'commodity'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'commodity')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetails{{ $asset->id }}" class="collapse asset-details">
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Qtrade - Trading Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ url('css/webtrader.css') }}" rel="stylesheet" />
    <link href="{{ url('css/webtrader2.css') }}" rel="stylesheet" />

</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <i class="bi bi-bell nav-icon notification-icon" title="Notifications" style="font-size:1.2rem; padding:0.3rem; position: relative;">
        @if(!empty($notifications) && count($notifications) > 0)
            <span class="notification-badge">{{ count($notifications) }}</span>
        @endif
    </i>
    <i class="bi bi-chat-dots nav-icon chat-icon" title="Live Chat" style="font-size:1.2rem; padding:0.3rem;"></i>

    <div style="height: 20px; border-bottom: 2px solid #4f8cff; margin: 0 10px 20px 10px; opacity: 0.7;"></div>

    <i class="bi bi-bar-chart nav-icon markets-icon active" title="Markets" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-person nav-icon account-icon" title="Account" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-arrow-up-circle nav-icon deposit-icon" title="Deposit" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-arrow-down-circle nav-icon withdrawal-icon" title="Withdrawal" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-box-arrow-right nav-icon logout-icon" title="Logout" style="font-size:1.2rem; padding:0.3rem;"></i>
</div>

<!-- Custom Context Menu -->
<div id="customContextMenu" class="shadow-lg p-2">
    <button id="goToAssetBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-arrow-right-circle fs-5"></i>
        <span>Go to Asset</span>
    </button>
    <button id="addToFavouriteBtn" data-asset-id="{{ $asset && $asset->id ? $asset->id : '' }}" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star fs-5 text-primary"></i>
        <span>Add to Favourites</span>
    </button>
    <button id="removeFromFavouriteBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star-fill fs-5 text-warning"></i>
        <span>Remove from Favourites</span>
    </button>
</div>

<!-- Main Trading Interface -->
<div id="mainContent" class="main-content">
    <div class="row g-3 align-items-start">

        <!-- Chart & Tabs -->
        <div class="col-lg-8">
            <div class="panel">
                <!-- TradingView Widget -->
                <div class="tv-widget-container">
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                        "autosize": true,
                        "symbol": "{{ $symbol ?? 'XAUUSD' }}",
                        "interval": "5",
                        "timezone": "Etc/UTC",
                        "theme": "dark",
                        "style": "1",
                        "locale": "en",
                        "allow_symbol_change": false,
                        "support_host": "https://www.tradingview.com"
                    }
                    </script>
                </div>
            </div>
        </div>

        <!-- Right Side Panel -->
        <div class="col-lg-4">
            <div class="right-side-panel">
                <!-- Asset Search & Filters -->
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <div class="flex-grow-1">
                        <input type="text" id="assetSearch" class="searchbar form-control-sm w-100" placeholder="Search symbols..." style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem;">
                    </div>
                    <div>
                        <select id="categoryFilter" class="filtercategory form-select-sm" style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem; min-width: 100px;">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" id="showFavouritesBtn" class="btn btn-sm" title="Show Favourites" style="background:#23272f; color:#4f8cff; border:1px solid #353b48; padding: 0.5rem 0.75rem; border-radius: 6px;">
                            <i class="bi bi-star-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="assets d-grid gap-2" id="assetGrid">
                    <div class="row fw-bold text-secondary mb-2" style="font-size: 1rem;">
                        <div class="col-6">Market</div>
                        <div class="col-3 text-center">Bid</div>
                        <div class="col-3 text-center">Ask</div>
                    </div>
                    
                    {{-- Current Asset First --}}
                    @if($asset)
                        <button type="button" class="row align-items-center asset-button asset-item market-assets mb-2 current-asset"
                                data-asset-id="{{ $asset->id }}"
                                data-id="{{ $asset->id }}"
                                data-symbol="{{ $asset->symbol }}"
                                data-name="{{ $asset->name }}"
                                data-category="{{ $asset->category }}"
                                data-url="{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}"
                                onclick="window.location.href='{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}'"
                                oncontextmenu="showContextMenu(event, {{ $asset->id }})">
                            <div class="col-6 text-start">
                                <span class="name text-white fw-bold">
                                    {{ $asset->name }}
                                    @if (in_array($asset->id, $favourite_assets_ids))
                                        <span class="star-icon" style="color: gold; margin-left: 6px;">★</span>
                                    @endif
                                </span>
                            </div>

                            <div class="col-3 text-center">
                                <span class="bid_price text-danger" data-asset-id="{{$asset->id}}" class="bid_price" data-asset-id="{{$asset->id}}">
                                    {{$asset->bid_price}}
                                </span>
                                </span>
                            </div>

                            <div class="col-3 text-center">
                                <span class="ask_price text-success" data-asset-id="{{$asset->id}}">
                                    {{$asset->ask_price}} 
                                </span>
                                </span>
                            </div>
                        </button>
                    @endif
                    
                    {{-- Other Assets --}}
                    @foreach($assetsPrices as $assetPrice)
                        @if(!$asset || $assetPrice->id !== $asset->id)
                            <button type="button" class="row align-items-center asset-button asset-item market-assets mb-2"
                                    data-asset-id="{{ $assetPrice->id }}"
                                    data-id="{{ $assetPrice->id }}"
                                    data-symbol="{{ $assetPrice->symbol }}"
                                    data-name="{{ $assetPrice->name }}"
                                    data-category="{{ $assetPrice->category }}"
                                    data-url="{{ route('client.webtrader', ['symbol' => $assetPrice->symbol]) }}"
                                    onclick="window.location.href='{{ route('client.webtrader', ['symbol' => $assetPrice->symbol]) }}'"
                                    oncontextmenu="showContextMenu(event, {{ $assetPrice->id }})">
                                <div class="col-6 text-start">
                                    <span class="name text-white fw-bold">
                                        {{ $assetPrice->name }}
                                        @if (in_array($assetPrice->id, $favourite_assets_ids))
                                            <span class="star-icon" style="color: gold; margin-left: 6px;">★</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="col-3 text-center">
                                    <span class="bid_price text-danger" data-asset-id="{{ $assetPrice->id }}">
                                        {{ number_format($assetPrice->bid_price, 4) }}
                                    </span>
                                </div>

                                <div class="col-3 text-center">
                                    <span class="ask_price text-success" data-asset-id="{{ $assetPrice->id }}">
                                        {{ number_format($assetPrice->ask_price, 4) }}
                                    </span>
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
                
                <!-- Order Form -->
                <div class="mt-3">
                    <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="orderType" name="type" value="1">
                    <input type="hidden" id="selectedAssetId" name="currency" value="{{ $asset && $asset->id ? $asset->id : '' }}">
                    <input type="hidden" id="selectedAssetSymbol" name="asset_symbol" value="{{ $symbol ?? '' }}">
                    <input type="hidden" id="currentBidPrice" name="bid" value="{{ $asset && $asset->bid_price ? $asset->bid_price : '0' }}">
                    <input type="hidden" id="currentAskPrice" name="ask" value="{{ $asset && $asset->ask_price ? $asset->ask_price : '0' }}">
                    <input type="hidden" id="currentChartSymbol" value="{{ $symbol ?? '' }}">
                    

                    <!-- Amount and Trade Buttons -->
                    <div class="d-flex gap-2 mb-3 align-items-center justify-content-center">
                        <button type="button" id="sellBtn" class="btnorder btn-danger">
                            <span class="d-flex flex-column align-items-center">
                                <strong class="sellPrice" id="displayBidPrice">{{ $asset && $asset->bid_price ? number_format($asset->bid_price, 4) : '0.0000' }}</strong>
                                <span>{{ __('web.sell') }}</span>
                            </span>
                        </button>
                        <div class="input-group" style="max-width: 140px;">
                            <button type="button" class="btnminus" onclick="changeAmount(-0.01)">−</button>
                            <input type="number" id="amount" name="amount" min="0.01" step="0.01" value="0.01" class="amount text-center" readonly/>
                            <button type="button" class="btnplus" onclick="changeAmount(0.01)">+</button>
                        </div>
                        <button type="button" id="buyBtn" class="btnorder btn-success">
                            <span class="d-flex flex-column align-items-center">
                                <strong class="buyPrice" id="displayAskPrice">{{ $asset && $asset->ask_price ? number_format($asset->ask_price, 4) : '0.0000' }}</strong>
                                <span>{{ __('web.buy') }}</span>
                            </span>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tabs and Account Summary Row -->
        <div class="col-12">
            <div class="details-panel">
            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center">
                <ul class="nav nav-tabs border-0 mb-0" id="tradeTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link {{ (!isset($tab) || $tab == 'openedOrder') ? 'active' : '' }}" data-bs-toggle="tab" href="#openOrders" role="tab">Orders</a></li>
                    <li class="nav-item"><a class="nav-link {{ (isset($tab) && $tab == 'summary') ? 'active' : '' }}" data-bs-toggle="tab" href="#summary" role="tab">Pending</a></li>
                    <li class="nav-item"><a class="nav-link {{ (isset($tab) && $tab == 'history') ? 'active' : '' }}" data-bs-toggle="tab" href="#history" role="tab">History</a></li>
                </ul>
                <div class="account-summary-inline d-flex flex-wrap">
                    <div><span class="text-secondary">Balance:</span> <span class="text-light">${{ number_format($finance['balance'], 2) }}</span></div>
                    <div><span class="text-secondary">Margin:</span> <span class="text-light">${{ number_format($finance['freeMargin'], 2) }}</span></div>
                    <div><span class="text-secondary">Equity:</span> <span class="text-light">${{ number_format($finance['equity'], 2) }}</span></div>
                    <div><span class="text-secondary">Credit:</span> <span class="text-light">${{ number_format($finance['credit'], 2) }}</span></div>
                    <div><span class="text-secondary">Bonus:</span> <span class="text-light">${{ number_format($finance['bonus'], 2) }}</span></div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Open Orders Tab -->
                <div class="tab-pane fade {{ (!isset($tab) || $tab == 'openedOrder') ? 'show active' : '' }}" id="openOrders" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Entry Price</th>
                                <th>Current Price</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Created at</th>
                                <th>Profit &amp; Loss</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($openOrders as $order)
                                    <tr>
                                        <td>{{ $order->asset->name }}</td>
                                        <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                        <td>{{ number_format($order->amount, 2) }}</td>
                                        <td>{{ number_format($order->open_price, 5) }}</td>
                                        <td>{{ number_format($order->type == 1 ? $order->asset->bid_price : $order->asset->ask_price, 5) }}</td>
                                        <td>{{ $order->s_l ?? '-' }}</td>
                                        <td>{{ $order->s_p ?? '-' }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                        <td class="pnl active_pnl {{ $order->pnl < 0 ? 'text-danger' : 'text-success' }}" data-order-id="{{ $order->id }}">
                                            ${{ number_format($order->pnl, 2) }}
                                        </td>
                                        <td>
                                            <form action="{{ route('order.close', ['id'=>$order->id]) }}" class="d-inline" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to close this order?')">
                                                    Close
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        {{ __('web.no_orders_found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- History Tab -->
                <div class="tab-pane fade {{ (isset($tab) && $tab == 'history') ? 'show active' : '' }}" id="history" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Open Price</th>
                                <th>Close Price</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Opened</th>
                                <th>Closed</th>
                                <th>Profit &amp; Loss</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($closedOrders as $order)
                                    <tr>
                                        <td>{{ $order->asset->name }}</td>
                                        <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                        <td>{{ number_format($order->amount, 2) }}</td>
                                        <td>{{ number_format($order->open_price, 5) }}</td>
                                        <td>{{ number_format($order->close_price, 5) }}</td>
                                        <td>{{ $order->s_l ?? '-' }}</td>
                                        <td>{{ $order->s_p ?? '-' }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->closed_at)) }}</td>
                                        <td class="pnl {{ $order->pnl < 0 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format($order->pnl, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        {{ __('web.no_orders_found') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($closedOrders->hasPages())
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $closedOrders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
                
                <!-- Pending Orders Tab -->
                <div class="tab-pane fade {{ (isset($tab) && $tab == 'summary') ? 'show active' : '' }}" id="summary" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Order Price</th>
                                <th>Current Price</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingOrders as $order)
                                    <tr>
                                        <td>{{ $order->asset->name }}</td>
                                        <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                        <td>{{ number_format($order->amount, 2) }}</td>
                                        <td>{{ number_format($order->open_price, 5) }}</td>
                                        <td>{{ number_format($order->type == 1 ? $order->asset->ask_price : $order->asset->bid_price, 5) }}</td>
                                        <td>{{ $order->s_l ?? '-' }}</td>
                                        <td>{{ $order->s_p ?? '-' }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('order.delete', ['id'=>$order->id]) }}" class="d-inline" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this pending order?')">
                                                    Cancel
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        {{ __('web.no_pending_orders') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Interface -->
<div id="accountInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="header-text">
                    <h1>Account Dashboard</h1>
                    <p>Overview of your trading account and statistics</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Trading</span>
                </button>
            </div>
        </div>

        <!-- Account Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card balance-card">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['balance'], 2) }}</h3>
                    <p>Account Balance</p>
                </div>
                <div class="stat-trend positive">
                    <i class="bi bi-arrow-up"></i>
                </div>
            </div>

            <div class="stat-card equity-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['equity'], 2) }}</h3>
                    <p>Total Equity</p>
                </div>
                <div class="stat-trend positive">
                    <i class="bi bi-arrow-up"></i>
                </div>
            </div>

            <div class="stat-card margin-card">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['freeMargin'], 2) }}</h3>
                    <p>Free Margin</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="bi bi-dash"></i>
                </div>
            </div>

            <div class="stat-card pnl-card">
                <div class="stat-icon">
                    <i class="bi bi-lightning"></i>
                </div>
                <div class="stat-content">
                    <h3 class="{{ $finance['currentPL'] >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($finance['currentPL'], 2) }}</h3>
                    <p>Current P&L</p>
                </div>
                <div class="stat-trend {{ $finance['currentPL'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="bi bi-{{ $finance['currentPL'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                </div>
            </div>
        </div>

        <!-- Account Info Cards -->
        <div class="info-cards-grid">
            <div class="info-card personal-info">
                <div class="card-header">
                    <h3><i class="bi bi-person-lines-fill"></i> Personal Information</h3>
                    <button class="btn btn-sm btn-outline-primary edit-profile-btn" id="editProfileBtn">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                </div>
                <div class="card-content">
                    <form id="profileForm" action="{{ route('client.update.profile') }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <div class="info-row">
                            <span class="label">Name</span>
                            <input type="text" name="name" class="form-control-modern" value="{{ auth()->guard('client')->user()->name }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">Email</span>
                            <input type="email" name="email" class="form-control-modern" value="{{ auth()->guard('client')->user()->email }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone</span>
                            <input type="text" name="phone" class="form-control-modern" value="{{ auth()->guard('client')->user()->phone ?? '' }}" placeholder="Enter your phone number">
                        </div>
                        <div class="info-row">
                            <span class="label">Country</span>
                            <input type="text" name="country" class="form-control-modern" value="{{ auth()->guard('client')->user()->country ?? '' }}" placeholder="Enter your country">
                        </div>
                        <div class="info-row">
                            <span class="label">Account Type</span>
                            <span class="value badge-premium">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                            <small class="text-muted">Account type cannot be changed</small>
                        </div>
                        <div class="form-actions mt-3">
                            <button type="submit" class="btn btn-gradient-primary me-2">
                                <i class="bi bi-check-lg"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary cancel-edit-btn">
                                <i class="bi bi-x-lg"></i> Cancel
                            </button>
                        </div>
                    </form>
                    
                    <div id="profileDisplay" class="profile-display">
                        <div class="info-row">
                            <span class="label">Name</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Email</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Country</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->country ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Account Type</span>
                            <span class="value badge-premium enhanced">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card trading-stats">
                <div class="card-header">
                    <h3><i class="bi bi-graph-up-arrow"></i> Trading Statistics</h3>
                </div>
                <div class="card-content">
                    <div class="stats-grid-3x2">
                        <div class="stat-item">
                            <div class="stat-circle total-orders">
                                <span>{{ number_format($finance['totalOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Total Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle active-orders">
                                <span>{{ number_format($finance['activeOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Active Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle closed-orders">
                                <span>{{ number_format($finance['closedOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Closed Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle win-orders">
                                <span>{{ number_format($finance['winOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Win Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle lose-orders">
                                <span>{{ number_format($finance['loseOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Lose Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle total-pnl {{ ($finance['totalPnL'] ?? 0) >= 0 ? 'profit' : 'loss' }}">
                                <span>${{ number_format($finance['totalPnL'] ?? 0, 2) }}</span>
                            </div>
                            <p>Total PnL</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="action-btn deposit-action" onclick="showDepositInterface()">
                <i class="bi bi-arrow-up-circle"></i>
                <span>Make Deposit</span>
            </button>
            <button class="action-btn withdrawal-action" onclick="showWithdrawalInterface()">
                <i class="bi bi-arrow-down-circle"></i>
                <span>Withdraw Funds</span>
            </button>
            <button class="action-btn trading-action" onclick="showMainContent()">
                <i class="bi bi-graph-up"></i>
                <span>Start Trading</span>
            </button>
        </div>
    </div>
</div>

<!-- Deposit Interface -->
<div id="depositInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-arrow-up-circle"></i>
                </div>
                <div class="header-text">
                    <h1>Deposit Funds</h1>
                    <p>Fund your account quickly and securely</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Trading</span>
                </button>
            </div>
        </div>

        <!-- Current Balance Display -->
        <div class="balance-display-card mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="balance-icon me-3">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h6 class="text-white mb-1">Current Balance</h6>
                        <h3 class="text-white mb-0">${{ number_format($finance['balance'], 2) }}</h3>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-white">Available for withdrawal</small>
                    <div class="text-success font-weight-bold">${{ number_format($finance['balance'], 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Deposit Methods Grid -->
        <div class="deposit-methods-grid">
            <!-- Bank Transfer Method -->
            <div class="deposit-method-card bank-transfer-card">
                <div class="method-header">
                    <div class="method-icon bank-icon">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="method-info">
                        <h4>Bank Transfer</h4>
                        <p>Secure bank-to-bank transfer</p>
                        <div class="method-features">
                            <span class="feature-badge">No Fees</span>
                            <span class="feature-badge">1-3 Business Days</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="bankDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="bank_transfer">
                        
                        <div class="form-group">
                            <label for="bank_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="bank_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="country_select" class="form-label">Select Country</label>
                            <select name="country" id="country_select" class="form-select-modern" required>
                                <option value="">Choose your country...</option>
                                @php
                                    $countries = [];
                                    foreach($banks ?? [] as $bank) {
                                        if (!empty($bank->country) && !in_array($bank->country, $countries)) {
                                            $countries[] = $bank->country;
                                        }
                                    }
                                    sort($countries);
                                @endphp
                                @foreach($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="bank_select" class="form-label">Select Bank</label>
                            <select name="bank_id" id="bank_select" class="form-select-modern" required disabled>
                                <option value="">First select a country...</option>
                            </select>
                        </div>
                        
                        <!-- Bank Details Display -->
                        <div id="bankDetailsDisplay" class="bank-details-card" style="display: none;">
                            <h5 class="text-white mb-3"><i class="bi bi-bank me-2"></i>Bank Transfer Details</h5>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Bank Name:</span>
                                    <span class="detail-value" id="displayBankName">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Beneficiary Name:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAccountName">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAccountName').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAccountNumber">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAccountNumber').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="ibanRow" style="display: none;">
                                    <span class="detail-label">IBAN:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayIban">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayIban').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="swiftCodeRow" style="display: none;">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displaySwiftCode">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displaySwiftCode').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="abaRoutingRow" style="display: none;">
                                    <span class="detail-label">ABA Routing Number:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAbaRouting">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAbaRouting').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="beneficiaryAddressRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Address:</span>
                                    <span class="detail-value" id="displayBeneficiaryAddress">-</span>
                                </div>
                                <div class="detail-item" id="beneficiaryCountryRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Country:</span>
                                    <span class="detail-value" id="displayBeneficiaryCountry">-</span>
                                </div>
                                <div class="detail-item" id="bankAddressRow" style="display: none;">
                                    <span class="detail-label">Bank Address:</span>
                                    <span class="detail-value" id="displayBankAddress">-</span>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Please use these details to make your bank transfer and upload the receipt below.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bank_receipt" class="form-label">Upload Receipt</label>
                            <div class="file-upload-area">
                                <input type="file" name="receipt" id="bank_receipt" class="file-input" 
                                       accept=".pdf,.png,.jpg,.jpeg" required>
                                <div class="file-upload-content">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <small>PDF, PNG, JPG, JPEG (Max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-deposit-submit bank-submit">
                            <i class="bi bi-bank me-2"></i>
                            <span>Submit Bank Transfer</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cryptocurrency Method -->
            <div class="deposit-method-card crypto-card">
                <div class="method-header">
                    <div class="method-icon crypto-icon">
                        <i class="bi bi-currency-bitcoin"></i>
                    </div>
                    <div class="method-info">
                        <h4>Cryptocurrency</h4>
                        <p>Fast and secure crypto deposits</p>
                        <div class="method-features">
                            <span class="feature-badge crypto">Low Fees</span>
                            <span class="feature-badge crypto">Instant</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="cryptoDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="cryptocurrency">
                        
                        <div class="form-group">
                            <label for="crypto_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="crypto_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="crypto_type_select" class="form-label">Cryptocurrency</label>
                            <select name="crypto_type" id="crypto_type_select" class="form-select-modern" required>
                                <option value="">Select cryptocurrency...</option>
                                <option value="USDT">Tether (USDT)</option>
                            </select>
                        </div>
                        
                        <!-- USDT Address Display -->
                        <div id="usdtAddressDisplay" class="crypto-address-card" style="display: none;">
                            <h5 class="text-white mb-3"><i class="bi bi-wallet2 me-2"></i>USDT Deposit Address</h5>
                            @php
                                // Get USDT address with fallback logic
                                $client = auth('client')->user();
                                $usdtAddress = null;
                                
                                if ($client) {
                                    // First try client's usdt column
                                    if (!empty($client->usdt)) {
                                        $usdtAddress = is_string($client->usdt) ? $client->usdt : (is_array($client->usdt) ? reset($client->usdt) : null);
                                    }
                                    // If client usdt is null/empty, get USDT from the pipeline this client belongs to
                                    elseif (!empty($client->pipeline_id)) {
                                        // Get the pipeline by pipeline_id
                                        $pipeline = \App\Models\Pipeline::find($client->pipeline_id);
                                        
                                        if ($pipeline && !empty($pipeline->usdt)) {
                                            $pipelineUsdt = $pipeline->usdt;
                                            
                                            // Handle different data types for pipeline usdt
                                            if (is_array($pipelineUsdt) && !empty($pipelineUsdt)) {
                                                $usdtAddress = reset($pipelineUsdt);
                                            } elseif (is_string($pipelineUsdt) && trim($pipelineUsdt) !== '') {
                                                $usdtAddress = trim($pipelineUsdt);
                                            }
                                        }
                                    }
                                }
                            @endphp
                            
                            @if($usdtAddress)
                                <div class="address-section">
                                    <div class="usdt-deposit-container">
                                        <!-- QR Code Section -->
                                        <div class="qr-section-top mb-4">
                                            <div class="qr-code-frame">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($usdtAddress) }}&format=png&bgcolor=FFFFFF&color=000000"
                                                     alt="USDT Address QR Code"
                                                     class="qr-code-image-large">
                                                <div class="qr-label">
                                                    <i class="bi bi-qr-code me-2"></i>
                                                    <span>Scan with Any Crypto Wallet</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- USDT Address Display -->
                                        <div class="address-section-main">
                                            <div class="address-frame">
                                                <label for="usdtAddressValue" class="address-label">
                                                    <i class="bi bi-wallet2 me-2"></i>
                                                    USDT (TRC20) Deposit Address
                                                </label>
                                                <div class="address-input-container">
                                                    <div class="address-value-modern" id="usdtAddress">{{ $usdtAddress }}</div>
                                                    <input type="hidden" id="usdtAddressValue" value="{{ $usdtAddress }}">
                                                    <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('usdtAddress').textContent)">
                                                        <i class="bi bi-copy"></i>
                                                        <span>Copy</span>
                                                    </button>
                                                </div>
                                                <div class="address-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    <span>Send only USDT (TRC20) to this address. Scan QR code or copy address to your crypto wallet.</span>
                                                </div>
                                                <div class="address-info mt-3">
                                                    <small class="text-light text-center d-block">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Compatible with all major crypto wallets and exchanges. Scan QR or copy and paste address.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="address-section">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>USDT Address Not Available</strong>
                                        <p class="mb-0 mt-2">Please contact our support team to set up your USDT deposit address.</p>
                                        <small class="d-block mt-2">
                                            <i class="bi bi-envelope me-1"></i>
                                            Contact support for assistance with cryptocurrency deposits.
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="crypto_receipt" class="form-label">Upload Receipt</label>
                            <div class="file-upload-area">
                                <input type="file" name="receipt" id="crypto_receipt" class="file-input" 
                                       accept=".pdf,.png,.jpg,.jpeg" required>
                                <div class="file-upload-content">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <small>PDF, PNG, JPG, JPEG (Max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-deposit-submit crypto-submit">
                            <i class="bi bi-currency-bitcoin me-2"></i>
                            <span>Submit Crypto Deposit</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Credit Card Method -->
            <div class="deposit-method-card credit-card-card">
                <div class="method-header">
                    <div class="method-icon credit-card-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="method-info">
                        <h4>Credit Card</h4>
                        <p>Fast and secure card payments</p>
                        <div class="method-features">
                            <span class="feature-badge credit-card">Instant</span>
                            <span class="feature-badge credit-card">Secure</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="creditCardDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="credit_card">
                        
                        <div class="form-group">
                            <label for="credit_card_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="credit_card_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>

                        <!-- Credit Card Details -->
                        <div class="credit-card-details">
                            <h6 class="text-white mb-3"><i class="bi bi-credit-card me-2"></i>Card Information</h6>
                            
                            <div class="form-group">
                                <label for="card_number" class="form-label">Card Number</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <input type="text" name="card_number" id="card_number" class="form-control-modern" 
                                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="card_expiry" class="form-label">Expiry Date</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-calendar"></i>
                                            <input type="text" name="card_expiry" id="card_expiry" class="form-control-modern" 
                                                   placeholder="MM/YY" maxlength="5" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-shield-lock"></i>
                                            <input type="text" name="card_cvv" id="card_cvv" class="form-control-modern" 
                                                   placeholder="123" maxlength="4" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="card_holder_name" class="form-label">Cardholder Name</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="card_holder_name" id="card_holder_name" class="form-control-modern" 
                                           placeholder="John Doe" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-geo-alt"></i>
                                    <textarea name="billing_address" id="billing_address" class="form-control-modern" 
                                              rows="3" placeholder="123 Main St, City, State, ZIP" required></textarea>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="bi bi-shield-check me-2"></i>
                                Your card information is encrypted and secure. We use industry-standard security measures.
                            </div>
                        </div>

                        <button type="submit" class="btn-deposit-submit credit-card-submit">
                            <i class="bi bi-credit-card me-2"></i>
                            <span>Process Payment</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Deposits Section -->
        <div class="recent-transactions-section">
            <div class="section-header">
                <h3><i class="bi bi-clock-history me-2"></i>Recent Deposits</h3>
                <button class="btn btn-outline-primary btn-sm refresh-btn">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
            
            <!-- Deposit Tabs -->
            <div class="transaction-tabs">
                <nav class="nav nav-tabs nav-tabs-dark" id="depositTabs" role="tablist">
                    <button class="nav-link active" id="all-deposits-tab" data-bs-toggle="tab" data-bs-target="#all-deposits" type="button" role="tab" aria-controls="all-deposits" aria-selected="true">
                        <i class="bi bi-list-ul me-1"></i>All
                    </button>
                    <button class="nav-link" id="pending-deposits-tab" data-bs-toggle="tab" data-bs-target="#pending-deposits" type="button" role="tab" aria-controls="pending-deposits" aria-selected="false">
                        <i class="bi bi-clock me-1"></i>Pending
                    </button>
                    <button class="nav-link" id="accepted-deposits-tab" data-bs-toggle="tab" data-bs-target="#accepted-deposits" type="button" role="tab" aria-controls="accepted-deposits" aria-selected="false">
                        <i class="bi bi-check-circle me-1"></i>Accepted
                    </button>
                    <button class="nav-link" id="rejected-deposits-tab" data-bs-toggle="tab" data-bs-target="#rejected-deposits" type="button" role="tab" aria-controls="rejected-deposits" aria-selected="false">
                        <i class="bi bi-x-circle me-1"></i>Rejected
                    </button>
                </nav>
                
                <div class="tab-content" id="depositTabContent">
                    <!-- All Deposits Tab -->
                    <div class="tab-pane fade show active" id="all-deposits" role="tabpanel" aria-labelledby="all-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="all-deposits-tbody">
                                    @php
                                        $currentClient = auth()->guard('client')->user();
                                        $clientId = $currentClient ? $currentClient->id : null;
                                        $brokerIdToUse = ($currentClient && $currentClient->broker_id) ? $currentClient->broker_id : $clientId;
                                        
                                        $allDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $allDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    
                                    @forelse($allDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @elseif($deposit->credit_card_details)
                                                    Credit Card
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $status = strtolower($deposit->status ?? '');
                                                    $displayStatus = ucfirst($deposit->status ?? 'Unknown');
                                                @endphp
                                                @if($status === 'pending')
                                                    <span class="badge bg-warning">{{ $displayStatus }}</span>
                                                @elseif($status === 'accepted')
                                                    <span class="badge bg-success">{{ $displayStatus }}</span>
                                                @elseif($status === 'rejected')
                                                    <span class="badge bg-danger">{{ $displayStatus }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $displayStatus }}</span>
                                                @endif
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-inbox display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No deposits found</p>
                                                    <small class="text-light opacity-75">Your deposit history will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pending Deposits Tab -->
                    <div class="tab-pane fade" id="pending-deposits" role="tabpanel" aria-labelledby="pending-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="pending-deposits-tbody">
                                    @php
                                        $pendingDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $pendingDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'pending')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    
                                    @forelse($pendingDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @elseif($deposit->credit_card_details)
                                                    Credit Card
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ ucfirst($deposit->status ?? 'Pending') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No pending deposits found</p>
                                                    <small class="text-light opacity-75">Pending deposits will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Accepted Deposits Tab -->
                    <div class="tab-pane fade" id="accepted-deposits" role="tabpanel" aria-labelledby="accepted-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="accepted-deposits-tbody">
                                    @php
                                        $acceptedDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $acceptedDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'accepted')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    @forelse($acceptedDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($deposit->status ?? 'Accepted') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-check-circle display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No accepted deposits found</p>
                                                    <small class="text-light opacity-75">Accepted deposits will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Rejected Deposits Tab -->
                    <div class="tab-pane fade" id="rejected-deposits" role="tabpanel" aria-labelledby="rejected-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="rejected-deposits-tbody">
                                    @php
                                        $rejectedDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $rejectedDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'rejected')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    @forelse($rejectedDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">{{ ucfirst($deposit->status ?? 'Rejected') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-x-circle display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No rejected deposits found</p>
                                                    <small class="text-light opacity-75">Rejected deposits will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Interface -->
<div id="withdrawalInterface" class="main-content" style="display: none;">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-12">
                <div class="panel h-100">
                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="text-white mb-2">
                                <i class="bi bi-arrow-down-circle me-3" style="color: #ffcc02;"></i>
                                Withdrawal Management
                            </h2>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-gradient-primary new-withdrawal-btn" data-bs-toggle="modal" data-bs-target="#newWithdrawalModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                New Withdrawal
                            </button>
                            <button class="btn btn-gradient-primary back-to-trading-btn">
                                <i class="bi bi-arrow-left me-2"></i>
                                Back to Trading
                            </button>
                        </div>
                    </div>

                    <!-- Balance Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon balance-icon me-3">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">Available Balance</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['balance'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon pending-icon me-3">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">Pending Withdrawals</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['pendingWithdrawal'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon completed-icon me-3">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">Total Withdrawn</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['totalWithdrawal'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Withdrawals Section -->
                    <div class="recent-transactions-section">
                        <div class="section-header">
                            <h3><i class="bi bi-clock-history me-2"></i>Recent Withdrawals</h3>
                            <button class="btn btn-outline-primary btn-sm refresh-btn">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                        
                        <!-- Withdrawal Tabs -->
                        <div class="transaction-tabs">
                            <nav class="nav nav-tabs nav-tabs-dark" id="withdrawalTabs" role="tablist">
                                <button class="nav-link active" id="all-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#all-withdrawals" type="button" role="tab" aria-controls="all-withdrawals" aria-selected="true">
                                    <i class="bi bi-list-ul me-1"></i>All
                                </button>
                                <button class="nav-link" id="pending-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#pending-withdrawals" type="button" role="tab" aria-controls="pending-withdrawals" aria-selected="false">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </button>
                                <button class="nav-link" id="accepted-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#accepted-withdrawals" type="button" role="tab" aria-controls="accepted-withdrawals" aria-selected="false">
                                    <i class="bi bi-check-circle me-1"></i>Accepted
                                </button>
                                <button class="nav-link" id="rejected-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#rejected-withdrawals" type="button" role="tab" aria-controls="rejected-withdrawals" aria-selected="false">
                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                </button>
                            </nav>
                            
                            <div class="tab-content" id="withdrawalTabContent">
                                <!-- All Withdrawals Tab -->
                                <div class="tab-pane fade show active" id="all-withdrawals" role="tabpanel" aria-labelledby="all-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="all-withdrawals-tbody">
                                                @php
                                                    $currentClient = auth()->guard('client')->user();
                                                    $clientId = $currentClient ? $currentClient->id : null;
                                                    $brokerIdToUse = ($currentClient && $currentClient->broker_id) ? $currentClient->broker_id : $clientId;
                                                    
                                                    $allWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $allWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                
                                                @forelse($allWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = strtolower($withdrawal->status ?? '');
                                                                $displayStatus = ucfirst($withdrawal->status ?? 'Unknown');
                                                            @endphp
                                                            @if($status === 'pending')
                                                                <span class="badge bg-warning">{{ $displayStatus }}</span>
                                                            @elseif($status === 'accepted')
                                                                <span class="badge bg-success">{{ $displayStatus }}</span>
                                                            @elseif($status === 'rejected')
                                                                <span class="badge bg-danger">{{ $displayStatus }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $displayStatus }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-inbox display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No withdrawals found</p>
                                                                <small class="text-light opacity-75">Your withdrawal history will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pending Withdrawals Tab -->
                                <div class="tab-pane fade" id="pending-withdrawals" role="tabpanel" aria-labelledby="pending-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pending-withdrawals-tbody">
                                                @php
                                                    $pendingWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $pendingWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'pending')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                
                                                @forelse($pendingWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning">{{ ucfirst($withdrawal->status ?? 'Pending') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No pending withdrawals found</p>
                                                                <small class="text-light opacity-75">Pending withdrawals will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Accepted Withdrawals Tab -->
                                <div class="tab-pane fade" id="accepted-withdrawals" role="tabpanel" aria-labelledby="accepted-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="accepted-withdrawals-tbody">
                                                @php
                                                    $acceptedWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $acceptedWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'accepted')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                @forelse($acceptedWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">{{ ucfirst($withdrawal->status ?? 'Accepted') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-check-circle display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No accepted withdrawals found</p>
                                                                <small class="text-light opacity-75">Accepted withdrawals will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Rejected Withdrawals Tab -->
                                <div class="tab-pane fade" id="rejected-withdrawals" role="tabpanel" aria-labelledby="rejected-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rejected-withdrawals-tbody">
                                                @php
                                                    $rejectedWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $rejectedWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'rejected')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                @forelse($rejectedWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-danger">{{ ucfirst($withdrawal->status ?? 'Rejected') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-x-circle display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No rejected withdrawals found</p>
                                                                <small class="text-light opacity-75">Rejected withdrawals will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Withdrawal Modal -->
<div class="modal fade" id="newWithdrawalModal" tabindex="-1" aria-labelledby="newWithdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h4 class="modal-title text-white" id="newWithdrawalModalLabel">
                    <i class="bi bi-arrow-down-circle me-2" style="color: #ffcc02;"></i>
                    Request New Withdrawal
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Withdrawal Method Tabs -->
                <ul class="nav nav-pills mb-4" id="withdrawalMethodTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="font-size: 1.05rem;" id="bank-tab" data-bs-toggle="pill" data-bs-target="#bank-transfer" type="button" role="tab">
                            <i class="bi bi-bank me-2"></i>Bank Transfer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" style="font-size: 1.05rem;" id="crypto-tab" data-bs-toggle="pill" data-bs-target="#cryptocurrency" type="button" role="tab">
                            <i class="bi bi-currency-bitcoin me-2"></i>Cryptocurrency
                        </button>
                    </li>
                </ul>

                <!-- Available Balance Alert -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong style="font-size: 1.1rem;">Available Balance:</strong> <span style="font-size: 1.1rem;">${{ number_format($finance['balance'], 2) }}</span>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="withdrawalMethodContent">
                    <!-- Bank Transfer Tab -->
                    <div class="tab-pane fade show active" id="bank-transfer" role="tabpanel">
                        <form id="bankWithdrawalForm" action="{{ route('client.withdrawal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="bank_transfer">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_amount" class="form-label text-white" style="font-size: 1.05rem;">Amount (USD)</label>
                                        <input type="number" id="bank_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;"
                                               min="1" max="{{ $finance['balance'] }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">Minimum: $1.00 | Maximum: ${{ number_format($finance['balance'], 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_holder" class="form-label text-white" style="font-size: 1.05rem;">Account Holder Name</label>
                                        <input type="text" name="account_holder" id="account_holder" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label text-white" style="font-size: 1.05rem;">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_number" class="form-label text-white" style="font-size: 1.05rem;">Account Number</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="swift_code" class="form-label text-white" style="font-size: 1.05rem;">SWIFT/Routing Code</label>
                                <input type="text" name="swift_code" id="swift_code" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-gradient-danger" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>Submit Withdrawal Request
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Cryptocurrency Tab -->
                    <div class="tab-pane fade" id="cryptocurrency" role="tabpanel">
                        <form id="cryptoWithdrawalForm" action="{{ route('client.withdrawal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="cryptocurrency">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="crypto_amount" class="form-label text-white" style="font-size: 1.05rem;">Amount (USD)</label>
                                        <input type="number" id="crypto_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;"
                                               min="1" max="{{ $finance['balance'] }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">Minimum: $1.00 | Maximum: ${{ number_format($finance['balance'], 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="crypto_type_withdrawal" class="form-label text-white" style="font-size: 1.05rem;">Cryptocurrency</label>
                                        <select name="crypto_type" id="crypto_type_withdrawal" class="form-select bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                            <option value="">Select Cryptocurrency</option>
                                            <option value="BTC">Bitcoin (BTC)</option>
                                            <option value="ETH">Ethereum (ETH)</option>
                                            <option value="USDT">Tether (USDT)</option>
                                            <option value="LTC">Litecoin (LTC)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="wallet_address" class="form-label text-white" style="font-size: 1.05rem;">Wallet Address</label>
                                <input type="text" name="wallet_address" id="wallet_address" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                <small class="text-white" style="font-size: 0.95rem;">Enter your cryptocurrency wallet address. Double-check this address as transactions cannot be reversed.</small>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-gradient-warning" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>Submit Withdrawal Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="editOrderModalLabel">
                    <i class="bi bi-pencil-square me-2" style="color: #4f8cff;"></i>
                    Edit Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOrderForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editOrderId" name="order_id">
                    
                    <div class="mb-3">
                        <label for="edit_stop_loss" class="form-label text-white">Stop Loss</label>
                        <input type="number" id="edit_stop_loss" name="stop_loss" class="form-control bg-dark text-white border-secondary" 
                               step="0.00001" placeholder="Enter stop loss price">
                        <small class="text-muted">Optional: Set a stop loss price to limit losses</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_take_profit" class="form-label text-white">Take Profit</label>
                        <input type="number" id="edit_take_profit" name="take_profit" class="form-control bg-dark text-white border-secondary" 
                               step="0.00001" placeholder="Enter take profit price">
                        <small class="text-muted">Optional: Set a take profit price to secure gains</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logoutForm" action="{{ route('client.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Chat Interface -->
<div id="chatInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="header-text">
                    <h1>Live Chat Support</h1>
                    <p>Get instant help from our support team</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Trading</span>
                </button>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                @foreach ($chat ?? [] as $message)
                    @if ($message->user_id)
                        <!-- Support Message -->
                        <div class="message support-message">
                            <div class="message-avatar">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">Support Team</span>
                                    <span class="message-time">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span>
                                </div>
                                <div class="message-text">{{ $message->message }}</div>
                            </div>
                        </div>
                    @else
                        <!-- User Message -->
                        <div class="message user-message">
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">You</span>
                                    <span class="message-time">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span>
                                </div>
                                <div class="message-text">{{ $message->message }}</div>
                            </div>
                            <div class="message-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                @if(empty($chat) || count($chat) == 0)
                    <div class="welcome-message">
                        <div class="welcome-icon">
                            <i class="bi bi-chat-heart"></i>
                        </div>
                        <h3>Welcome to Live Chat Support!</h3>
                        <p>Our support team is here to help you with any questions or issues you may have.</p>
                        <p>Send us a message and we'll get back to you as soon as possible.</p>
                    </div>
                @endif
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container">
                <form id="chatForm" action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    <div class="chat-input-wrapper">
                        <div class="input-with-icon">
                            <textarea name="message" id="chatMessage" class="chat-input" 
                                      placeholder="Type your message here..." 
                                      rows="1" required></textarea>
                            <button type="submit" class="send-button">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Actions -->
                <div class="chat-quick-actions">
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('I need help with my account')">
                        <i class="bi bi-person-gear"></i>
                        <span>Account Help</span>
                    </button>
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('I have a question about trading')">
                        <i class="bi bi-graph-up"></i>
                        <span>Trading Question</span>
                    </button>
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('I need help with deposits/withdrawals')">
                        <i class="bi bi-credit-card"></i>
                        <span>Payment Help</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Notification Popup -->
<div id="notificationPopup" class="notification-popup" style="display: none;">
    <div class="notification-popup-content">
        <div class="notification-popup-header">
            <div class="notification-popup-title">
                <i class="bi bi-bell me-2"></i>
                <span>Notifications</span>
            </div>
            <button class="notification-popup-close" onclick="closeNotificationPopup()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="notification-popup-messages" id="notificationPopupMessages">
            @if(empty($notifications) || count($notifications) == 0)
                <div class="no-notifications-message">
                    <div class="no-notifications-icon">
                        <i class="bi bi-bell-slash"></i>
                    </div>
                    <h4>No Notifications</h4>
                    <p>You don't have any notifications at this time.</p>
                </div>
            @else
                @foreach($notifications as $notification)
                    <div class="notification-item" data-id="{{ $notification->id }}">
                        <div class="notification-content">
                            <div class="notification-text">{{ __('web.'.$notification->text) }}</div>
                            <div class="notification-time">{{ date('d/m H:i', strtotime($notification->created_at)) }}</div>
                        </div>
                        <div class="notification-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/main_tp.js?v1.600') }}"></script>
<script src="{{ url('assets/js/webtrader2.js?v2.0') }}"></script>

<!-- Add CSRF token and routes for JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Global variables for JavaScript
    var client_id = {{ auth()->guard('client')->user()->id }};
    var assetId = {{ $asset && $asset->id ? $asset->id : 'null' }};
    
    // Current symbol for JavaScript
    window.currentSymbol = '{{ $symbol ?? '' }}';
    
    // Routes for JavaScript
    window.updateOrderRoute = '{{ route("order.update", ["id" => "__ORDER_ID__"]) }}';
    
    // Banks data for filtering
    window.banksData = @json($banks ?? []);
    
    // Notifications data
    window.notificationsData = @json($notifications ?? []);
    
    // Crypto wallets (example data - you should get this from your configuration)
    window.cryptoWallets = {
        'BTC': '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
        'ETH': '0x742d35Cc6634C0532925a3b8D82D5F62B5D5D',
        'USDT': 'TUDsqK9VqKmJ5KjWKVfK3aEKwKKKvKJKKv',
        'LTC': 'LTC1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'
    };
    
    // Add route for toggling favourites
    document.body.setAttribute('data-toggle-favourite-route', '{{ route("toggle.favourite") }}');
</script>


</body>
</html>
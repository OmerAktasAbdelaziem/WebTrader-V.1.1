<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #181d23;
            color: #e0e0e0;
            font-family: 'Segoe UI', 'Inter', Arial, sans-serif;
        }
        #assetGrid::-webkit-scrollbar {
            width: 4px;
        }
        #assetGrid::-webkit-scrollbar-thumb {
            background-color: #4f8cff;
            border-radius: 10px;
        }
        #assetGrid {
            overflow-x: hidden !important; 
            overflow-y: auto !important;   
            max-width: 100%;               
        }
        #customContextMenu {
            position: absolute;
            z-index: 1050;
            background: #23272f;
            color: #e0e0e0;
            border-radius: 7px;
            box-shadow: 0 4px 16px #0006;
            display: none;
            padding: 0.2rem 0;
            border: 1px solid #353b48;
            font-family: 'Segoe UI', 'Inter', Arial, sans-serif;
        }
        #customContextMenu button {
            background: none;
            border: none;
            color: #e0e0e0;
            width: 100%;
            padding: 6px 12px;
            text-align: left;
            cursor: pointer;
            font-size: 0.92rem;
            transition: background 0.18s, color 0.18s;
            border-radius: 0;
        }
        #customContextMenu button:first-child {
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
        }
        #customContextMenu button:last-child {
            border-bottom-left-radius: 7px;
            border-bottom-right-radius: 7px;
        }
        #customContextMenu button:hover, #customContextMenu button:focus {
            background: linear-gradient(90deg, #4f8cff22 0%, #23272f 100%);
            color: #4f8cff;
            outline: none;
        }
        .star-icon {
            color: #4f8cff;
            margin-left: 6px;
            font-size: 1.1rem;
        }
        .sidebar {
            background: #181d23;
            min-height: 100vh;
            width: 50px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0 1rem 0;
            border-right: 1px solid #23272f;
            z-index: 100;
            box-shadow: 2px 0 16px #0003;
        }
        .sidebar img {
            width: 48px;
            margin-bottom: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px #0004;
        }
        .sidebar .nav-icon {
            color: #7a8599;
            font-size: 2rem;
            margin: 1.5rem 0;
            cursor: pointer;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
            padding: 0.7rem;
            border-radius: 10px;
        }
        .sidebar .nav-icon.active,
        .sidebar .nav-icon:hover {
            color: #fff;
            background: #23272f;
            box-shadow: 0 2px 8px #0002;
        }
        .sidebar .logout {
            margin-top: auto;
            color: #ff4d4f;
        }
        .main-content {
            padding: 2.5rem 2vw 2rem 2vw;
        }
        .panel {
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 2rem 1.5rem 2rem;
            margin-left: 10px;
        }
        .details-panel {
            height: 34vh;
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 2rem 1.5rem 2rem;
            margin-left: 20px;
        }
        .assets{
            max-height: 371px;
            overflow-y: auto;
        }
        .right-side-panel{
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 0.01rem 1rem 1rem;
            margin-left: -20px;
            margin-right: -35px;
        }
        .order-form {
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            margin-left: -20px;
            margin-right: -35px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .order-form .d-flex {
            justify-content: center !important;
            align-items: center !important;
            width: 100%;
        }
        .btnorder {
            width: 180px;
            height: 50px;
            font-size: 0.7rem;
            border-radius: 7px;
            border: none;
            box-shadow: 0 2px 8px #0002;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: -1px;
            margin-right: -1px;
        }
        .btnorder.btn-danger {
            background: linear-gradient(90deg, #ff4d4f 60%, #c82333 100%);
            color: #fff;
        }
        .btnorder.btn-success {
            background: linear-gradient(90deg, #05ab18 60%, #218838 100%);
            color: #fff;
        }
        .btnorder:hover, .btnorder:focus {
            box-shadow: 0 4px 16px #4f8cff33;
            opacity: 0.95;
            outline: none;
        }
        .amount, .btnminus, .btnplus {
            width: 100px;
            height: 50px;
            background: #23272f;
            color: #b0b8c1;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            text-align: center;
        }
        .sellPrice, .buyPrice {
            font-size: 0.9rem;
            color: #fff;
        }
        .btnminus, .btnplus {
            width: 50px;
        }
        .input-group {
            margin-left: 35px;
        }
        .tv-widget-container {
            height: 55vh;
            min-height: 340px;
        }
        .nav-tabs {
            gap: 0.5rem;
        }
        .nav-tabs .nav-link {
            color: #b0b8c1;
            border: none;
            background: transparent;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-tabs .nav-link.active {
            color: #fff;
            background: #1c1f26;
        }
        .account-summary-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 2.2rem;
            align-items: center;
        }
        .account-summary-inline div {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .account-summary-inline .text-secondary {
            color: #b0b8c1 !important;
        }
        .table-dark {
            --bs-table-bg: #1c1f26;
            --bs-table-striped-bg: #20242b;
            --bs-table-hover-bg: #23272f;
        }
        .table-dark th, .table-dark td {
            color: #e0e0e0;
            vertical-align: middle;
        }
        .table-dark th {
            font-weight: 600;
            font-size: 1.01rem;
            border-bottom: 2px solid #23272f;
        }
        .table-dark td {
            font-size: 0.98rem;
        }
        .nav-tabs .nav-item {
            flex: 1;
            text-align: center;
        }
        .nav-tabs .nav-link {
            padding: 0.5rem 0.5rem !important;
            font-size: 0.9rem;
        }
        .ask_price {
            margin-left: 30px;
        }
        .market-assets{
            background: #1c1f26;
            color: #e0e0e0;
            border: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .market-assets:hover,
        .market-assets.active {
            background: linear-gradient(90deg, #23272f 0%, #1c1f26 100%);
            color: #4f8cff;
            box-shadow: 0 2px 12px #4f8cff22;
            border-left: 3px solid #05ab1875;
            transition: background 0.1s, color 0.2s, box-shadow 0.2s, border-left 0.2s;
        }
        .searchbar{
            width: 20vh;
            background: #23272f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .filtercategory{
            width: 20vh;
            background: #23272f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <i class="bi bi-house nav-icon active" title="Dashboard" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-bar-chart nav-icon" title="Markets" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-person nav-icon" title="Account" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-gear nav-icon" title="Settings" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-box-arrow-right nav-icon logout" title="Logout" style="font-size:1.2rem; padding:0.3rem;"></i>
</div>

<!-- Custom Context Menu -->
<div id="customContextMenu" class="shadow-lg p-2">
    <button id="goToAssetBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-arrow-right-circle fs-5"></i>
        <span>Go to Asset</span>
    </button>
    <button id="addToFavouriteBtn" data-asset-id="{{ $asset->id }}" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star fs-5 text-primary"></i>
        <span>Add to Favourites</span>
    </button>
</div>

<div class="main-content">
    <div class="row align-items-start" style="margin-top: -40px;">

        <!-- Chart & Tabs -->
        <div class="col-lg-9">
            <div class="panel mb-2">
                <!-- TradingView Widget -->
                <div class="tv-widget-container">
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                        "autosize": true,
                        "symbol": "{{ $symbol }}",
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
        <div class="col-lg-3">
            <div class="right-side-panel mb-3">
                <!-- Asset Search & Filters -->
                <div class="mb-2 d-flex gap-2 align-items-center">
                    <input type="text" id="assetSearch" class="searchbar form-control-sm" placeholder="Search symbols...">
                    <select id="categoryFilter" class="filtercategory form-select-sm">
                        <option value="all">All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="showFavouritesBtn" class="btn btn-sm" title="Show Favourites" style="background:#23272f; color:#4f8cff; border:none; padding: 0.3rem 0.6rem;">
                        <i class="bi bi-star-fill"></i>
                    </button>
                </div>
                <div class="assets d-grid gap-2" id="assetGrid">
                    <div class="row fw-bold text-secondary mb-2" style="font-size: 1rem;">
                        <div class="col-5">Market</div>
                        <div class="bid_price col-2 text-center">Bid</div>
                        <div class="ask_price col-2 text-center">Ask</div>
                    </div>
                    @foreach($assetsPrices as $asset)
                        <button type="button" class="row align-items-center asset-item market-assets mb-2" data-id="{{ $asset->id }}" data-symbol="{{ $asset->symbol }}" data-url="{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}"onclick="window.location.href='{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}'">
                            <div class="col-5 text-start">
                                <span class="name text-white fw-bold">
                                    {{ $asset->name }}
                                    @if (in_array($asset->id, $favourite_assets_ids))
                                        <span class="star-icon" style="color: gold; margin-left: 6px;">★</span>
                                    @endif
                                </span>
                            </div>

                            <div class="col-2 text-center">
                                <span class="bid_price text-danger" data-asset-id="{{ $asset->id }}">
                                    {{ number_format($asset->bid_price, 4) }}
                                </span>
                            </div>

                            <div class="col-2 text-end">
                                <span class="ask_price text-success" data-asset-id="{{ $asset->id }}">
                                    {{ number_format($asset->ask_price, 4) }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="asset_symbol" id="selectedAssetSymbol" required>
            </div>

            <!-- Order Form -->
            <div class="order-form p-3">
                <div class="d-flex gap-1">
                    <button type="submit" class="btnorder btn-danger" formaction="{{route('order.store',['type' => 2])}}">
                        <span class="d-flex flex-column align-items-center">
                            <strong class="sellPrice">{{$asset->bid_price}}</strong>
                            <span>{{ __('web.sell') }}</span>
                        </span>
                    </button>
                    <div class="input-group">
                        <button type="button" class="btnminus" onclick="changeAmount(-0.01)">−</button>
                            <input type="number" id="tradeAmount" name="amount" min="0.01" step="0.01" value="0.01" class="amount" readonly/>
                        <button type="button" class="btnplus" onclick="changeAmount(0.01)">+</button>
                    </div>
                    <button type="submit" class="btnorder btn-success" formaction="{{route('order.store',['type' => 1])}}">
                        <span class="d-flex flex-column align-items-center">
                            <strong class="buyPrice">{{$asset->ask_price}}</strong>
                            <span>{{ __('web.buy') }}</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Tabs and Account Summary Row -->
        <div class="details-panel">
            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center">
                <ul class="nav nav-tabs border-0 mb-0" id="tradeTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#orders"    role="tab">Orders     </a></li>
                    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#history"   role="tab">History    </a></li>
                    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#positions" role="tab">Positions  </a></li>
                    <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#summary"   role="tab">Summary    </a></li>
                </ul>
                <div class="account-summary-inline d-flex flex-wrap">
                    <div><span class="text-secondary">Balance :</span> <span class="text-light">${{ number_format($finance['balance'], 2) }}</span></div>
                    <div><span class="text-secondary">Margin :</span> <span class="text-light">${{ number_format($finance['freeMargin'], 2) }}</span></div>
                    <div><span class="text-secondary">Equity : </span> <span class="text-light">${{ number_format($finance['equity'], 2) }} </span></div>
                    <div><span class="text-secondary">Credit : </span> <span class="text-light">${{ number_format($finance['credit'], 2) }} </span></div>
                    <div><span class="text-secondary">Bonus :  </span> <span class="text-light">${{ number_format($finance['bonus'], 2) }}  </span></div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="orders" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Entry / Market</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Created at</th>
                                <th>Profit &amp; Loss</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->asset->name }}</td>
                                        <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                        <td>{{ number_format($order->amount, 2) }}</td>
                                        <td>{{ number_format($order->open_price, 5) }}</td>
                                        <td>-{{ $order->s_l ?? '-' }}</td>
                                        <td>{{ $order->s_p ?? '-' }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                        <td class="pnl @if($order->closed_at == null && $order->status == 'active' && $order->pnl) active_pnl @endif" data-order-id="{{$order->id}}">
                                            <div class="{{$order->pnl < 0 ? 'text-danger' : 'text-success'}}">
                                                {{ number_format($order->pnl, 2) }}
                                            </div>
                                        </td>
                                        @if (!$order->closed_at)
                                            @if ($order->status == 'active')
                                                <td>
                                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                                        <form action="{{ route('order.close', ['id'=>$order->id]) }}" class="d-none" method="POST" id="closeOrderForm{{ $order->id }}">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-primary btn-sm edit_order" formaction="{{ route('order.update', $order->id) }}" data-sl="{{ $order->s_l }}" data-sp="{{ $order->s_p }}" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                        <i class="bi bi-pencil-square" style="color: #fff;"></i>
                                                    </button>
                                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                                        <button type="submit" class="btn btn-danger btn-sm" form="closeOrderForm{{ $order->id }}" style="font-size: 11.6px;">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    <form action="{{ route('order.delete', ['id'=>$order->id]) }}" class="d-none" method="POST" id="closeOrderForm{{ $order->id }}">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    <button type="submit" class="btn btn-danger btn-sm" form="closeOrderForm{{ $order->id }}" style="font-size: 11.6px;">{{__('web.delete')}}</button>
                                                </td>
                                            @endif
                                        @else
                                            <td>{{ date('d/m/Y H:i', strtotime($order->closed_at)) }}</td>
                                        @endif
                                    </tr>
                                @empty
                                <tr>
                                    @if ($tabId == 'pendingOrders' || $tabId == 'openedOrder')
                                        <td colspan="12" class="text-center text-danger">
                                            {{ __('web.no_orders_found') }}
                                        </td>
                                    @else
                                        <td colspan="11" class="text-center text-danger">
                                            {{ __('web.no_orders_found') }}
                                        </td>
                                    @endif
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="text-center text-muted py-5">No history yet.</div>
                </div>
                <div class="tab-pane fade" id="positions" role="tabpanel">
                    <div class="text-center text-muted py-5">No positions yet.</div>
                </div>
                <div class="tab-pane fade" id="summary" role="tabpanel">
                    <div class="text-center text-muted py-5">Summary coming soon.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>

<script>
    var client_id = {{auth()->guard('client')->user()->id}};
    var assetId = {{$asset->id}};
</script>

<script>
    document.getElementById('orderSizeRange').addEventListener('input', function () {
        // Optional dynamic amount logic
    });

    function submitOrder(side) {
        const form = document.getElementById('tradeOrderForm');
        const type = form.order_type.value;
        const price = form.price.value;
        const amount = form.amount.value;
        alert(`Order: ${side.toUpperCase()} | Type: ${type} | Price: ${price} | Amount: ${amount}`);
    }

    // Highlight selected asset and set hidden input
    document.querySelectorAll('.asset-item').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.asset-item').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('selectedAssetSymbol').value = btn.getAttribute('data-symbol');
            // Optionally auto-fill price field with bid or ask
            document.querySelector('input[name="price"]').value = btn.getAttribute('data-bid');
        });
    });
</script>

<script>
    function changeAmount(amount) {
        const input = document.getElementById('tradeAmount');
        let current = parseFloat(input.value) || 0;
        current = Math.max(0.01, (current + amount).toFixed(2));
        input.value = current;
    }
</script>

<script>
    const contextMenu = document.getElementById('customContextMenu');
    const addBtn = document.getElementById('addToFavouriteBtn');
    const goBtn = document.getElementById('goToAssetBtn');
    const removeBtn = document.getElementById('removeFromFavouriteBtn');

    let selectedAsset = null;

    function showStar(button) {
        const nameSpan = button.querySelector('.name');
        if (!nameSpan.querySelector('.star-icon')) {
            const star = document.createElement('span');
            star.classList.add('star-icon');
            star.textContent = '★';
            star.style.color = 'gold';
            star.style.marginLeft = '6px';
            nameSpan.appendChild(star);
        }
    }

    function removeStar(button) {
        const star = button.querySelector('.star-icon');
        if (star) star.remove();
    }

    document.querySelectorAll('.asset-item').forEach(button => {
        button.addEventListener('contextmenu', e => {
            e.preventDefault();
            selectedAsset = button;

            const hasStar = !!selectedAsset.querySelector('.star-icon');
            contextMenu.style.top = `${e.pageY}px`;
            contextMenu.style.left = `${e.pageX}px`;
            contextMenu.style.display = 'block';

            addBtn.style.display = hasStar ? 'none' : 'block';
            removeBtn.style.display = hasStar ? 'block' : 'none';
        });
    });

    addBtn.addEventListener('click', () => {
        if (!selectedAsset) return;
        const assetId = selectedAsset.getAttribute('data-id');

        fetch("{{ route('toggle.favourite') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ asset_id: assetId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                showStar(selectedAsset);
            }
            contextMenu.style.display = 'none';
        });
    });

    removeBtn.addEventListener('click', () => {
        if (!selectedAsset) return;
        const assetId = selectedAsset.getAttribute('data-id');

        fetch("{{ route('toggle.favourite') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ asset_id: assetId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                removeStar(selectedAsset);
            }
            contextMenu.style.display = 'none';
        });
    });

    goBtn.addEventListener('click', () => {
        if (!selectedAsset) return;
        const url = selectedAsset.getAttribute('data-url');
        if (url) {
            window.location.href = url;
        }
        contextMenu.style.display = 'none';
    });

    window.addEventListener('click', () => {
        contextMenu.style.display = 'none';
    });
</script>

<script>
    const assetSearch = document.getElementById('assetSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const showFavouritesBtn = document.getElementById('showFavouritesBtn');
    const assetGrid = document.getElementById('assetGrid');
    let showFavouritesOnly = false;

    let favourites = JSON.parse(localStorage.getItem('favourites')) || [];

    function filterAssets() {
        const search = assetSearch.value.trim().toLowerCase();
        const category = categoryFilter.value;
        document.querySelectorAll('.asset-item').forEach(btn => {
            const name = btn.querySelector('.name').textContent.toLowerCase();
            const symbol = btn.getAttribute('data-symbol').toLowerCase();
            const assetCat = btn.getAttribute('data-category');
            const isFav = favourites.includes(btn.getAttribute('data-symbol'));
            let visible = true;

            if (showFavouritesOnly && !isFav) visible = false;
            if (category !== 'all' && assetCat !== category) visible = false;
            if (search && !name.includes(search) && !symbol.includes(search)) visible = false;

            btn.style.display = visible ? '' : 'none';
        });
    }

    assetSearch.addEventListener('input', filterAssets);
    categoryFilter.addEventListener('change', filterAssets);

    showFavouritesBtn.addEventListener('click', function() {
        showFavouritesOnly = !showFavouritesOnly;
        showFavouritesBtn.classList.toggle('active', showFavouritesOnly);
        showFavouritesBtn.style.color = showFavouritesOnly ? '#ffd700' : '#4f8cff';
        filterAssets();
    });

    filterAssets();

    window.addEventListener('storage', function(e) {
        if (e.key === 'favourites') {
            favourites = JSON.parse(localStorage.getItem('favourites')) || [];
            filterAssets();
        }
    });

    document.addEventListener('favouritesChanged', filterAssets);
</script>
</body> 
</html>
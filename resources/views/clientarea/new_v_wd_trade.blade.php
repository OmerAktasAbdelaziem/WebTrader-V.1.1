<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Trading Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #181d23;
            color: #e0e0e0;
            font-family: 'Segoe UI', 'Inter', Arial, sans-serif;
        }
        .sidebar {
            background: #181d23;
            min-height: 100vh;
            width: 90px;
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
            margin-left: 90px;
            padding: 2.5rem 2vw 2rem 2vw;
            min-height: 100vh;
        }
        .panel {
            background: #23272f;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 2rem 1.5rem 2rem;
            margin-bottom: 2.5rem;
            border: 1px solid #23272f;
        }
        .tv-widget-container {
            height: 55vh;
            min-height: 340px;
            border-radius: 12px;
            overflow: hidden;
            background: #181d23;
            box-shadow: 0 2px 12px #0002;
        }
        .nav-tabs {
            border-bottom: none;
            gap: 0.5rem;
        }
        .nav-tabs .nav-link {
            color: #b0b8c1;
            border: none;
            background: transparent;
            border-radius: 8px 8px 0 0;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background 0.2s, color 0.2s;
            padding: 0.7rem 1.5rem;
        }
        .nav-tabs .nav-link.active {
            color: #fff;
            background: #181d23;
            border-bottom: 3px solid #4f8cff;
        }
        .account-summary-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 2.2rem;
            align-items: center;
            background: #23272f;
            padding: 1rem 2rem;
            margin-top: 1.2rem;
            margin-bottom: 1.2rem;
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
        .order-form .form-label {
            color: #b0b8c1;
            font-weight: 500;
        }
        .order-buttons .btn {
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
        }
        .order-form input, .order-form select {
            background: #181d23;
            color: #e0e0e0;
            border: 1px solid #23272f;
            border-radius: 8px;
        }
        .order-form input:focus, .order-form select:focus {
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.15rem #4f8cff33;
        }
        .order-form .form-range {
            accent-color: #4f8cff;
        }
        .order-form .form-range::-webkit-slider-thumb {
            background: #4f8cff;
        }
        .order-form .form-range::-moz-range-thumb {
            background: #4f8cff;
        }
        .order-form .form-range::-ms-thumb {
            background: #4f8cff;
        }
        .order-form .form-range:focus {
            outline: none;
        }
        @media (max-width: 991px) {
            .sidebar {
                position: static;
                width: 100%;
                flex-direction: row;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #23272f;
                box-shadow: none;
                padding: 1rem 0 1rem 0;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem 2vw;
            }
            .panel {
                padding: 1.2rem 1rem 1rem 1rem;
            }
            .account-summary-inline {
                flex-direction: column;
                gap: 0.7rem;
                align-items: flex-start;
                padding: 1rem 1rem;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <i class="bi bi-house nav-icon active" title="Dashboard" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-bar-chart nav-icon" title="Markets" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-person nav-icon" title="Account" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-gear nav-icon" title="Settings" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-box-arrow-right nav-icon logout" title="Logout" style="font-size:1.2rem; padding:0.3rem;"></i>
</div>

<div class="main-content">
    <div class="row g-4">
        <!-- Chart & Tabs -->
        <div class="col-lg-9">
            <div class="panel mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 fw-bold" style="letter-spacing:0.5px;">{{ $symbol }}</h5>
                    <span class="badge text-danger border border-danger" style="font-size:1rem; border-radius:8px; background:transparent;">Live</span>
                </div>

                <!-- TradingView Widget -->
                <div class="tv-widget-container mb-3">
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                        "autosize": true,
                        "symbol": "{{ $symbol }}",
                        "interval": "1440",
                        "timezone": "Etc/UTC",
                        "theme": "dark",
                        "style": "1",
                        "locale": "en",
                        "allow_symbol_change": false,
                        "support_host": "https://www.tradingview.com"
                    }
                    </script>
                </div>

                <!-- Tabs and Account Summary Row -->
                    <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center mb-2 gap-3">
                        <ul class="nav nav-tabs border-0 mb-0" id="tradeTabs" role="tablist" style="font-size:0.85rem;">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#orders"    role="tab">Open Orders</a></li>
                            <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#history"   role="tab">History    </a></li>
                            <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#positions" role="tab">Positions  </a></li>
                            <li class="nav-item"><a class="nav-link"        data-bs-toggle="tab" href="#summary"   role="tab">Summary    </a></li>
                        </ul>
                        <div class="account-summary-inline d-flex flex-wrap gap-4">
                            <div><span class="text-secondary">Balance :</span> <span class="text-light">${{ number_format($finance['balance'], 2) }}</span></div>
                            <div><span class="text-secondary">Margin :</span> <span class="text-light">${{ number_format($finance['freeMargin'], 2) }}</span></div>
                            <div><span class="text-secondary">Equity : </span> <span class="text-light">${{ number_format($finance['equity'], 2) }} </span></div>
                            <div><span class="text-secondary">Credit : </span> <span class="text-light">${{ number_format($finance['credit'], 2) }} </span></div>
                            <div><span class="text-secondary">Bonus :  </span> <span class="text-light">${{ number_format($finance['bonus'], 2) }}  </span></div>
                        </div>
                    </div>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="orders" role="tabpanel">
                            <div class="table-responsive rounded-3 shadow-sm">
                                <table class="table table-dark table-sm align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>Instrument</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Entry / Market</th>
                                        <th>Stop Loss</th>
                                        <th>Take Profit</th>
                                        <th>Margin</th>
                                        <th>Created at</th>
                                        <th>Profit &amp; Loss</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>XAUUSD</td>
                                        <td><strong class="text-success">Buy</strong></td>
                                        <td>0.01</td>
                                        <td>3371.90</td>
                                        <td>3350.00</td>
                                        <td>3400.00</td>
                                        <td>$10.00</td>
                                        <td>2024-06-01 12:34</td>
                                        <td class="text-success fw-bold">+1.20</td>
                                        <td>
                                            <button class="btn btn-sm rounded-3" title="Close" style="border-color: #c2c2c2; color: #c2c2c2; background: transparent;">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                            <button class="btn btn-sm rounded-3 ms-1" title="Edit" style="border-color: #c2c2c2; color: #c2c2c2; background: transparent;">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- More rows dynamically -->
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
                <!-- End of Tabs and Account Summary Row -->
            </div>
        </div>

        <!-- Right Side Panel -->
        <div class="col-lg-3">
            <div class="panel order-form shadow-sm">
                <h6 class="mb-3 fw-bold">Trade Order</h6>
                <form id="tradeOrderForm" autocomplete="off">
                    <div class="mb-3">
                        <div class="d-grid gap-2" id="assetGrid" style="max-height: 355px; overflow-y: auto;">
                            <div class="row fw-bold text-secondary mb-2" style="font-size: 1rem;">
                                <div class="col-5">Market</div>
                                <div class="col-2 text-center">Bid</div>
                                <div class="col-2 text-center" style="padding-left: 50px;">Ask</div>
                            </div>
                            @foreach($assetsPrices as $asset)
                                <button type="button"class="row align-items-center asset-item mb-2" data-symbol="{{ $asset->symbol }}" data-bid="{{ $asset->bid_price }}" data-ask="{{ $asset->ask_price }}" style="background:#181d23; color:#e0e0e0; border:10px solid #23272f; border-radius:20px;">
                                    <div class="col-5 text-start">
                                        <strong>{{ $asset->name }}</strong>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}} </span>
                                    </div>
                                    <div class="col-2 text-end" style="padding-right:70px;">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->ask_price}} </span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="asset_symbol" id="selectedAssetSymbol" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Type</label>
                        <select class="form-select" name="order_type">
                            <option value="market">Market</option>
                            <option value="limit">Limit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-control" name="price" placeholder="Enter price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="Enter amount">
                    </div>
                    <div class="d-flex order-buttons gap-2 my-4">
                        <button type="button" class="btn btn-danger w-50" onclick="submitOrder('sell')">Sell</button>
                        <button type="button" class="btn btn-success w-50" onclick="submitOrder('buy')">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body> 
</html>
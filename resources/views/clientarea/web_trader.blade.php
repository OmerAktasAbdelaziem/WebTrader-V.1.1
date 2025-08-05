<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebTrader</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v1.599') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v1.599') }}">

<style>
    body {
        background-color: #f8f9fa;
        overflow: hidden;
    }
    .container-fluid {
        display: flex;
        height: calc(100vh - 50px); 
    }
    .topbar {
        height: 50px;
        background: #ededed;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 15px;
    }
    .sidebar {
        width: 250px;
        min-width: 400px;
        transition: width 0.2s ease-in-out;
        overflow-x: hidden;
        background: #EDEDED;
        color: white;
        padding: 10px;
    }
    .sidebar.collapsed {
        width: 150px;
    }
    .sidebar .nav-link {
        white-space: nowrap;
    }
    .resizer {
        width: 5px;
        background: #aaa;
        cursor: ew-resize;
    }
    .main-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .chart-container {
        flex-grow: 1;
        background: white;
        padding: 10px;
        overflow: auto;
    }
    .resizer-horizontal {
        height: 5px;
        background: #aaa;
        cursor: ns-resize;
    }
    .table-container {
        height: 250px;
        overflow: auto;
    }
    .dropdown-menu {
        width: 300px;
    }
    .dropdown-menu .dropdown-header {
        font-weight: bold;
    }
    .dropdown-menu .dropdown-divider {
        margin: 0;
    }
    
    .nav-tabs {
        border-bottom: none;
    }
    
    .nav-tabs .nav-item {
        margin: 0 5px;
    }
    
    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #4699D9;
        border-bottom: 2px solid #4699D9;
    }
    .nav-tabs .nav-link {
        font-size: 12px;
        line-height: 25px;
        padding: 2px 35px;
        border: none;
        border-radius: 0;
        color: #000;
        background-color: #CCCCCC;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #007bff;
        background-color: #f8f9fa;
        border-bottom: 2px solid #4699D9;
    }

    .nav{
        flex-wrap: nowrap;
        border
    }

    .quick-trading {
        position: absolute;
        top: 55px;
        left: 50%;
        transform: translateX(-50%);
        background: #a3a1a17b;
        padding: 8px 8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .quick-trading a {
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
    }

    .button4 {
        background-color: red;
    }

    .button3 {
        background-color: green;
    }

    .formset {
        display: flex;
        align-items: center;
        background: white;
        padding: 5px;
        border-radius: 5px;
    }

    .btn-counter {
        background: #ddd;
        color: black;
        padding: 5px 10px;
        border-radius: 3px;
        font-weight: bold;
        cursor: pointer;
        user-select: none;
    }

    .formset input {
        width: 50px;
        text-align: center;
        font-size: 16px;
        border: none;
        outline: none;
        margin: 0 5px;
    }

    .search-bar input {
        border: none;
        background-color: white;
        width: 100%;
        padding: 5px;
    }

    .category {
        padding: 10px;
        background-color: #ededed;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .category:hover {
        background-color: #ddd;
    }

    .currency-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .currency-list li {
        padding: 8px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .currency-list li:hover {
        background-color: #f8f9fa;
    }

    .currency-list li span {
        font-size: 14px;
    }

    .nav-link.text-warning {
        padding: 8px 15px;
        border-bottom: 1px solid #eee;
    }

    .nav-link.text-warning:hover {
        background-color: #f8f9fa;
    }

    .footer {
        background-color: #222;
        color: white;
        text-align: center;
        padding: 20px 0;
        position: relative;
        bottom: 0;
        width: 100%;
    }

    .footer a {
        color: #f8b400;
        text-decoration: none;
        margin: 0 15px;
        font-weight: bold;
    }

    .footer a:hover {
        color: #fff;
    }

    .footer p {
        margin: 10px 0 0;
        font-size: 14px;
    }
    .tradingview-widget-container {
        height: 70% !important;
    }
</style>

</head>

<!-- Top Bar -->
<div class="topbar d-flex justify-content-between align-items-center p-2">
    <button class="btn btn-outline-dark me-2" id="resetPasswordBtn" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
        <span class="iconify" data-icon="solar:key-broken" data-inline="false"></span>
        {{__('web.reset_password')}}
    </button>

        @if(Auth::guard('client')->check())
            <span class="fw-bold text-black">{{__('web.welcome')}}, {{ Auth::guard('client')->user()->first_name }} {{ Auth::guard('client')->user()->last_name }}</span>
        @else
            <span class="fw-bold text-black">{{__('web.welcome')}}, {{__('web.guest')}}</span>
        @endif
        <span class="text-black">{{__('web.equity')}} : <span class="equity">$ {{number_format((floor($finance['equity'] * 100) / 100), '2','.',',')}}</span></span>
        <span class="text-black">{{__('web.credit')}} : <span>$ {{$finance['credit']}}</span></span>
        <span class="text-black">{{__('web.balance')}} : <span>$ {{number_format((floor($finance['balance'] * 100) / 100), '2','.',',')}}</span></span>
        <span class="text-black">{{__('web.bonus')}} : <span>$ {{number_format((floor($finance['bonus'] * 100) / 100), '2','.',',')}}</span></span>
        <div class="d-flex align-items-center">
            @isset(auth()->guard('client')->user()->options['enableDepositRequest'])
                <button class="btn btn-success w-100 me-2" data-bs-toggle="modal" data-bs-target="#depositModal">{{__('web.deposit')}}</button>
            @endisset
            @isset(auth()->guard('client')->user()->options['enableWithdrawalRequest'])
                <button class="btn btn-success w-100 me-2" data-bs-toggle="modal" data-bs-target="#withdrawModal">{{__('web.withdraw')}}</button>
            @endisset
        <div class="dropdown me-2">
            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i> <span class="d-none d-md-inline">
                    @if (auth()->guard('client')->user()->account_type == 'Real')
                        {{__('web.real_account')}}
                    @else
                        {{__('web.demo_account')}}
                    @endif
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="accountDropdown" style="min-width: 400px;">
                <div class="d-flex justify-content-between">
                    <div class="w-50 pe-3">
                        <h6 class="fw-bold">
                            @if (auth()->guard('client')->user()->account_type == 'Real')
                                {{__('web.real_account')}}
                            @else
                                {{__('web.demo_account')}}
                            @endif
                        </h6>
                        <p class="mb-1">{{__('web.id')}} : {{ Auth::guard('client')->user()->id }}</p>
                        <p class="mb-1">{{__('web.balance')}} : <span class="text-success">$ {{number_format($finance['balance'], '2','.',',')}}</span></p>
                        <p class="mb-1">{{__('web.profitloss')}} : <span class="text-danger currentPL">$ {{$finance['currentPL']}}</span></p>
                    </div>
                    <div class="vr"></div>
                    <div class="w-50 ps-3">
                        <h6 class="fw-bold">{{__('web.trading_information')}}</h6>
                        <p class="mb-1">{{__('web.currency')}} : USD</p>
                        <p class="mb-1">{{__('web.leverage')}} : {{auth()->guard('client')->user()->leverage??'1:500'}}</p>
                        <form action="{{ route('client.logout') }}" method="POST" class="d-none" id="formLogout"></form>
                        <button type="submit" form="formLogout" class="text-gray-700 hover:bg-gray-100">{{__('web.logout')}}</button>
                    </div>
                </div>
            </ul>
        </div>

        
        
        <!-- Language Select -->
        <div class="nav-item dropdown d-flex align-items-center me-2">
            <div class="d-flex justify-content-center align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret cursor-pointer border border-dark rounded text-dark" data-bs-toggle="dropdown" style="padding: 11px 12px;">
                <img class="mx-2" src="{{ config('app.flagIconUrlForLocale.' . app()->getLocale()) }}" width="25" alt="flag icon">
            </div>                
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach(['en'=>'English','ar'=>'العربية'] as $language => $name)
                    <li>
                        <a class="dropdown-item my-2" href="{{ switchUrlLocaleTo($language) }}">
                            <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" alt="flag icon">
                            <span class="ms-2">{{ $name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Notification Button with Dropdown -->
        <div class="d-flex align-items-center me-2  ">
            <button class="btn btn-outline-dark me-2 dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="iconify" data-icon="line-md:bell-loop" data-inline="false"></span>
            </button>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end p-3 fw-bold" aria-labelledby="notificationDropdown" style="min-width: 500px;background-color: #cccccc">
                @if ($notifications->count() == 0)
                    <li>
                        <h6 class="dropdown-header px-0 fw-bold">{{__('web.notifications')}}</h6>
                        <p class="text-center text-muted mb-2 px-0 fw-bold">{{__('web.no_notification')}}</p>
                    </li>
                @else
                    <li>
                        <h6 class="dropdown-header px-0 fw-bold">{{__('web.notifications')}}</h6>
                    </li>
                    @foreach ($notifications as $notification)
                        <li>
                            <div class="row">
                                <div class="col-6">
                                    {{__('web.'.$notification->text)}}
                                </div>
                                <div class="col-6 text-end">
                                    {{date('d/m H:i', strtotime($notification->created_at))}}
                                </div>
                            </div>
                        </li>
                        <hr class="p-0 m-0">
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('fail'))
            <div class="alert alert-danger">
                {{ session('fail') }}
            </div>
        @endif
    </div>
</div>

<div class="container-fluid">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs d-flex justify-content-center w-150 ms-3">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#allPairs" style="border-radius: 0;">{{__('web.all_pairs')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#favorites" style="border-radius: 0;">{{__('web.favorites')}}</a>
            </li>
        </ul>
        <div class="search-bar p-2 d-flex justify-content-between align-items-center">
            <!-- Search Bar -->
            <input type="text" class="form-control me-2 search" placeholder="{{__('web.search')}}">
        </div>

        <!-- Quotes Header -->
        <div class="quotes-header p-2 d-flex justify-content-between align-items-center">
            <span class="text-black" style="font-size: 12px;">{{__('web.instrument')}}</span>
            <span class="text-black" style="font-size: 12px;">{{__('web.sell')}}</span>
            <span class="text-black" style="font-size: 12px;">{{__('web.buy')}}</span>
        </div>
        <hr class="text-dark m-0">
        <!-- Tabs Content -->
        <div class="tab-content">
            <!-- All Pairs Tab -->
            <div class="tab-pane fade show active" id="allPairs">
                <!-- Forex Section -->
                <div class="nav-item">
                    <a href="#" class="nav-link text-dark category align-items-center">
                        <div class="d-flex justify-content-between">
                            <div>{{__('web.forex')}} </div>
                            <div>
                                ▼
                            </div>
                        </div>
                    </a>
                    <ul class="currency-list text-dark" style="display: none;">
                        @foreach ($assetsPrices as $assetPrice)
                            @if ($assetPrice->category == 'Forex')
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="bid_price text-danger" data-asset-id="{{$assetPrice->id}}" class="bid_price" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}}</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->ask_price}} </span>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link text-dark category align-items-center">
                        <div class="d-flex justify-content-between">
                            <div>{{__('web.crypto')}} </div>
                            <div>
                                ▼
                            </div>
                        </div>
                    </a>
                    <ul class="currency-list text-dark" style="display: none;">
                        @foreach ($assetsPrices as $assetPrice)
                            @if ($assetPrice->category == 'Crypto')
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="bid_price text-danger" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}}</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->ask_price}}</span>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link text-dark category align-items-center">
                        <div class="d-flex justify-content-between">
                            <div>{{__('web.stock')}} </div>
                            <div>
                                ▼
                            </div>
                        </div>
                    </a>
                    <ul class="currency-list text-dark" style="display: none;">
                        @foreach ($assetsPrices as $assetPrice)
                            @if ($assetPrice->category == 'Stocks')
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="bid_price text-danger" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}} </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}"> {{$assetPrice->ask_price}} </span>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
        
                <!-- Indices Section -->
                <div class="nav-item">
                    <a href="#" class="nav-link text-dark category align-items-center">
                        <div class="d-flex justify-content-between">
                            <div>{{__('web.indices')}} </div>
                            <div>
                                ▼
                            </div>
                        </div>
                    </a>
                    <ul class="currency-list text-dark" style="display: none;">
                        @foreach ($assetsPrices as $assetPrice)
                            @if ($assetPrice->category == 'Indx')
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="bid_price text-danger" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}} </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}"> {{$assetPrice->ask_price}} </span>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="nav-item">
                    <a href="#" class="nav-link text-dark category align-items-center">
                        <div class="d-flex justify-content-between">
                            <div>{{__('web.commodity')}} </div>
                            <div>
                                ▼
                            </div>
                        </div>
                    </a>
                    <ul class="currency-list text-dark" style="display: none;">
                        @foreach ($assetsPrices as $assetPrice)
                            @if ($assetPrice->category == 'Commodity')
                                <li class="d-flex justify-content-between align-items-center">
                                    <div class="text-start">
                                        <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                        </a>
                                        <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                                    </div>
                                    <div class="text-center">
                                        <span class="bid_price text-danger" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}} </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="ask_price text-success" data-asset-id="{{$assetPrice->id}}"> {{$assetPrice->ask_price}} </span>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <!-- Crypto Section -->
            </div>
        
            <!-- Favorites Tab -->
            <div class="tab-pane fade" id="favorites">
                <ul class="nav flex-column">
                    @foreach ($favourite_assets as $assetPrice)
                        <li class="d-flex justify-content-between align-items-center text-dark">
                            <div class="text-start">
                                <a href="{{route('toggle.favourite',$assetPrice->id)}}" style="text-decoration: none;">
                                    <i class="fas fa-star @if (in_array($assetPrice->id, $favourite_assets_ids)) text-warning @else text-secondary @endif"></i>
                                </a>
                                <a href="{{route('client.webtrader',['symbol' => $assetPrice->symbol])}}" class="name text-dark" style="text-decoration: none">{{$assetPrice->name}}</a>
                            </div>
                            <div class="text-center">
                                <span class="bid_price" data-asset-id="{{$assetPrice->id}}">{{$assetPrice->bid_price}} </span>
                            </div>
                            <div class="text-end">
                                <span class="ask_price" data-asset-id="{{$assetPrice->id}}"> {{$assetPrice->ask_price}} </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- End Sidebar -->
    <div class="resizer" id="sidebarResizer"></div>
    <div class="main-content">
        <div class="resizer-horizontal" id="chartResizer"></div>
        <div class="chart-container" id="chartContainer" style="position: relative;">
            <div class="tradingview-widget-container">
                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                    "autosize": false,
                    "symbol": "{{$symbol}}",
                    "interval": "60",
                    "timezone": "Etc/UTC",
                    "theme": "light",
                    "style": "1",
                    "locale": "en",
                    "withdateranges": false,
                    "hide_side_toolbar": false,
                    "allow_symbol_change": false,
                    "details": false,
                    "hotlist": false,
                    "calendar": false,
                    "support_host": "https://www.tradingview.com"
                    }
                </script>
            </div>
        
            <!-- Quick Trading Floating -->
            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                <div class="quick-trading">
                    <form method="POST" class="form form2 ng-untouched ng-pristine ng-valid d-flex align-items-center m-0">
                        <!-- Sell Button -->
                        <button type="submit" class="btn btn-danger btn-md me-2" formaction="{{route('order.store',['type' => 2])}}">
                            <span>{{__('web.sell')}}<strong class="sellPrice">{{$asset->bid_price}}</strong></span>
                        </button>
                        <!-- Counter Input --> 
                        <div class="formset d-flex align-items-center" style="gap: 5px;">
                            <a class="btn-counter btn-minus" href="#" style="padding: 1px 5px;">-</a>
                            <input name="amount" id="orderAmount" type="text" value="0.01" step="0.01" min="0.01" style="width: 55px; padding: 2px; text-align: center;">
                            <a class="btn-counter btn-plus" href="#" style="padding: 1px 5px;">+</a>
                        </div>

                        <!-- Buy Button -->
                        <button type="submit" class="btn btn-success btn-md ms-2" formaction="{{route('order.store',['type' => 1])}}">
                            <span>{{__('web.buy')}}<strong class="buyPrice">{{$asset->ask_price}} </strong></span>
                        </button>

                        <!-- Navigation Button (Behind Buy Button) -->
                        <button type="button" id="openNavModal" class="btn btn-white btn-md ms-2 d-flex align-items-center justify-content-center">
                            <span class="iconify" data-icon="fluent:navigation-24-filled" data-inline="false"></span>
                        </button>
                        <input type="hidden" class="form-control" name="currency" value="{{$asset->id}}" readonly>
                        <input type="hidden" class="form-control bidInput" name="bid" id="bidQuick" value="{{$asset->bid_price}}" readonly>
                        <input type="hidden" class="form-control askInput" name="ask" id="askQuick" value="{{$asset->ask_price}}" readonly>
                    </form>
                </div>
            @endif
            
            <!-- New Navigation Info Modal -->
            <div id="navInfoModal" class="modal fade" tabindex="-1" aria-labelledby="navInfoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs justify-content-center" id="orderTabs" role="tablist">
                                <li class="nav-item me-2" role="presentation">
                                    <button class="nav-link active" id="market-tab" data-bs-toggle="tab" data-bs-target="#marketOrder" type="button" role="tab">{{__('web.new_market_order')}}</button>
                                </li>
                                <li class="nav-item me-2" role="presentation">
                                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pendingOrder" type="button" role="tab">{{__('web.new_pending_order')}}</button>
                                </li>
                            </ul>
            
                        <!-- New Market Order Tab -->
                        <div class="tab-content mt-3" id="orderTabsContent">
                            <!-- New Market Order -->
                            <div class="tab-pane fade show active" id="marketOrder" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>{{__('web.contract_size')}}: <strong>{{$asset?->size[$asset_group_id]}}</strong></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row g-2">
                                    <form method="POST" id="marketOrderForm" class="d-none">
                                        @csrf
                                    </form>
                                    <div class="col-md-6">
                                        <label for="currency" class="form-label">{{__('web.item')}}</label>
                                        <input type="text" class="form-control" value="{{$asset->name}}" readonly>
                                        <input type="hidden" class="form-control" name="currency" form="marketOrderForm" value="{{$asset->id}}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="amountInput" class="form-label">{{__('web.amount')}}</label>
                                        <input type="number" class="form-control" form="marketOrderForm" id="amountInput" name="amount" min="0.01" step="any" value="0.01">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="stopLossSwitch">
                                            <label class="form-check-label" for="stopLossSwitch">{{__('web.set_stop_loss')}}</label>
                                        </div>
                                        <div id="stopLossContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" form="marketOrderForm" step="any" name="s_l">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitch">
                                            <label class="form-check-label" for="takeProfitSwitch">{{__('web.set_take_profit')}}</label>
                                        </div>
                                        <div id="takeProfitContainer" class="mt-2" style="display: none;">
                                            <input type="number" form="marketOrderForm" class="form-control" step="any" name="s_p">
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" class="form-control bidInput" name="bid" form="marketOrderForm" id="bid" value="{{$asset->bid_price}}" readonly>
                                <input type="hidden" class="form-control askInput" name="ask" form="marketOrderForm" id="ask" value="{{$asset->ask_price}}" readonly>

                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" form="marketOrderForm" formaction="{{route('order.store',['type' => 2])}}" class="btn btn-danger w-100 me-2">{{__('web.sell')}} (<span class="sellPrice">{{$asset->bid_price}}</span>)</button>
                                    <button type="submit" form="marketOrderForm" formaction="{{route('order.store',['type' => 1])}}" class="btn btn-success w-100 ms-2">{{__('web.buy')}} (<span class="buyPrice">{{$asset->ask_price}}</span>)</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pendingOrder" role="tabpanel">
                                <form action="{{ route('order.store') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <label for="currency" class="form-label">{{__('web.item')}}</label>
                                            <input type="text" class="form-control" value="{{$asset->name}}" readonly>
                                            <input type="hidden" class="form-control" name="currency" value="{{$asset->id}}" readonly>
                                        </div>
                                        <div class="col-6">
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
                                            <label for="amountTP" class="form-label">{{__('web.amount')}}</label>
                                            <input type="number" id="amountTP" class="form-control" min="0.01" name="amount" step="any" value="0.01">
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check form-switch d-flex align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" id="stopLossSwitchPending">
                                                <label class="form-check-label" for="stopLossSwitchPending">{{__('web.set_stop_loss')}}</label>
                                            </div>
                                            <div id="stopLossContainerPending" class="mt-2" style="display: none;">
                                                <input type="number" class="form-control" step="any" name="s_l">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check form-switch d-flex align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitchPending">
                                                <label class="form-check-label" for="takeProfitSwitchPending">{{__('web.set_take_profit')}}</label>
                                            </div>
                                            <div id="takeProfitContainerPending" class="mt-2" style="display: none;">
                                                <input type="number" class="form-control" step="any" name="s_p">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">{{ __('web.place_pending_order') }}</button>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control bidInput" name="bid" value="{{$asset->bid_price}}" readonly>
                                    <input type="hidden" class="form-control askInput" name="ask" value="{{$asset->ask_price}}" readonly>
                                </form>
                            </div>
                        </div>

                        <!-- New Pending Order Tab -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="resizer-horizontal" id="chartResizer"></div>
        <ul class="nav nav-tabs d-flex justify-content-center w-150 ms-3 mt-2">
            <li class="nav-item">
                <a class="nav-link @if ($tab == 'openedOrder') active @endif" data-bs-toggle="tab" href="#openedOrder">{{ __('web.open_orders') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ($tab == 'pendingOrders') active @endif" data-bs-toggle="tab" href="#pendingOrders">{{ __('web.pending_orders') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link @if ($tab == 'history') active @endif" data-bs-toggle="tab" href="#history">{{ __('web.history') }}</a>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            @php
                $tabs = [
                    'openedOrder'   => $openOrders,
                    'pendingOrders' => $pendingOrders,
                    'history'       => $closedOrders
                ];
            @endphp
        
            @foreach ($tabs as $tabId => $orders)
                <div class="tab-pane fade {{ ($tab == $tabId) ? 'show active' : '' }}" id="{{ $tabId }}">
                    <div class="table-container">
                        @if ($tabId == 'history')
                            <form class="ajax-form" method="GET" data-tab="trx">
                                <div class="row">
                                    <div class="d-flex col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control from-to-range" id="history_fromTo" placeholder="{{$history_fromTo}}">
                                            <input type="hidden" class="rangeDate" value="{{$history_fromTo}}" name="history_fromTo">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('web.id') }}</th>
                                    <th>{{ __('web.instrument') }}</th>
                                    <th>{{ __('web.type') }}</th>
                                    <th>{{ __('web.amount') }}</th>
                                    <th>{{ __('web.open_rate') }}</th>
                                    <th>{{ __('web.open_time') }}</th>
                                    <th>S/L</th>
                                    <th>T/P</th>
                                    <th>{{ __('web.commission') }}</th>
                                    <th>{{ __('web.profitloss') }}</th>
                                    <th>{{ __('web.margin') }}</th>
                                    @if ($tabId == 'pendingOrders' || $tabId == 'openedOrder')
                                        <th></th>
                                    @else
                                        <th>{{ __('web.close_time') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->asset->name }}</td>
                                        <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                        <td>{{ number_format($order->amount, 2) }}</td>
                                        <td>{{ number_format($order->open_price, 5) }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                        <td>-{{ $order->s_l ?? '-' }}</td>
                                        <td>{{ $order->s_p ?? '-' }}</td>
                                        <td>0</td>
                                        <td class="pnl @if($order->closed_at == null && $order->status == 'active' && $order->pnl) active_pnl @endif" data-order-id="{{$order->id}}">
                                            <div class="{{$order->pnl < 0 ? 'text-danger' : 'text-success'}}">
                                                {{ number_format($order->pnl, 2) }}
                                            </div>
                                        </td>
                                        <td>{{ number_format($order->required_margin, 2) }}</td>
                                        @if (!$order->closed_at)
                                            @if ($order->status == 'active')
                                                <td>
                                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                                        <form action="{{ route('order.close', ['id'=>$order->id]) }}" class="d-none" method="POST" id="closeOrderForm{{ $order->id }}">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-success btn-sm edit_order" formaction="{{ route('order.update', $order->id) }}" data-sl="{{ $order->s_l }}" data-sp="{{ $order->s_p }}" style="font-size: 11.6px;" data-bs-toggle="modal" data-bs-target="#editOrderModal">{{__('web.edit')}}</button>
                                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                                        <button type="submit" class="btn btn-warning btn-sm" form="closeOrderForm{{ $order->id }}" style="font-size: 11.6px;">{{__('web.close')}}</button>
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
                        @if ($tabId == 'history')
                            @include("layouts.table.pagination.footer",['model' => $orders, 'tab' => 'history'])
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-4 text-white" style="background: #F3F4F6; padding: 20px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
                        <h5 class="mb-3 text-dark text-center me-3" style="font-size: 1.25rem;">{{__('web.withdraw')}}</h5>
                        <ul class="nav nav-pills flex-column" id="withdrawTabs">
                            <li class="nav-item">
                                <a class="nav-link active d-flex align-items-center" id="bank-tab" data-bs-toggle="tab" href="#bank-form" style="color: #1EBC74; font-size: 1rem; margin-top: 5px;">
                                    <span class="iconify me-1" data-icon="proicons:bank" data-inline="false"></span> {{__('web.bank')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" id="usdt-tab" data-bs-toggle="tab" href="#usdt-form" style="color: #1EBC74; font-size: 1rem; margin-top: 5px;">
                                    <span class="iconify me-1" data-icon="cryptocurrency:usdt" data-inline="false"></span> {{__('web.usdt')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8 p-4">
                        <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="tab-content">
                            <!-- Bank Form -->
                            <div class="tab-pane fade show active" id="bank-form">
                                <form method="POST" action="{{ route('client.withdraw.submit') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="swift" class="form-label">{{__('web.swift')}} *</label>
                                            <input type="text" class="form-control" id="swift" name="swift" placeholder="{{__('web.enter_swift')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="iban" class="form-label">{{__('web.iban')}} *</label>
                                            <input type="text" class="form-control" id="iban" name="iban" placeholder="{{__('web.enter_iban')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank-name" class="form-label">{{__('web.bank_name')}} *</label>
                                            <input type="text" class="form-control" id="bank-name" name="bank_name" placeholder="{{__('web.enter_bank_name')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bank-country0" class="form-label">{{__('web.bank_country')}} *</label>
                                            <select class="form-select" id="bank-country0" name="bank_country" required>
                                                <option selected>{{__('web.select_country')}}</option>
                                                @foreach (__('web.country_list') as $key => $country)
                                                    <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank-address0" class="form-label">{{__('web.bank_address')}} *</label>
                                            <input type="text" class="form-control" id="bank-address0" name="bank_address" placeholder="{{__('web.enter_bank_address')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-name" class="form-label">{{__('web.beneficiary_name')}} *</label>
                                            <input type="text" class="form-control" id="beneficiary-name" name="beneficiary_name" placeholder="{{__('web.enter_beneficiary_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-country" class="form-label">{{__('web.beneficiary_country')}} *</label>
                                            <select class="form-select" id="beneficiary-country" name="beneficiary_country" required>
                                                <option selected>{{__('web.select_country')}}</option>
                                                @foreach (__('web.country_list') as $key => $country)
                                                    <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-address" class="form-label">{{__('web.beneficiary_address')}} *</label>
                                            <input type="text" class="form-control" id="beneficiary-address" name="beneficiary_address" placeholder="{{__('web.enter_beneficiary_address')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="currency0" class="form-label">{{__('web.currency')}} *</label>
                                            <input type="text" class="form-control" id="currency0" name="currency" value="USD" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="amount" class="form-label">{{__('web.amount')}} *</label>
                                            <input type="number" class="form-control" id="amount" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="aba-routing-number" class="form-label">{{__('web.aba_routing_number')}} *</label>
                                            <input type="text" class="form-control" id="aba-routing-number" name="aba_routing_number" placeholder="{{__('web.enter_aba_routing_number')}}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">{{__('web.confirm')}}</button>
                                </form>
                            </div>

                            <!-- USDT Form -->
                            <div class="tab-pane fade" id="usdt-form">
                                <form method="POST" action="{{ route('client.withdraw.submit') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="currency" class="form-label">{{__('web.currency')}} *</label>
                                        <input type="text" class="form-control" name="currency" value="USD" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="usdt" class="form-label">{{__('web.please_specify_usdt_address')}} *</label>
                                        <input type="text" class="form-control" id="usdt" name="usdt" placeholder="{{__('web.enter_usdt_address')}}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amount2" class="form-label">{{__('web.amount')}} *</label>
                                        <input type="number" class="form-control" id="amount2" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">{{__('web.confirm')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">{{__('web.update_order')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2">
                <form class="d-none" id="editOrderForm" method="POST">
                    @csrf
                    @method('PUT')
                </form>
                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="editSp" class="form-label">{{__('web.tp')}}</label>
                        <input type="number" class="form-control" form="editOrderForm" name="s_p" step="any" id="editSp">
                    </div>
                    <div class="col-md-6">
                        <label for="editSl" class="form-label">{{__('web.sl')}}</label>
                        <input type="number" class="form-control" form="editOrderForm" name="s_l" step="any" id="editSl">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success" form="editOrderForm">{{__('web.update_order')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1000px;">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="depositModalLabel">{{__('web.deposit')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="stepper1" class="bs-stepper linear">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step active" data-target="#step-1">
                            <button type="button" class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="step-1">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">{{__('web.choose_payment_method')}}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step-2">
                            <button type="button" class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="step-2">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">{{__('web.payment_details')}}</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#step-3">
                            <button type="button" class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="step-3">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">{{__('web.upload_receipt')}}</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <form id="depositForm" method="POST" action="{{ route('deposit.process') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1: Payment Method Selection -->
                            <div id="step-1" role="tabpanel" class="bs-stepper-pane active dstepper-active" aria-labelledby="stepper1trigger1" style="display: block;">
                                <div class="mb-3">
                                    <label for="payment-method" class="form-label">{{__('web.payment_method')}} *</label>
                                    <select class="form-select" id="payment-method" name="payment_method" required>
                                        <option selected>{{__('web.choose_payment_method')}}</option>
                                        <option value="usdt">USDT</option>
                                        <option value="bank">Bank Transfer</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary" id="step1NextBtn">{{__('web.next')}}</button>
                            </div>

                            <!-- Step 2: Payment Details -->
                            <div id="step-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                <!-- USDT Details -->
                                <div id="usdt-details" class="payment-details" style="display: none;">
                                    <div class="mb-3">
                                        <label for="usdt-address" class="form-label">{{__('web.usdt_address')}}</label>
                                        @if (auth()->guard('client')->user()->source == 'BNC')
                                            <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{auth()->guard('client')->user()->usdt??auth()->guard('client')->user()->pipeline->usdt['BNC']??''}}" readonly>
                                        @else
                                            <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{auth()->guard('client')->user()->usdt??auth()->guard('client')->user()->pipeline->usdt['phoenix']??''}}" readonly>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bank Transfer Details -->
                                <div id="bank-details" class="payment-details" style="display: none;">
                                    <div class="mb-3">
                                        <label for="bank-country" class="form-label">{{__('web.bank_country')}} *</label>
                                        <select class="form-select" id="bank-country" name="country">
                                            <option selected>{{__('web.select_country')}}</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bank-name0" class="form-label">{{__('web.select_bank')}} *</label>
                                        <select class="form-select" id="bank-name0" name="bank">
                                            <option selected>{{__('web.select_bank')}}</option>
                                            @foreach($banks as $bank)
                                                <option 
                                                    data-country="{{ $bank->country }}" 
                                                    data-address="{{ $bank->address }}" 
                                                    data-swift="{{ $bank->swift_code }}" 
                                                    data-iban="{{ $bank->iban }}" 
                                                    data-account="{{ $bank->account_number }}" 
                                                    data-beneficiary-name="{{ $bank->beneficiary_name }}" 
                                                    data-beneficiary-address="{{ $bank->beneficiary_address }}" 
                                                    data-beneficiary-country="{{ $bank->beneficiary_country }}" 
                                                    value="{{ $bank->id }}">
                                                    {{ $bank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="bank-info" style="display: none;">
                                        <h5 class="mb-1">{{__('web.bank_information')}}</h5>
                                        <br>
                                        <p><strong>{{__('web.bank_name')}}:           </strong> <span id="bank-name-detail">                </span></p>
                                        <p><strong>{{__('web.address')}}:             </strong> <span id="bank-address-detail">             </span></p>
                                        <p><strong>{{__('web.swift')}}:               </strong> <span id="bank-swift-code-detail">          </span></p>
                                        <p><strong>{{__('web.iban')}}:                </strong> <span id="bank-iban-detail">                </span></p>
                                        <p><strong>{{__('web.account_number')}}:      </strong> <span id="bank-account-number-detail">      </span></p>
                                        <p><strong>{{__('web.beneficiary_name')}}:    </strong> <span id="bank-beneficiary-name-detail">    </span></p>
                                        <p><strong>{{__('web.beneficiary_address')}}: </strong> <span id="bank-beneficiary-address-detail"> </span></p>
                                        <p><strong>{{__('web.beneficiary_country')}}: </strong> <span id="bank-beneficiary-country-detail"> </span></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="amount1" class="form-label"><strong>{{__('web.amount')}} *</strong></label>
                                    <input type="number" class="form-control" id="amount1" name="amount" required>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="step2PrevBtn">{{__('web.previous')}}</button>
                                    <button type="button" class="btn btn-primary" id="step2NextBtn">{{__('web.next')}}</button>
                                </div>
                            </div>

                            <!-- Step 3: Upload Receipt -->
                            <div id="step-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                                <h5 class="mb-4">{{__('web.upload_your_receipt')}}</h5>
                                <div class="mb-3">
                                    <label for="receipt" class="form-label">{{__('web.upload_receipt')}} *</label>
                                    <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*" required>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="button" class="btn btn-outline-secondary px-4" id="step3PrevBtn"><i class="bx bx-left-arrow-alt me-2"></i>{{__('web.previous')}}</button>
                                    <button type="submit" class="btn btn-success px-4">{{__('web.submit')}}</button>
                                </div>
                            </div>
                            <!-- Hidden input fields for bank details -->
                            <input type="hidden" id="bank-name-hidden" name="bank_name">
                            <input type="hidden" id="bank-address" name="bank_address">
                            <input type="hidden" id="bank-swift-code" name="bank_swift_code">
                            <input type="hidden" id="bank-iban" name="bank_iban">
                            <input type="hidden" id="bank-account-number" name="bank_account_number">
                            <input type="hidden" id="bank-beneficiary-name" name="bank_beneficiary_name">
                            <input type="hidden" id="bank-beneficiary-address" name="bank_beneficiary_address">
                            <input type="hidden" id="bank-beneficiary-country" name="bank_beneficiary_country">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <form action="{{ route('client.reset.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700">{{__('web.current_password')}}</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700">{{__('web.new_password')}}</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-700">{{__('web.confirm_new_password')}}</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-blue-500 px-4 py-2 rounded hover:bg-blue-600">{{__('web.reset_password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script>
    var client_id = {{auth()->guard('client')->user()->id}};
    var assetId = {{$asset->id}};
</script>
<script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>

<script>
    
    $(document).ready(function() {
        $(document).on("click", ".edit_order", function() {
            $("#editOrderForm").attr("action", $(this).attr("formaction"));
            $("#editSp").val($(this).data("sp"));
            $("#editSl").val($(this).data("sl"));
        });

        $(".search").on("input", function () {
            let searchValue = $(this).val().toLowerCase();
            console.log(searchValue);

            $(".name").each(function () {
                let nameText = $(this).text().toLowerCase();

                if (nameText.includes(searchValue)) {
                    $(this).closest("li").removeClass('d-none');
                } else {
                    $(this).closest("li").addClass('d-none');
                }
            });
        });

        $('#toggleSidebar').click(function() {
            $('#sidebar').toggleClass('collapsed');
        });

        $('.category').click(function(e) {
            e.preventDefault();
            $(this).next('.currency-list').slideToggle();
        });

        function makeResizable(resizer, target, isHorizontal) {
            $(resizer).on('mousedown', function(e) {
                e.preventDefault();
                $(document).on('mousemove.resizing', function(event) {
                    if (isHorizontal) {
                        let newHeight = Math.max(100, event.clientY - $(target).offset().top);
                        $(target).css('height', newHeight + 'px');
                    } else {
                        let newWidth = Math.max(80, event.clientX - $(target).offset().left);
                        $(target).css('width', newWidth + 'px');
                    }
                });
                $(document).on('mouseup', function() {
                    $(document).off('mousemove.resizing');
                });
            });
        }
        makeResizable('#sidebarResizer', '#sidebar', false);
        makeResizable('#chartResizer', '#chartContainer', true);
    });

    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("orderAmount");
        const minusBtn = document.querySelector(".btn-minus");
        const plusBtn = document.querySelector(".btn-plus");

        minusBtn.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseFloat(input.value);
            if (value > 0.01) {
                input.value = (value - 0.01).toFixed(2);
            }
        });

        plusBtn.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseFloat(input.value);
            if (value < 100) {
                input.value = (value + 0.01).toFixed(2);
            }
        });
    });

    document.getElementById('openNavModal').addEventListener('click', function() {
        var newModal = new bootstrap.Modal(document.getElementById('navInfoModal'), {
            keyboard: true
        });
        newModal.show();
    });

    document.getElementById('stopLossSwitch').addEventListener('change', function() {
        document.getElementById('stopLossContainer').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('takeProfitSwitch').addEventListener('change', function() {
        document.getElementById('takeProfitContainer').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('stopLossSwitchPending').addEventListener('change', function() {
        document.getElementById('stopLossContainerPending').style.display = this.checked ? 'block' : 'none';
    });

    document.getElementById('takeProfitSwitchPending').addEventListener('change', function() {
        document.getElementById('takeProfitContainerPending').style.display = this.checked ? 'block' : 'none';
    });

    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM loaded, initializing stepper...');
        
        // Check if stepper element exists
        const stepperElement = document.querySelector('#stepper1');
        if (!stepperElement) {
            console.error('Stepper element not found!');
            return;
        }
        
        // Initialize stepper
        try {
            window.stepper1 = new Stepper(stepperElement, {
                linear: true,
                animation: true
            });
            console.log('Stepper initialized successfully');
        } catch (error) {
            console.error('Error initializing stepper:', error);
            return;
        }

        // Add event listeners for stepper navigation buttons
        const step1NextBtn = document.getElementById('step1NextBtn');
        const step2PrevBtn = document.getElementById('step2PrevBtn');
        const step2NextBtn = document.getElementById('step2NextBtn');
        const step3PrevBtn = document.getElementById('step3PrevBtn');

        if (step1NextBtn) {
            step1NextBtn.addEventListener('click', function() {
                console.log('Step 1 Next clicked');
                if (window.stepper1) {
                    window.stepper1.next();
                }
            });
        }

        if (step2PrevBtn) {
            step2PrevBtn.addEventListener('click', function() {
                console.log('Step 2 Previous clicked');
                if (window.stepper1) {
                    window.stepper1.previous();
                }
            });
        }

        if (step2NextBtn) {
            step2NextBtn.addEventListener('click', function() {
                console.log('Step 2 Next clicked');
                if (window.stepper1) {
                    window.stepper1.next();
                }
            });
        }

        if (step3PrevBtn) {
            step3PrevBtn.addEventListener('click', function() {
                console.log('Step 3 Previous clicked');
                if (window.stepper1) {
                    window.stepper1.previous();
                }
            });
        }

        // Check payment method element
        const paymentMethodEl = document.getElementById('payment-method');
        if (!paymentMethodEl) {
            console.error('Payment method element not found!');
            return;
        }

        paymentMethodEl.addEventListener('change', function () {
            console.log('Payment method changed to:', this.value);
            let method = this.value;
            document.querySelectorAll('.payment-details').forEach(function (element) {
                element.style.display = 'none';
            });
            if (method === 'usdt') {
                document.getElementById('usdt-details').style.display = 'block';
            } else if (method === 'bank') {
                document.getElementById('bank-details').style.display = 'block';
            }
        });

        // Check bank name element
        const bankNameEl = document.getElementById('bank-name0');
        if (!bankNameEl) {
            console.error('Bank name element not found!');
            return;
        }

        bankNameEl.addEventListener('change', function () {
            console.log('Bank selection changed');
            let selectedOption = this.options[this.selectedIndex];
            document.getElementById('bank-name-detail').textContent = selectedOption.textContent;
            document.getElementById('bank-address-detail').textContent = selectedOption.dataset.address || 'N/A';
            document.getElementById('bank-swift-code-detail').textContent = selectedOption.dataset.swift || 'N/A';
            document.getElementById('bank-iban-detail').textContent = selectedOption.dataset.iban || 'N/A';
            document.getElementById('bank-account-number-detail').textContent = selectedOption.dataset.account || 'N/A';
            document.getElementById('bank-beneficiary-name-detail').textContent = selectedOption.dataset.beneficiaryName || 'N/A';
            document.getElementById('bank-beneficiary-address-detail').textContent = selectedOption.dataset.beneficiaryAddress || 'N/A';
            document.getElementById('bank-beneficiary-country-detail').textContent = selectedOption.dataset.beneficiaryCountry || 'N/A';
            
            document.getElementById('bank-name-hidden').value = selectedOption.textContent || '';
            document.getElementById('bank-address').value = selectedOption.dataset.address || '';
            document.getElementById('bank-swift-code').value = selectedOption.dataset.swift || '';
            document.getElementById('bank-iban').value = selectedOption.dataset.iban || '';
            document.getElementById('bank-account-number').value = selectedOption.dataset.account || '';
            document.getElementById('bank-beneficiary-name').value = selectedOption.dataset.beneficiaryName || '';
            document.getElementById('bank-beneficiary-address').value = selectedOption.dataset.beneficiaryAddress || '';
            document.getElementById('bank-beneficiary-country').value = selectedOption.dataset.beneficiaryCountry || '';
            
            document.getElementById('bank-info').style.display = 'block';
        });

    });
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            el.classList.add('d-none');
        });
    }, 3000);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebTrader Mobile')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v1.0') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ url('assets/css/icons.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('assets/css/bootstrap.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('assets/plugins/metismenu/css/metisMenu.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/app.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('css/mobile-style.css?v1.0') }}" rel="stylesheet">
    <script src="{{ url('assets/js/new.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/metismenu/js/metisMenu.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v1.599') }}"></script>
<style>
    .btn {
        font-size: 12px;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #CCCCCC;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
        font-size: 12px;
    }
    .bottom-nav .nav-link {
        color: #495057;
    }
    .bottom-nav .nav-link.active {
        color: #007bff;
    }
    .iconify {
        font-size: 17px;
    }
    
    /* Sidebar Navigation Styles */
    .sidebar-nav {
        position: fixed;
        top: 0;
        left: -300px;
        width: 300px;
        height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        backdrop-filter: blur(20px);
        transition: left 0.3s ease;
        z-index: 2000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.3);
    }
    
    .sidebar-nav.active {
        left: 0;
    }
    
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1999;
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .sidebar-header {
        padding: 20px;
        background: rgba(255,255,255,0.1);
        border-bottom: 1px solid rgba(255,255,255,0.2);
        color: white;
    }
    
    .sidebar-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        float: right;
        cursor: pointer;
    }
    
    .sidebar-menu {
        padding: 20px 0;
    }
    
    .sidebar-menu-item {
        display: block;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,0.1)  ;
        transition: all 0.3s ease;
    }
    
    .sidebar-menu-item:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        text-decoration: none;
    }
    
    .sidebar-menu-item .iconify {
        margin-right: 10px;
        font-size: 20px;
    }
    
    .hamburger-btn {
        background: none;
        border: none;
        color: #000;
        font-size: 24px;
        cursor: pointer;
        padding: 5px;
    }
    
    .main-container {
        padding-bottom: 80px !important;
    }

    /* Balance Dropdown Content Production Styles */
    .balance-dropdown-content {
        position: fixed !important;
        left: 0 !important;
        right: 0 !important;
        /* bottom is controlled by JS, do NOT force it here! */
        z-index: 1041 !important;
        background: #fff !important;
        border-radius: 16px 16px 0 0 !important;
        transition: bottom 0.12s cubic-bezier(.4,0,.2,1), opacity 0.12s cubic-bezier(.4,0,.2,1), visibility 0.12s cubic-bezier(.4,0,.2,1) !important;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.12) !important;
        padding: 0 0 16px 0 !important;
        max-width: 100vw !important;
        min-height: 120px !important;
        opacity: 0;
        visibility: hidden;
    }
    .balance-dropdown-content .p-3 {
        /* No debug outline */
    }
    #balanceDropdownBar {
        /* No debug outline */
    }
        .bottom-nav {
        background: #f7f7f7 !important;
        border-top: 1px solid #e0e0e0 !important;
        box-shadow: 0 -2px 16px rgba(0,0,0,0.04);
        padding-bottom: env(safe-area-inset-bottom, 0);
        transition: background 0.2s;
    }
    .bottom-nav .nav-link {
        color: #222 !important;
        font-size: 14px;
        border-radius: 12px 12px 0 0;
        transition: background 0.18s, color 0.18s, font-weight 0.18s;
        background: transparent;
        position: relative;
        min-width: 0;
        outline: none;
    }
    .bottom-nav .nav-link.active,
    .bottom-nav .nav-link:active,
    .bottom-nav .nav-link:focus {
        background: #f2f2f2 !important;
        color: #111 !important;
        font-weight: 700;
        box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
    }
    .bottom-nav .iconify {
        font-size: 1.7rem;
        color: #888;
        transition: color 0.18s;
    }
    .bottom-nav .nav-link.active .iconify,
    .bottom-nav .nav-link:active .iconify,
    .bottom-nav .nav-link:focus .iconify {
        color: #111;
    }
    .bottom-nav .nav-label {
        font-size: 12px;
        color: #444;
        font-weight: 500;
        letter-spacing: 0.01em;
        margin-top: 0.1rem;
        transition: color 0.18s;
    }
    .bottom-nav .nav-link.active .nav-label,
    .bottom-nav .nav-link:active .nav-label,
    .bottom-nav .nav-link:focus .nav-label {
        color: #111;
    }
    .bottom-nav .nav-link::after {
        content: '';
        display: block;
        margin: 0 auto;
        width: 0%;
        height: 2px;
        background: #111;
        border-radius: 2px;
        transition: width 0.18s;
    }
    .bottom-nav .nav-link.active::after {
        width: 40%;
    }
    @media (max-width: 576px) {
        .bottom-nav .nav-link {
            font-size: 12px;
            padding: 0.4rem 0.1rem;
        }
        .bottom-nav .iconify {
            font-size: 1.3rem !important;
        }
    }
    @media (max-width: 400px) {
        .bottom-nav .nav-label {
            font-size: 11px;
        }
    }
</style>

</head>

<div class="container-fluid topbar p-0">
    <div class="row align-items-center justify-content-between" style="background-color: #F2F2F2; color: #000; padding: 10px;">
        <div class="col p-0" style="width: fit-content;margin-left: 0.4rem;">
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu">
                <span class="iconify" data-icon="material-symbols:menu" data-inline="false"></span>
            </button>
        </div>
        <div class="col p-0" style="width: fit-content">
            <div class="ms-auto d-flex align-items-center justify-content-end">
                <button class="btn me-2" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="iconify" data-icon="line-md:bell-loop" data-inline="false" style="font-size: 24px;"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-3 fw-bold" aria-labelledby="notificationDropdown" style="min-width: 300px;background-color: #cccccc;">
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
                                    <div class="col-12">
                                        {{__('web.'.$notification->text)}}
                                    </div>
                                    <div class="col-12 text-end">
                                        {{date('d/m H:i', strtotime($notification->created_at))}}
                                    </div>
                                </div>
                            </li>
                            <hr class="p-0 m-0">
                        @endforeach
                    @endif
                </ul>

                <!-- Language -->
                <div class="nav-item dropdown">
                    <div class="d-flex justify-content-center align-items-center nav-link dropdown-toggle-nocaret cursor-pointer text-dark me-2 p-0" data-bs-toggle="dropdown">
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
                <a href="{{route('chat.index')}}" class="btn me-2" style="position: relative;">
                    <span class="iconify" data-icon="mynaui:message" data-inline="false" style="font-size: 24px;"></span>
                    @if ($totalChat > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">@if ($totalChat > 99) +99 @else {{$totalChat}} @endif
                            <span class="visually-hidden">unread messages</span></span>
                    @endif
                </a>
                @if (!request()->routeIs('clientarea.quotes') && !isset(auth()->guard('client')->user()->options['cantOpen']))
                    <a data-bs-toggle="modal" data-bs-target="#newOrderModal" class="btn me-2">
                        <span class="iconify" data-icon="gridicons:add-outline" data-inline="false" style="font-size: 24px;"></span>
                    </a>
                @endif
            </div>
        </div>

            <!-- Notification -->
        </div>
    </div>
</div>

<div class="row mx-0" style="padding-top: 4rem;">
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

@if (request()->routeIs('clientarea.quotes'))
<!-- Balance Dropdown (Simple, Modern, Responsive) -->
<div id="balanceDropdownBar" class="d-flex align-items-center justify-content-between px-3 py-2 shadow-sm"
    style="position:fixed;left:0;right:0;bottom:56px;z-index:1040;background:#f8f9fa;color:#222;cursor:pointer;border-radius:18px 18px 0 0;min-height:56px;box-shadow:0 -2px 12px rgba(0,0,0,0.07);">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="fw-semibold d-flex align-items-center" style="font-size:1.08rem;color:#222;">
            <span class="iconify me-2" data-icon="mdi:wallet-outline" style="font-size:1.6rem;color:#222;"></span>
            {{ __('web.balance') }}
        </span>
        <span class="badge ms-2" style="background:#e9ecef;color:#222;font-size:1.04rem;padding:0.45em 1.1em;border-radius:14px;font-weight:500;">
            $ {{ number_format($finance['balance'], 2, '.', ',') }}
        </span>
    </div>
    <span id="balanceDropdownChevron" class="ms-2 d-flex align-items-center justify-content-center"
        style="font-size:1.5rem;transition:transform 0.2s;color:#888;">
        <i class="fas fa-chevron-down"></i>
    </span>
</div>
<div id="balanceDropdownContent" class="balance-dropdown-content shadow-lg"
    style="padding-top: 12px; padding-bottom: 24px;">
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-12 col-md-10 col-lg-8">
             @include('clientarea.balance-card', ['finance' => $finance, 'locale' => $locale])
          </div>
       </div>
    </div>
</div>
@endif

<div class="container p-0 main-container">
    @yield('content')
</div>

<!-- Sidebar Navigation -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<nav class="sidebar-nav" id="sidebarNav" aria-label="Main navigation menu">
    <div class="sidebar-header position-relative" style="padding-top: 8px;">
        <button class="sidebar-close position-absolute" id="sidebarClose" aria-label="Close navigation menu" style="top: 4px; right: 8px; z-index: 10;">
            <span class="iconify" data-icon="material-symbols:close" data-inline="false"></span>
        </button>
        <h5 style="color: #fff; margin: 0; padding-top: 8px;">{{ __('web.menu') }}</h5>
    </div>
    <div class="sidebar-menu">
        <a href="#" class="sidebar-menu-item" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
            <span class="iconify" data-icon="solar:key-broken" data-inline="false"></span>
            {{__('web.reset_password')}}
        </a>
        <a href="{{route('clientarea.account')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:account-circle" data-inline="false"></span>
            {{__('web.account')}}
        </a>
        <a href="{{route('client.deposit')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:arrow-upward" data-inline="false"></span>
            {{__('web.deposit')}}
        </a>
        <a href="{{route('client.withdraw')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:arrow-downward" data-inline="false"></span>
            {{__('web.withdraw')}}
        </a>
        <a href="{{route('clientarea.orders')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:receipt-long" data-inline="false"></span>
            {{__('web.orders')}}
        </a>
        <a href="{{route('client.logout')}}" class="sidebar-menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="iconify" data-icon="material-symbols:logout" data-inline="false"></span>
            {{__('web.logout')}}
        </a>
    </div>
</nav>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<nav class="bottom-nav navbar navbar-expand navbar-light shadow-sm" aria-label="Bottom navigation">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.quotes')) active @endif"
               href="{{ route('clientarea.quotes') }}"
               data-nav="quotes"
               aria-label="{{__('web.quotes')}}">
                <span class="iconify mb-1" data-icon="flowbite:arrow-up-down-outline"></span>
                <span class="small nav-label">{{__('web.quotes')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.charts')) active @endif"
               href="{{ route('clientarea.charts') }}"
               data-nav="charts"
               aria-label="{{__('web.charts')}}">
                <span class="iconify mb-1" data-icon="material-symbols:candlestick-chart-rounded"></span>
                <span class="small nav-label">{{__('web.charts')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.orders')) active @endif"
               href="{{ route('clientarea.orders') }}"
               data-nav="orders"
               aria-label="{{__('web.orders')}}">
                <span class="iconify mb-1" data-icon="material-symbols:add-box"></span>
                <span class="small nav-label">{{__('web.orders')}}</span>
            </a>
        </li>
    </ul>
</nav>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <div class="modal-header bg-blue-600 text-white rounded-t-lg">
                <h5 class="modal-title font-semibold" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                <button type="button" class="text-white bg-transparent border-0" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-2xl">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('client.reset.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2">{{__('web.current_password')}}</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-medium mb-2">{{__('web.new_password')}}</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2">{{__('web.confirm_new_password')}}</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-outline-dark btn-xs me-2">{{__('web.reset_password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if (!request()->routeIs('clientarea.quotes') && !isset(auth()->guard('client')->user()->options['cantOpen']))
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
@endif

<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script>
    var client_id = {{auth()->guard('client')->user()->id}};
    var assetId = 1;
</script>
<script>
    $('#asset-select').on('change', function() {
        const selectedOption = $(this).find(':selected');
        
        const bidPrice = selectedOption.data('bid');
        const askPrice = selectedOption.data('ask');
        
        $('#bid').val(bidPrice);
        $('#ask').val(askPrice);

        $('#sell-price').text(bidPrice);
        $('#buy-price').text(askPrice);
    });
    document.getElementById('stopLossSwitch').addEventListener('change', function() {
        document.getElementById('stopLossContainer').style.display = this.checked ? 'block' : 'none';
    });

    // Take Profit Toggle
    document.getElementById('takeProfitSwitch').addEventListener('change', function() {
        document.getElementById('takeProfitContainer').style.display = this.checked ? 'block' : 'none';
    });
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            el.classList.add('d-none');
        });
    }, 3000);
    
    // Sidebar Navigation JavaScript
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebarNav = document.getElementById('sidebarNav');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    
    function openSidebar() {
        sidebarNav.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        sidebarNav.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    hamburgerBtn.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);
    
    // Close sidebar when clicking on menu items (except reset password)
    document.querySelectorAll('.sidebar-menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!this.hasAttribute('data-bs-toggle')) {
                closeSidebar();
            }
        });
    });
</script>

<script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>
<script src="{{ url('js/mobile-style.js?v1.0') }}"></script>

<script>
    // Balance Dropdown Slide Up/Down (toggle on bar click)
    $(function() {
        var $balanceBar = $('#balanceDropdownBar');
        var $balanceContent = $('#balanceDropdownContent');
        var $chevron = $('#balanceDropdownChevron');
        var balanceOpen = false;

        // Initialize chevron to down position
        $chevron.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

        function openBalanceDropdown() {
            $balanceContent[0].style.setProperty('bottom', '56px', 'important');
            $balanceContent[0].style.setProperty('visibility', 'visible', 'important');
            $balanceContent[0].style.setProperty('opacity', '1', 'important');
            $chevron.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            balanceOpen = true;
        }
        function closeBalanceDropdown() {
            $balanceContent[0].style.setProperty('bottom', '-400px', 'important');
            $balanceContent[0].style.setProperty('visibility', 'hidden', 'important');
            $balanceContent[0].style.setProperty('opacity', '0', 'important');
            $chevron.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            balanceOpen = false;
        }

        // Always reset dropdown on resize/orientationchange
        $(window).on('resize orientationchange', function() {
            closeBalanceDropdown();
        });

        // Close dropdown if user taps outside
        $(document).on('click', function(e) {
            if (balanceOpen && !$(e.target).closest('#balanceDropdownBar, #balanceDropdownContent').length) {
                closeBalanceDropdown();
            }
        });

        // Close button handler for balance card
        $(document).on('click', '#closeBalanceDropdown', function(e) {
            e.stopPropagation();
            closeBalanceDropdown();
        });

        // Hide balance dropdown when any modal is opened
        $(document).on('show.bs.modal', '.modal', function() {
            if (balanceOpen) {
                closeBalanceDropdown();
            }
        });

        // Main click handler for balance dropdown toggle
        $balanceBar.on('click', function(e) {
            e.stopPropagation();
            // Check if any modal is currently visible
            if ($('.modal.show').length > 0) {
                return; // Don't toggle if modal is open
            }
            
            // Toggle the dropdown
            if (balanceOpen) {
                closeBalanceDropdown();
            } else {
                openBalanceDropdown();
            }
        });
    });
</script>
<script>
    // Interactive hover/focus effect for nav bar (mobile-friendly)
    document.querySelectorAll('.bottom-nav .nav-link').forEach(link => {
        link.addEventListener('touchstart', function() {
            this.classList.add('active');
        });
        link.addEventListener('touchend', function() {
            if (!this.href.endsWith(window.location.pathname)) {
                this.classList.remove('active');
            }
        });
        link.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
</script>
</html>

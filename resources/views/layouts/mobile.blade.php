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
        font-size: 11px;
        padding: 6px 10px;
    }
    
    /* Compact Header Styles */
    .topbar {
        background-color: #FAFAFA !important;
        border-bottom: 1px solid #E5E5E5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 8px 12px !important;
        min-height: 50px;
    }
    
    .topbar .btn {
        padding: 4px 8px;
        border-radius: 8px;
        background: transparent;
        border: none;
        transition: background 0.2s;
        color: #424242;
    }
    
    .topbar .btn:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    
    .hamburger-btn {
        background: none;
        border: none;
        color: #424242;
        font-size: 20px;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .hamburger-btn:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #212121;
    }
    
    .topbar .iconify {
        font-size: 20px;
    }
    
    .topbar .dropdown-menu {
        border-radius: 12px;
        border: 1px solid #E5E5E5;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 8px;
        background-color: #FFFFFF;
    }
    
    .topbar .dropdown-item {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        transition: background 0.2s;
        color: #424242;
    }
    
    .topbar .dropdown-item:hover {
        background: #F5F5F5;
    }
    
    /* Notification dropdown specific styles */
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA !important;
        border-color: #E5E5E5 !important;
    }
    
    /* Center notification dropdown */
    #notificationDropdown + .dropdown-menu {
        left: -70% !important;
        transform: translateX(-50%) translateY(10px) !important;
        right: auto !important;
        top: 100% !important;
        margin-top: 8px !important;
    }
    
    .dropdown-menu::-webkit-scrollbar {
        width: 4px;
    }
    
    .dropdown-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .dropdown-menu::-webkit-scrollbar-thumb {
        background: rgba(66, 66, 66, 0.3);
        border-radius: 2px;
    }
    
    .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(66, 66, 66, 0.5);
    }
    
    .topbar .badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: #424242;
        color: #FFFFFF;
    }
    
    /* Compact Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #FFFFFF !important;
        border-top: 1px solid #E5E5E5 !important;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
        padding: 8px 0 max(8px, env(safe-area-inset-bottom));
        z-index: 1000;
        height: 60px;
    }
    
    .bottom-nav .nav-link {
        color: #757575 !important;
        font-size: 11px;
        border-radius: 10px;
        transition: all 0.2s ease;
        background: transparent;
        position: relative;
        min-width: 0;
        outline: none;
        padding: 6px 8px;
        margin: 0 2px;
    }
    
    .bottom-nav .nav-link.active,
    .bottom-nav .nav-link:active,
    .bottom-nav .nav-link:focus {
        background: #212121 !important;
        color: #FFFFFF !important;
        font-weight: 600;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .bottom-nav .iconify {
        font-size: 18px;
        color: inherit;
        transition: all 0.2s;
        margin-bottom: 2px;
    }
    
    .bottom-nav .nav-label {
        font-size: 10px;
        color: inherit;
        font-weight: 500;
        letter-spacing: 0.02em;
        line-height: 1;
    }
    
    .bottom-nav .nav-link::after {
        display: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .topbar {
            padding: 6px 10px !important;
            min-height: 46px;
        }
        
        .topbar .iconify {
            font-size: 18px;
        }
        
        .hamburger-btn {
            font-size: 18px;
            padding: 4px;
        }
        
        .bottom-nav {
            height: 56px;
            padding: 6px 0 max(6px, env(safe-area-inset-bottom));
        }
        
        .bottom-nav .nav-link {
            font-size: 10px;
            padding: 4px 6px;
            margin: 0 1px;
        }
        
        .bottom-nav .iconify {
            font-size: 16px;
        }
        
        .bottom-nav .nav-label {
            font-size: 9px;
        }
    }
    
    @media (max-width: 400px) {
        .topbar {
            padding: 4px 8px !important;
            min-height: 44px;
        }
        
        .bottom-nav .nav-label {
            font-size: 8px;
        }
    }
    
    /* Main container adjustments */
    .main-container {
        padding-bottom: 70px !important;
        padding-top: 60px !important;
    }
    
    @media (max-width: 576px) {
        .main-container {
            padding-bottom: 66px !important;
            padding-top: 56px !important;
        }
    }
    
    @media (max-width: 400px) {
        .main-container {
            padding-bottom: 64px !important;
            padding-top: 54px !important;
        }
    }
    
    .iconify {
        font-size: 16px;
    }
    
    /* Sidebar Navigation Styles */
    .sidebar-nav {
        position: fixed;
        top: 0;
        left: -300px;
        width: 300px;
        height: 100vh;
        background: #424242;
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
        background: #616161;
        border-bottom: 1px solid #757575;
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
        border-bottom: 1px solid #616161;
        transition: all 0.3s ease;
    }
    
    .sidebar-menu-item:hover {
        background: #616161;
        color: white;
        text-decoration: none;
    }
    
    .sidebar-menu-item .iconify {
        margin-right: 10px;
        font-size: 20px;
    }

    /* Balance Dropdown Content Production Styles */
    .balance-dropdown-content {
        position: fixed !important;
        left: 0 !important;
        right: 0 !important;
        /* bottom is controlled by JS, do NOT force it here! */
        z-index: 1041 !important;
        background: #FAFAFA !important;
        border-radius: 16px 16px 0 0 !important;
        transition: bottom 0.12s cubic-bezier(.4,0,.2,1), opacity 0.12s cubic-bezier(.4,0,.2,1), visibility 0.12s cubic-bezier(.4,0,.2,1) !important;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.12) !important;
        padding: 0 0 16px 0 !important;
        max-width: 100vw !important;
        min-height: 120px !important;
        opacity: 0;
        visibility: hidden;
        border-top: 1px solid #E5E5E5;
    }
    .balance-dropdown-content .p-3 {
        /* No debug outline */
    }
    #balanceDropdownBar {
        /* No debug outline */
    }
</style>

</head>

<div class="container-fluid topbar p-0">
    <div class="row align-items-center justify-content-between mx-0">
        <div class="col-auto">
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu">
                <span class="iconify" data-icon="material-symbols:menu" data-inline="false"></span>
            </button>
        </div>
        
        <div class="col-auto">
            <div class="d-flex align-items-center gap-1">
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="iconify" data-icon="line-md:bell-loop" data-inline="false"></span>
                        @if ($notifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color:#424242;color:#FFFFFF;font-size:9px;padding:2px 6px;">
                                @if ($notifications->count() > 9) 9+ @else {{$notifications->count()}} @endif
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="notificationDropdown" style="min-width: 280px;max-width:85vw;border-radius:10px;border:1px solid #E5E5E5;box-shadow:0 3px 15px rgba(0,0,0,0.08);background-color:#FAFAFA;">
                        @if ($notifications->count() == 0)
                            <li class="dropdown-header" style="background-color:#424242;color:#FFFFFF;padding:8px 12px;margin:-8px -8px 6px -8px;border-radius:10px 10px 0 0;font-weight:600;font-size:0.85rem;">
                                <span class="iconify me-1" data-icon="material-symbols:notifications" style="font-size:1rem;"></span>
                                {{__('web.notifications')}}
                            </li>
                            <li style="padding:16px 12px;text-align:center;">
                                <div style="color:#9E9E9E;margin-bottom:6px;">
                                    <span class="iconify" data-icon="material-symbols:notifications-off" style="font-size:1.5rem;opacity:0.5;"></span>
                                </div>
                                <div style="color:#757575;font-size:0.85rem;font-weight:500;">{{__('web.no_notification')}}</div>
                                <div style="color:#9E9E9E;font-size:0.75rem;margin-top:3px;">Check back later for updates</div>
                            </li>
                        @else
                            <li class="dropdown-header" style="background-color:#424242;color:#FFFFFF;padding:8px 12px;margin:-8px -8px 6px -8px;border-radius:10px 10px 0 0;font-weight:600;font-size:0.85rem;display:flex;align-items:center;justify-content:space-between;">
                                <span>
                                    <span class="iconify me-1" data-icon="material-symbols:notifications" style="font-size:1rem;"></span>
                                    {{__('web.notifications')}}
                                </span>
                                <span id="notificationCounter" style="background-color:rgba(255,255,255,0.2);color:#FFFFFF;font-size:0.7rem;padding:2px 6px;border-radius:8px;font-weight:500;">
                                    {{$notifications->count()}}
                                </span>
                            </li>
                            <div style="max-height:200px;overflow-y:auto;">
                                @foreach ($notifications as $notification)
                                    <li style="margin:0 6px;">
                                        <div class="dropdown-item notification-item" data-notification-id="{{$notification->id}}" style="border-radius:6px;padding:8px 10px;margin-bottom:3px;background-color:#FFFFFF;border:1px solid #F0F0F0;transition:all 0.2s;position:relative;cursor:pointer;">
                                            <div style="display:flex;align-items:flex-start;gap:8px;">
                                                <div class="notification-dot" style="flex-shrink:0;width:6px;height:6px;background-color:#424242;border-radius:50%;margin-top:4px;"></div>
                                                <div style="flex:1;">
                                                    <div style="font-weight:600;color:#212121;font-size:0.8rem;margin-bottom:3px;line-height:1.2;">
                                                        {{__('web.'.$notification->text)}}
                                                    </div>
                                                    <div style="color:#757575;font-size:0.7rem;display:flex;align-items:center;gap:3px;">
                                                        <span class="iconify" data-icon="material-symbols:schedule" style="font-size:0.8rem;"></span>
                                                        {{date('d/m H:i', strtotime($notification->created_at))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </div>
                            <li style="margin:6px 6px 0 6px;">
                                <div style="border-top:1px solid #F0F0F0;padding:6px 0;">
                                    <div style="text-align:center;padding:4px;">
                                        <span id="notificationTotal" style="color:#757575;font-size:0.75rem;">{{$notifications->count()}} notification(s) total</span>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Language Selector -->
                <div class="dropdown">
                    <button class="btn d-flex align-items-center" data-bs-toggle="dropdown" aria-label="Language selector">
                        <img src="{{ config('app.flagIconUrlForLocale.' . app()->getLocale()) }}" width="18" height="12" alt="flag icon" class="rounded">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach(['en'=>'English','ar'=>'العربية'] as $language => $name)
                            <li>
                                <a class="dropdown-item" href="{{ switchUrlLocaleTo($language) }}">
                                    <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="16" height="11" alt="flag icon" class="me-2 rounded">
                                    <span>{{ $name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Chat -->
                <a href="{{route('chat.index')}}" class="btn position-relative" aria-label="Chat">
                    <span class="iconify" data-icon="mynaui:message" data-inline="false"></span>
                    @if ($totalChat > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            @if ($totalChat > 99) +99 @else {{$totalChat}} @endif
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>

                <!-- New Order -->
                @if (!request()->routeIs('clientarea.quotes') && !isset(auth()->guard('client')->user()->options['cantOpen']))
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#newOrderModal" aria-label="New order">
                        <span class="iconify" data-icon="gridicons:add-outline" data-inline="false"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-3 py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert">
            <small>{{ session('success') }}</small>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('fail'))
        <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert">
            <small>{{ session('fail') }}</small>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

@if (request()->routeIs('clientarea.quotes'))
<!-- Balance Dropdown (Simple, Modern, Responsive) -->
<div id="balanceDropdownBar" class="d-flex align-items-center justify-content-between px-3 py-2 shadow-sm"
    style="position:fixed;left:0;right:0;bottom:60px;z-index:1040;background:#FAFAFA;color:#212121;cursor:pointer;border-radius:18px 18px 0 0;min-height:48px;box-shadow:0 -2px 12px rgba(0,0,0,0.07);border-top:1px solid #E5E5E5;">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="fw-semibold d-flex align-items-center" style="font-size:1rem;color:#212121;">
            <span class="iconify me-2" data-icon="mdi:wallet-outline" style="font-size:1.4rem;color:#424242;"></span>
            {{ __('web.balance') }}
        </span>
        <span class="badge ms-2" style="background:#424242;color:#FFFFFF;font-size:0.9rem;padding:0.35em 0.9em;border-radius:12px;font-weight:500;">
            $ {{ number_format($finance['balance'], 2, '.', ',') }}
        </span>
    </div>
    <span id="balanceDropdownChevron" class="ms-2 d-flex align-items-center justify-content-center"
        style="font-size:1.3rem;transition:transform 0.2s;color:#757575;">
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
            <div class="modal-header" style="background-color:#424242;color:#FFFFFF;border-bottom:1px solid #616161;">
                <h5 class="modal-title font-semibold" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                <button type="button" class="text-white bg-transparent border-0" data-dismiss="modal" aria-label="Close" style="color:#FFFFFF;">
                    <span aria-hidden="true" class="text-2xl">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background-color:#FAFAFA;">
                <form action="{{ route('client.reset.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.current_password')}}</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.new_password')}}</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.confirm_new_password')}}</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-outline-dark btn-xs me-2" style="background-color:#424242;color:#FFFFFF;border:1px solid #424242;">{{__('web.reset_password')}}</button>
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
                <div class="modal-body" style="background-color:#FAFAFA;">
                <!-- New Market Order Tab -->
                    <div class="tab-content mt-3" id="orderTabsContent">
                        <!-- New Market Order -->
                        <div class="tab-pane fade show active" id="marketOrder" role="tabpanel">
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab" id="newTab">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="asset-select" class="form-label" style="color:#424242;">{{__('web.item')}}</label>
                                        <select class="single-select form-select inside-modal me-2" id="asset-select" name="currency" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
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
                                        <label for="newAmount" class="form-label" style="color:#424242;">{{__('web.amount')}}</label>
                                        <input type="number" class="form-control" id="newAmount" name="amount" min="0.01" step="any" value="0.01" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="stopLossSwitch">
                                            <label class="form-check-label" for="stopLossSwitch" style="color:#424242;">{{__('web.set_stop_loss')}}</label>
                                        </div>
                                        <div id="stopLossContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="stopLossInput" step="any" name="s_l" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                        </div>
                                    </div>
                                
                                    <!-- Set Take Profit -->
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitch">
                                            <label class="form-check-label" for="takeProfitSwitch" style="color:#424242;">{{__('web.set_take_profit')}}</label>
                                        </div>
                                        <div id="takeProfitContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="takeProfitInput" step="any" name="s_p" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-md me-2" formaction="{{route('order.store',['type' => 2])}}" style="background-color:#757575;color:#FFFFFF;border:1px solid #757575;">
                                        <span>{{__('web.sell')}} <strong id="sell-price"> 0</strong></span>
                                    </button>
                                    <button type="submit" class="btn btn-md ms-2" formaction="{{route('order.store',['type' => 1])}}" style="background-color:#424242;color:#FFFFFF;border:1px solid #424242;">
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

    // Notification click handler to mark as read
    document.addEventListener('click', function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            const notificationId = notificationItem.getAttribute('data-notification-id');
            markNotificationAsRead(notificationId, notificationItem);
        }
    });

    function markNotificationAsRead(notificationId, notificationElement) {
        // Mark notification as read visually
        notificationElement.style.opacity = '0.6';
        notificationElement.style.backgroundColor = '#F8F9FA';
        const dot = notificationElement.querySelector('.notification-dot');
        if (dot) {
            dot.style.backgroundColor = '#E0E0E0';
        }

        // Update counters
        updateNotificationCount();

        // Send AJAX request to mark as read on server
        fetch('/notifications/' + notificationId + '/read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Notification marked as read');
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    function updateNotificationCount() {
        const unreadNotifications = document.querySelectorAll('.notification-item:not([style*="opacity: 0.6"])');
        const count = unreadNotifications.length;
        
        // Update badge count
        const badge = document.querySelector('#notificationDropdown .badge');
        const counterInDropdown = document.getElementById('notificationCounter');
        const totalCounter = document.getElementById('notificationTotal');
        
        if (count === 0) {
            if (badge) badge.style.display = 'none';
        } else {
            if (badge) {
                badge.style.display = 'block';
                badge.textContent = count > 9 ? '9+' : count;
            }
        }
        
        if (counterInDropdown) {
            counterInDropdown.textContent = count;
        }
        
        if (totalCounter) {
            totalCounter.textContent = count + ' notification(s) total';
        }
    }
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
            $balanceContent[0].style.setProperty('bottom', '60px', 'important');
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

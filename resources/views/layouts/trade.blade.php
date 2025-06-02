<!DOCTYPE html>
<html lang="en" @if (Auth::guard('client')->check() && Auth::guard('client')->user()) @endif>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="{{ url('assets/plugins/simplebar/css/simplebar.min.css?v1.599') }}" rel="stylesheet" />
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css?v1.599') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/metismenu/css/metisMenu.min.css?v1.599') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/bootstrap.min.css?v1.599') }}" rel="stylesheet">
    <link href="{{ url('assets/css/app.min.css?v1.599') }}" rel="stylesheet">
    <link href="{{ url('assets/css/icons.min.css?v1.599') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/header-colors.min.css?v1.599') }}" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <title>@yield('title', 'BNC - WebTrade')</title>
    @stack('styles')

    <style>
        .dark-theme {
            background-color: #0E1426;
            color: #ffffff;
        }

        .dark-theme .bg-white,
        .dark-theme .bg-gray-100,
        .dark-theme .sidenav,
        .dark-theme .topbar,
        .dark-theme .main-content {
            background-color: #0E1426;
        }

        .dark-theme .topbar {
            background-color: #0E1426;
        }

        .dark-theme .text-gray-700,
        .dark-theme .text-gray-800 {
            color: #ffffff;
        }

        .dark-theme .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.12);
        }

        .topbar {
            height: 64px;
        }

        .sidenav {
            height: calc(100vh - 64px);
            width: 250px;
            position: fixed;
            z-index: 50;
            top: 64px;
            left: 0;
            background-color: #ffffff;
            overflow-x: hidden;
            padding-top: 20px;
            transition: left 0.3s;
        }

        .sidenav a {
            padding: 12px 16px;
            text-decoration: none;
            font-size: 14px;
            color: #4c4d4f;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-family: 'Arial', sans-serif;
        }

        .sidenav a:hover {
            background-color: #2d3748;
            color: #ffffff;
        }

        .sidenav a.active {
            background-color: #4A5568;
            color: #ffffff;
        }

        .sidenav a .parent-icon {
            margin-right: 10px;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin-left: 0; /* No margin for desktop */
            transition: margin-left 0.3s;
        }

        .content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .no-scroll {
            overflow: hidden;
        }

        /* Responsive styles */
        @media (max-width: 1024px) {
            .sidenav {
                left: -250px;
            }

            .sidenav.open {
                left: 0;
            }

            .main-container {
                margin-left: 250px;
            }

            .topbar-buttons {
                display: none;
            }

            .sidenav .menu-items {
                display: block;
            }
        }

        @media (min-width: 1024px) {
            .sidenav {
                display: none; /* Hide sidebar on desktop */
            }

            .main-container {
                margin-left: 0; /* No margin for desktop */
            }

            .topbar-buttons {
                display: flex;
            }
        }
    </style>

</head>

<body class="bg-gray-100">
    <!-- Topbar -->
    <nav class="bg-white shadow-md p-4 fixed w-full z-10 flex justify-between items-center topbar">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="text-gray-700 focus:outline-none lg:hidden">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Logo -->
        <div class="flex items-center space-x-4">
            <img src="{{ url('assets/images/logo-icon.png') }}" alt="BNC Logo" class="h-8">
        </div>

        <!-- Buttons and User Dropdown -->
        <div class="flex items-center space-x-4 topbar-buttons">
            <!-- Balance and Margin Info (Desktop Only) -->
            <div class="hidden lg:flex items-center space-x-4 ml-4">
                <div class="flex items-center space-x-2">
                    <span class="text-gray-700">{{__('web.balance')}}:</span>
                    <span class="text-gray-900">$10,000</span>
                </div>
                <div class="border-l h-6"></div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-700">{{__('web.credit')}}:</span>
                    <span class="text-gray-900">$2,000</span>
                </div>
                <div class="border-l h-6"></div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-700">{{__('web.required_margin')}}:</span>
                    <span class="text-gray-900">$1,500</span>
                </div>
                <div class="border-l h-6"></div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-700">{{__('web.free_margin')}}:</span>
                    <span class="text-gray-900">$8,500</span>
                </div>
            </div>

            <!-- WebTrader Button -->
            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{__('web.deposit')}}</a>

            <!-- Language Switcher (Desktop Only) -->
            <div class="nav-item dropdown">
                <div class="d-flex justify-content-center justify-items-center nav-link dropdown-toggle dropdown-toggle-nocaret px-0 cursor-pointer" data-bs-toggle="dropdown">
                    <img class="mx-3" src="{{ config('app.flagIconUrlForLocale.' . app()->getLocale()) }}" width="25" alt="flag icon">
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    @foreach(['en'=>'English','ar'=>'العربية','tr'=>'Türkçe'] as $language => $name)
                        <li>
                            <a class="dropdown-item my-2" href="{{ switchUrlLocaleTo($language) }}">
                                <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" alt="flag icon">
                                <span class="ms-2">{{ $name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <span>{{ Auth::guard('client')->user()->first_name }}</span>
                        <i class="fas fa-chevron-down text-gray-500"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-20">
                        <a href="{{ route('client.logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{__('web.logout')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="main-container" id="main-container">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidenav">
            <div class="p-4">
                <!-- Balance and Margin Info (Mobile and Tablet Only) -->
                <div class="lg:hidden mt-4">
                    <div class="mb-4">
                        <div class="flex justify-between py-2 border-b border-gray-600">
                            <span>{{__('web.balance')}}:</span>
                            <span>$10,000</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-600">
                            <span>{{__('web.credit')}}:</span>
                            <span>$2,000</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-600">
                            <span>{{__('web.required_margin')}}:</span>
                            <span>$1,500</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span>{{__('web.free_margin')}}:</span>
                            <span>$8,500</span>
                        </div>
                    </div>
                </div>

                <!--Menu items-->
                <div class="menu-items">
                    <ul class="space-y-2">
                        <li class="pages">
                            <a href="{{ route('client.dashboard') }}" class=" {{ request()->is('client/dashboard') ? 'active' : '' }}">
                                <i class="fas fa-user parent-icon"></i> <span class="menu-title">{{__('web.information')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.kyc.form') }}" class="{{ request()->is('client/kyc/form') ? 'active' : '' }}">
                                <i class="fas fa-file-alt parent-icon"></i> <span class="menu-title">{{__('web.kyc')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('client.trading.platform') }}" class="{{ request()->is('client/trading/platform') ? 'active' : '' }}">
                                <i class="fas fa-desktop parent-icon"></i> <span class="menu-title">{{__('web.trading_platform')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="{{ request()->is('client/deposits') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave parent-icon"></i> <span class="menu-title">{{__('web.deposit')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="{{ request()->is('client/withdrawals') ? 'active' : '' }}">
                                <i class="fas fa-hand-holding-usd parent-icon"></i> <span class="menu-title">{{__('web.withdraw')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Buttons for mobile and tablet -->
                <div class="lg:hidden mt-4">
                    <span href="#" class="block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mb-2 text-center w-full">{{__('web.webtrader')}}</span>
                    <span class="block bg-green-500 text-white px-4 py-2 rounded mb-2 text-center w-full">
                        @if (isset(Auth::guard('client')->user()->options['isVerified']))
                            {{__('web.approved')}}
                        @else
                            {{__('web.not_approved')}}
                        @endif
                    </span>

                    <!-- Language Switcher (Mobile and Tablet Only) -->
                    <div class="nav-item dropdown">
                        <div class="d-flex justify-content-center justify-items-center nav-link dropdown-toggle dropdown-toggle-nocaret px-0 cursor-pointer" data-bs-toggle="dropdown">
                            <img class="mx-3" src="{{ config('app.flagIconUrlForLocale.' . app()->getLocale()) }}" width="25" alt="flag icon">
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach(['en'=>'English','ar'=>'العربية','tr'=>'Türkçe'] as $language => $name)
                                <li>
                                    <a class="dropdown-item my-2" href="{{ switchUrlLocaleTo($language) }}">
                                        <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" alt="flag icon">
                                        <span class="ms-2">{{ $name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 mt-2" data-toggle="modal" data-target="#resetPasswordModal">
                        <i class="fas fa-key parent-icon"></i> {{__('web.reset_password')}}
                    </a>
                    <a href="{{ route('client.logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 mt-2">
                        <i class="fas fa-sign-out-alt mr-2"></i>{{__('web.logout')}}</a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="content flex-1 flex flex-col items-start justify-start main-content">
            <div class="bg-white shadow-md rounded-lg p-6 w-full h-full mt-4">
                @yield('content') <!-- Content section -->
            </div>
        </main>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{__('web.reset_password')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

@stack('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mobileMenuBtn = document.getElementById("mobile-menu-btn");
        const sidebar = document.getElementById("sidebar");
        const mainContainer = document.getElementById("main-container");
        const body = document.body;

        function toggleSidebar() {
            sidebar.classList.toggle("open");
            mainContainer.classList.toggle("with-sidebar");
            body.classList.toggle("no-scroll");
        }

        mobileMenuBtn.addEventListener("click", toggleSidebar);

        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const themeSwitch = document.getElementById('theme-switch');

        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-theme');
            if (body.classList.contains('dark-theme')) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            }
        });

    });
</script>
<script src="{{ url('js/jquery-3.3.1.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/bootstrap.bundle.min.js?v1.599') }}"></script>
</html>
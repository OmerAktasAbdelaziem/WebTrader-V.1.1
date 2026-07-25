<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('web.client_signup') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v1.0') }}" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in': 'fadeIn 1s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    backdropBlur: {
                        'xs': '2px',
                    }
                }
            }
        }
    </script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f5dc 0%, #faf9f6 50%, #e8e8e8 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .glass-card {
            background: rgba(250, 249, 246, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(228, 228, 228, 0.3);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 1px 0 rgba(255, 255, 255, 0.8) inset;
        }

        .input-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(220, 220, 220, 0.5);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-modern:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #8b8680;
            box-shadow:
                0 0 0 4px rgba(139, 134, 128, 0.1),
                0 10px 20px rgba(139, 134, 128, 0.1);
            transform: translateY(-1px);
        }

        .select-modern {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(220, 220, 220, 0.5);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .select-modern:focus {
            background: rgba(255, 255, 255, 1);
            border-color: #8b8680;
            box-shadow:
                0 0 0 4px rgba(139, 134, 128, 0.1),
                0 10px 20px rgba(139, 134, 128, 0.1);
            transform: translateY(-1px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #8b8680 0%, #6b6b6b 100%);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(139, 134, 128, 0.3);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .language-btn {
            background: rgba(245, 245, 220, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 220, 220, 0.3);
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            background: rgba(245, 245, 220, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .floating-orbs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(245, 245, 220, 0.3), rgba(232, 232, 232, 0.2));
            backdrop-filter: blur(20px);
            animation: float 6s ease-in-out infinite;
        }

        .orb:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 8s;
        }

        .orb:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
            animation-duration: 10s;
        }

        .orb:nth-child(3) {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 60%;
            animation-delay: 4s;
            animation-duration: 7s;
        }

        .orb:nth-child(4) {
            width: 120px;
            height: 120px;
            top: 20%;
            left: 70%;
            animation-delay: 1s;
            animation-duration: 9s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) translateX(10px) rotate(90deg);
            }
            50% {
                transform: translateY(-10px) translateX(-10px) rotate(180deg);
            }
            75% {
                transform: translateY(-30px) translateX(5px) rotate(270deg);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .form-container {
            animation: fadeInUp 0.8s ease-out;
        }

        .gradient-text {
            background: linear-gradient(135deg, #8b8680 0%, #6b6b6b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .orb {
                display: none;
            }

            body {
                background-size: 200% 200%;
            }

            .glass-card {
                margin: 1rem;
                border-radius: 1.5rem;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .btn-gradient:hover {
                transform: none;
                box-shadow: 0 10px 25px rgba(139, 134, 128, 0.3);
            }

            .input-modern:focus {
                transform: none;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .glass-card {
                background: rgba(255, 255, 255, 0.98);
                border: 2px solid #000;
            }

            .input-modern {
                background: #fff;
                border: 2px solid #000;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Select2 styling */
        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(220, 220, 220, 0.5);
            border-radius: 0.75rem;
            height: 3.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            background: rgba(255, 255, 255, 1);
            border-color: #8b8680;
            box-shadow:
                0 0 0 4px rgba(139, 134, 128, 0.1),
                0 10px 20px rgba(139, 134, 128, 0.1);
            transform: translateY(-1px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            line-height: 2rem;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3.5rem;
        }
    </style>
</head>
<body class="font-inter relative flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">
    <!-- Floating Orbs Background -->
    <div class="floating-orbs">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>

    <!-- Main Container -->
    <div class="w-full max-w-sm sm:max-w-md lg:max-w-lg xl:max-w-xl form-container">
        <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
            <!-- Error Messages -->
            @if(session('fail'))
                <div class="mx-4 sm:mx-6 mt-4 sm:mt-6 bg-red-50/90 backdrop-blur-sm border-l-4 border-red-400 p-4 rounded-xl animate-fade-in-up" role="alert">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium leading-5">{{ session('fail') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <img src="{{$logoUrl}}" class="rounded mx-auto d-block mt-5" style="width: 250px;" alt="logo">
            <div class="px-6 sm:px-8 lg:px-10 py-8 sm:py-10">
                <!-- Language Switcher -->
                <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-2 mb-8 sm:mb-10">
                    @foreach(['en' => 'English', 'ar' => 'العربية'] as $language => $name)
                        <a href="{{ switchUrlLocaleTo($language) }}" class="language-btn flex items-center justify-center w-full sm:w-auto px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 hover:text-gray-900 group">
                            <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" height="15" alt="flag icon" class="mr-2 rounded-sm group-hover:scale-110 transition-transform duration-200">
                            <span>{{ $name }}</span>
                        </a>
                    @endforeach
                </div>

                <!-- Header Section -->
                <div class="text-center mb-8 sm:mb-10 animate-fade-in">
                    <div class="mb-4">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold gradient-text mb-3 sm:mb-4">
                            {{ __('web.create_account') }}
                        </h1>
                        <div class="w-16 h-1 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full mx-auto mb-4"></div>
                    </div>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg font-medium">
                        {{ __('web.join_trading_platform') }}
                    </p>
                </div>

                <!-- Signup Form -->
                <form method="POST" action="{{ route('client.signup.submit') }}" class="space-y-6 sm:space-y-7" x-data="{ isLoading: false }" @submit="isLoading = true">
                    @csrf
                    <!-- First Row: First Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <!-- First Name Field -->
                        <div>
                            <label for="firstname" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.first_name') }}
                            </label>
                            <input type="text" id="firstname" name="first_name" class="input-modern w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl focus:outline-none text-sm sm:text-base placeholder-gray-400" placeholder="{{ __('web.enter_first_name') }}" required autocomplete="given-name">
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.email') }}
                            </label>
                            <input type="email" id="email" name="email" class="input-modern w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl focus:outline-none text-sm sm:text-base placeholder-gray-400" placeholder="{{ __('web.enter_email_address') }}" required autocomplete="email">
                        </div>
                    </div>

                    <!-- Second Row: Country & Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                        <!-- Country Field -->
                        <div>
                            <label for="country" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.country') }}
                            </label>
                            <select id="country"
                                    class="single-select form-select w-full rounded-xl focus:outline-none text-sm sm:text-base"
                                    name="country"
                                    required>
                                <option value="">{{ __('web.select_your_country') }}</option>
                                @foreach (__('web.country_list') as $key => $country)
                                    <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone1" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.phone_number') }}
                            </label>
                            <input type="tel" id="phone1" name="phone1" class="input-modern w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl focus:outline-none text-sm sm:text-base placeholder-gray-400" placeholder="{{ __('web.enter_phone_number') }}" required autocomplete="tel">
                        </div>
                    </div>

                    <!-- Third Row: Password & Confirm Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.password') }}
                            </label>
                            <input type="password" id="password" name="password" class="input-modern w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl focus:outline-none text-sm sm:text-base placeholder-gray-400" placeholder="{{ __('web.create_secure_password') }}" required autocomplete="new-password">
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm sm:text-base font-semibold text-gray-700 mb-3">
                                {{ __('web.confirm_password') }}
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="input-modern w-full px-4 sm:px-5 py-3 sm:py-4 rounded-xl focus:outline-none text-sm sm:text-base placeholder-gray-400" placeholder="{{ __('web.confirm_your_password') }}" required autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Sign Up Button -->
                    <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                        <button type="submit"
                                class="btn-gradient w-full text-white font-semibold py-3 sm:py-4 px-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-gray-200 text-sm sm:text-base lg:text-lg relative overflow-hidden disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="isLoading">
                            <span x-show="!isLoading">{{ __('web.sign_up') }}</span>
                            <span x-show="isLoading" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ __('web.creating_account') }}
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Webtrader Message --}}
                @if ((app()->getLocale() === 'ar' && $pipeline->webtrader_message_ar) || (app()->getLocale() !== 'ar' && $pipeline->webtrader_message_en))
                    <div dir="{{ (app()->getLocale() === 'ar') ? 'rtl' : 'ltr' }}" style="background: linear-gradient(135deg, #8b8680 0%, #6b6b6b 100%);" class="flex gap-2 items-center mt-5 w-full text-white py-3 sm:py-4 px-4 rounded-xl text-sm lg:text-lg relative">
                        @if ($pipeline->show_webtrader_message_icon)
                            <svg class="text-green-400 w-7 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15L15 9.75m-3-7.036A11.96 11.96 0 0 1 3.598 6A12 12 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623c5.176-1.332 9-6.03 9-11.622c0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285" />
                            </svg>
                        @endif
                        @if (app()->getLocale() == 'ar')
                            {{ $pipeline->webtrader_message_ar }}
                        @else
                            {{ $pipeline->webtrader_message_en }}
                        @endif
                    </div>
                @endif

                <!-- Login Link -->
                <div class="text-center mt-8 sm:mt-10 pt-6 sm:pt-8 border-t border-gray-200/50 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <p class="text-sm sm:text-base text-gray-600">
                        {{ __('web.already_have_account') }}
                        <a href="{{ route('client.login') }}" class="font-semibold text-transparent bg-clip-text bg-gradient-to-r from-gray-600 to-gray-800 hover:from-gray-500 hover:to-gray-700 transition-all duration-300 ml-1">
                            {{ __('web.you_have_account') }}
                        </a>
                    </p>
                </div>
            </div>

            <!-- TradingView Widget -->
            <div class="bg-stone-50/80 backdrop-blur-sm px-4 sm:px-6 lg:px-8 py-4 sm:py-6 animate-fade-in" style="animation-delay: 0.6s;">
                <div class="tradingview-widget-container pointer-events-none select-none">
                    <div class="tradingview-widget-container__widget"></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                    {
                        "symbols": [
                            {
                                "proName": "FOREXCOM:SPXUSD",
                                "title": "S&P 500 Index"
                            },
                            {
                                "proName": "FOREXCOM:NSXUSD",
                                "title": "US 100 Cash CFD"
                            },
                            {
                                "proName": "FX_IDC:EURUSD",
                                "title": "EUR to USD"
                            },
                            {
                                "proName": "BITSTAMP:BTCUSD",
                                "title": "Bitcoin"
                            },
                            {
                                "proName": "BITSTAMP:ETHUSD",
                                "title": "Ethereum"
                            },
                            {
                                "description": "GOLD",
                                "proName": "OANDA:XAUUSD"
                            },
                            {
                                "description": "Tesla",
                                "proName": "NASDAQ:TSLA"
                            }
                        ],
                        "showSymbolLogo": true,
                        "isTransparent": true,
                        "displayMode": "adaptive",
                        "colorTheme": "light",
                        "locale": "en"
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ url('js/jquery-3.3.1.min.js?v1.597') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.597') }}"></script>
    <script>
        $(document).ready(function() {
            $('.single-select').select2({
                placeholder: "{{ __('web.select_your_country') }}",
                allowClear: false,
                width: '100%'
            });
        });
    </script>
</body>
</html>

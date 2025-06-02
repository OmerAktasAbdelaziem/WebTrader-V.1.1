<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ url('assets/css/bootstrap.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v1.0') }}" rel="stylesheet" />
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <style>
        .background {
            background-image: url({{url('assets/images/background.png')}});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .logo{
            height: 85px;
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(1.7em + .50rem + 2px);
        }
    </style>
</head>
<body class="background flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        @if(session('fail'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('fail') }}</span>
            </div>
        @endif
        <div class="p-6">
            <div class="flex" style="justify-content: center;padding-bottom: 10px">
                @foreach(['en' => 'English', 'ar' => 'العربية'] as $language => $name)
                    <a href="{{ switchUrlLocaleTo($language) }}" class="flex items-center my-2 px-4 py-2 hover:bg-gray-100 rounded transition" style="text-decoration: none;color: black;">
                        <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" alt="flag icon" class="shrink-0">
                        <span class="ml-2">{{ $name }}</span>
                    </a>
                @endforeach
            </div>
            <div class="text-center">
                <img src="{{ url('assets/images/logo-icon1.png') }}" class="logo mx-auto" alt="BNC Logo">
            </div>
            <form method="POST" action="{{ route('client.signup.submit') }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label for="firstname" class="block text-sm font-medium text-gray-700">{{ __('web.first_name') }}</label>
                    <input type="text" id="firstname" name="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('web.email') }}</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="country" class="block text-sm font-medium text-gray-700">{{__('web.country')}}</label>
                    <select id="country" class="single-select form-select" name="country" required>
                        @foreach (__('web.country_list') as $key => $country)
                            <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="phone1" class="block text-sm font-medium text-gray-700">{{ __('web.phone_number') }}</label>
                    <input type="number" step="1" id="phone1" name="phone1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('web.password') }}</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('web.confirm_password') }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="w-full py-2 px-4 text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"  style="background-color: #CDEC76; color:white;">{{ __('web.sign_up') }}</button>
                </div>
                <a style="color: rgb(31, 121, 238);" href="{{route ('client.login')}}">{{ __('web.you_have_account') }}</a>
            </form>
        </div>
        <div class="tradingview-widget-container pointer-events-none select-none" style="user-select: none;">
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
                "isTransparent": false,
                "displayMode": "adaptive",
                "colorTheme": "light",
                "locale": "en"
            }
            </script>
        </div>
    </div>
</body>
<script src="{{ url('js/jquery-3.3.1.min.js?v1.597') }}"></script>
<script src="{{ url('assets/js/bootstrap.bundle.min.js?v1.597') }}"></script>
<script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.597') }}"></script>
<script src="{{ url('assets/js/form-select2.min.js?v1.597') }}"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('web.client_login') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <style>
        .background {
            background-image: url({{url('assets/images/background.png')}});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .text-3xl {
            font-size: 1.475rem;
            line-height: 2.25rem;
        }
        .logo{
            height: 85px;
        }
    </style>
</head>
<body class="background flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg overflow-hidden">
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="px-6 py-4">
            <div class="flex" style="justify-content: center;padding-bottom: 10px">
                @foreach(['en' => 'English', 'ar' => 'العربية'] as $language => $name)
                    <a href="{{ switchUrlLocaleTo($language) }}" class="flex items-center my-2 px-4 py-2 hover:bg-gray-100 rounded transition">
                        <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="20" alt="flag icon" class="shrink-0">
                        <span class="ml-2">{{ $name }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mb-4 text-center">
                <img src="{{ url('assets/images/logo-icon1.png') }}" class="logo mx-auto" alt="BNC Logo">
            </div>
            <form method="POST" action="{{ route('client.forget_password.submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('web.email') }}</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('web.phone_number') }}</label>
                    <input type="number" step="1" id="phone" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('web.new_password') }}:</label>
                    <input type="password" id="new_password" name="new_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">{{ __('web.confirm_new_password') }}:</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  style="background-color: #CDEC76; color:white;">{{ __('web.reset_password') }}</button>
                </div>
                <a style="color: rgb(31, 121, 238);" href="{{route ('client.signup')}}">{{ __('web.create_new_account') }}</a>
            </form>
        </div>
        <div class="tradingview-widget-container">
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
</html>

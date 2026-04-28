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
        <div class="px-6 py-4">
            <div class="mb-4 text-center">
                {{-- <img src="{{ url('assets/images/logo-icon1.png') }}" class="logo mx-auto" alt="BNC Logo"> --}}
                <img src="{{$logoUrl}}" class="logo mx-auto" alt="logo">
            </div>
            <form method="POST" action="{{ route('client.logout') }}">
                @csrf
                <h3 class="mb-4 text-red-600 text-center text-lg font-semibold">
                    {{ __('web.you_have_no_access_to_this_account') }}<br>
                    {{ __('web.please_contact_us') }}
                </h3>                  
                <div class="flex items-center justify-between">
                    <button type="submit" class="w-full text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"  style="background-color: #eb2626; color:white;">{{ __('web.logout') }}</button>
                </div>
            </form>
        </div>
        <div class="tradingview-widget-container" style="position: relative;">
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

            <!-- invisible blocker -->
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10;
            "></div>
        </div>
    </div>
</body>
</html>

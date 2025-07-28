<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('web.loading_trading_platform') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f5dc 0%, #faf9f6 50%, #e8e8e8 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #8b8680;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .pulse-text {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="loading-spinner mx-auto mb-6"></div>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">{{ __('web.loading_trading_platform') }}</h2>
        <p class="text-gray-600 pulse-text">{{ __('web.preparing_your_trading_environment') }}</p>
        
        <!-- Progress indicators -->
        <div class="mt-8 space-y-2">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">{{ __('web.loading_market_data') }}</span>
            </div>
            <div class="flex items-center justify-center space-x-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                <span class="text-sm text-gray-600">{{ __('web.loading_your_portfolio') }}</span>
            </div>
            <div class="flex items-center justify-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                <span class="text-sm text-gray-600">{{ __('web.initializing_charts') }}</span>
            </div>
        </div>
    </div>

    <script>
        // Auto-redirect to webtrader after 3 seconds to allow backend processing
        setTimeout(function() {
            window.location.href = "{{ route('client.webtrader.main') }}";
        }, 3000);
    </script>
</body>
</html>

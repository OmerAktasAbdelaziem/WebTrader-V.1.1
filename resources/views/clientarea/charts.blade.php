@extends('layouts.mobile')

@section('content')
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
@endsection
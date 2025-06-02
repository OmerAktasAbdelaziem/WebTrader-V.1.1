@extends('layouts.client')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card" style="width: 400px;">
        <div class="card-header text-center">
            <h3>{{__('web.trading_platform')}}</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group position-relative">
                    <label for="tradingLink" class="form-label">{{__('web.webtrader')}}:</label>
                    <div class="position-relative">
                        <input type="text" id="tradingLink" name="tradingLink" class="form-control pr-5" value="{{ route('client.webtrader') }}" readonly>
                        <a href="https://webtrader.elitexcrm.com/client/webtrader?id={{Auth::guard('client')->id()}}&token={{$remember_token}}" target="_blank" class="position-absolute d-flex align-items-center justify-content-center" style="top: 50%; right: 10px; transform: translateY(-50%); height: 100%;">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

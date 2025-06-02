<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="{{ url('assets/js/new.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/metismenu/js/metisMenu.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v1.599') }}"></script>
<style>
    #resetPasswordBtn {
        display: flex;
        align-items: center;
        white-space: nowrap;
    }
    .btn {
        font-size: 12px;
    }
    #resetPasswordBtn .iconify {
        margin-right: 5px;
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
</style>

</head>

<div class="container-fluid topbar p-0">
    <div class="row align-items-center justify-content-between" style="background-color: #F2F2F2; color: #000; padding: 10px;">
        <div class="col p-0" style="width: fit-content;margin-left: 0.4rem;">
            <button class="btn btn-outline-dark btn-xs me-2" id="resetPasswordBtn" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                <span class="iconify" data-icon="solar:key-broken" data-inline="false"></span>
                {{__('web.reset_password')}}
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

<div class="container p-0 main-container">
    @yield('content')
</div>

<nav class="bottom-nav navbar navbar-expand navbar-light bg-light">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a class="nav-link @if (request()->routeIs('clientarea.quotes')) active @endif" href="{{ route('clientarea.quotes') }}">
                <span class="iconify" data-icon="flowbite:arrow-up-down-outline" data-inline="false"></span>
                <span class="d-block">{{__('web.quotes')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request()->routeIs('clientarea.charts')) active @endif" href="{{ route('clientarea.charts') }}">
                <span class="iconify" data-icon="material-symbols:candlestick-chart-rounded" data-inline="false"></span>
                <span class="d-block">{{__('web.charts')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request()->routeIs('clientarea.orders')) active @endif" href="{{ route('clientarea.orders') }}">
                <span class="iconify  @if (request()->routeIs('clientarea.orders')) text-primary @endif" data-icon="material-symbols:add-box" data-inline="false"></span>
                <span class="d-block">{{__('web.orders')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link @if (request()->routeIs('clientarea.account')) active @endif" href="{{ route('clientarea.account') }}">
                <span class="iconify" data-icon="bxs:user" data-inline="false"></span>
                <span class="d-block">{{__('web.account')}}</span>
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
</script>

<script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>
</html>
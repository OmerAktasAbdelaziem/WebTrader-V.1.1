@extends('layouts.mobile')
<style>
    .nav-tabs .nav-link {
        padding: 10px 44px;
        font-size: 12px;
        background-color: #cccccc;
        color: #000;
        border-radius: 0 !important;
    }
    .nav-tabs .nav-link.active {
        background-color: #4699D9 !important;
        color: #fff !important;
        border-radius: 0 !important;
    }
    .container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        width: 350px;
    }
    h3 {
        border-bottom: 2px solid #CCCCCC;
        padding-bottom: 5px;
        margin-bottom: 15px;
        font-size: 18px;
    }
    .info {
        margin-bottom: 15px;
    }
    .info div {
        display: flex;
        justify-content: space-between;
        margin: 5px 0;
    }
    .green-text {
        color: #4caf50;
        font-weight: bold;
    }
    .logout-btn {
        width: 100%;
        padding: 10px;
        border: none;
        background-color: #4caf50;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }
    .logout-btn:hover {
        background-color: #45a049;
    }
    .balance {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }
</style>

@section('content')
<div class="container-fluid">
    <ul class="nav nav-tabs" id="firstTabSet" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab" aria-controls="information" aria-selected="true">{{__('web.information')}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#balance" type="button" role="tab" aria-controls="balance" aria-selected="false">{{__('web.balance')}}</button>
        </li>
    </ul>
    <div class="tab-content" id="firstTabSetContent">
        <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
            <br>
            <h3>{{__('web.personal_information')}}</h3>
            <div class="info">
                <div><strong>{{__('web.name')}}:</strong> <span>{{ $user->first_name }} {{ $user->last_name }}</span></div>
                <div><strong>{{__('web.id')}}:</strong> <span>{{ $user->id }}</span></div>
                <div><strong>{{__('web.account_type')}}:</strong> <span>{{__('web.'.$user->account_type??'Demo')}}</span></div>
                <div><strong>{{__('web.registration_date')}}:</strong> <span>{{ $user->reg_date->format('m/d/Y') }}</span></div>
            </div>
    
            <h3>{{__('web.trading_information')}}</h3>
            <div class="info">
                <div><strong>{{__('web.currency')}}:</strong> <span>USD</span></div>
                <div><strong>{{__('web.leverage')}}:</strong> <span>{{$user->leverage??'1:500'}}</span></div>
                <div><strong>{{__('web.profitloss')}}:</strong> <span class="@if ($finance['currentPL'] < 0) text-danger @else green-text @endif">{{number_format($finance['currentPL'], '3','.',',')}} $</span></div>
            </div>
            <form action="{{ route('client.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">{{__('web.logout')}}</button>
            </form>
        </div>

        <div class="tab-pane fade" id="balance" role="tabpanel" aria-labelledby="balance-tab">
            <br>
            <div class="balance text-center">{{__('web.balance')}} : <span class="green-text">{{number_format($finance['balance'], '2','.',',')}} $</span></div>

            <div class="info">
            <div><strong>{{__('web.equity')}} :</strong> <span>{{number_format($finance['equity'], '2','.',',')}} $</span></div>
            <div><strong>{{__('web.profitloss')}} :</strong> <span>{{number_format($finance['currentPL'], '3','.',',')}} $</span></div>
            <div><strong>{{__('web.margin')}} :</strong> <span>{{number_format($finance['usedMargin'], '2','.',',')}} $</span></div>
            <div><strong>{{__('web.free')}} :</strong> <span>{{number_format($finance['freeMargin'], '2','.',',')}} $</span></div>
            <div><strong>{{__('web.bonus')}} :</strong> <span>{{number_format($finance['bonus'], '2','.',',')}} $</span></div>
            <div><strong>{{__('web.credit')}} :</strong> <span>{{number_format($finance['credit'], '2','.',',')}} $</span></div>
            </div>
            <div class="d-flex flex-column align-items-center mt-3">
                @isset(auth()->guard('client')->user()->options['enableDepositRequest'])
                    <button class="btn btn-primary mb-2" style="background-color: #1A7230; border-color: #1A7230;" data-bs-toggle="modal" data-bs-target="#depositModal">
                        <span class="iconify" data-icon="ph:hand-deposit-fill" data-inline="false"></span> {{__('web.deposit')}}
                    </button>
                @endisset
                @isset(auth()->guard('client')->user()->options['enableWithdrawalRequest'])
                    <button class="btn btn-secondary mb-2" style="background-color: #1A7230; border-color: #1A7230;" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <span class="iconify" data-icon="bx:money-withdraw" data-inline="false"></span> {{__('web.withdraw')}}
                    </button>
                @endisset
            </div>
        </div>
    </div>



@isset(auth()->guard('client')->user()->options['enableDepositRequest'])
    <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="depositModalLabel">{{__('web.deposit')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div id="stepper1" class="bs-stepper linear">
                                <div class="bs-stepper-header d-flex" role="tablist">
                                    <div class="step" data-target="#step-1">
                                        <button type="button" class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="step-1" style="font-size: 10px">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">{{__('web.choose_payment_method')}}</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#step-2">
                                        <button type="button" class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="step-2" style="font-size: 10px">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">{{__('web.payment_details')}}</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#step-3">
                                        <button type="button" class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="step-3" style="font-size: 10px">
                                            <span class="bs-stepper-circle">3</span>
                                            <span class="bs-stepper-label">{{__('web.upload_receipt')}}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <form id="depositForm" method="POST" action="{{ route('deposit.process') }}" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Step 1: Payment Method Selection -->
                                        <div id="step-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                            <div class="mb-3">
                                                <label for="payment-method" class="form-label">{{__('web.payment_method')}} *</label>
                                                <select class="form-select" id="payment-method" name="payment_method" required>
                                                    <option selected>{{__('web.choose_payment_method')}}</option>
                                                    <option value="usdt">USDT</option>
                                                    <option value="bank">Bank Transfer</option>
                                                </select>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="stepper1.next()">{{__('web.next')}}</button>
                                        </div>

                                        <!-- Step 2: Payment Details -->
                                        <div id="step-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                            <!-- USDT Details -->
                                            <div id="usdt-details" class="payment-details" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="usdt-address" class="form-label">{{__('web.usdt_address')}}</label>
                                                    @if (auth()->guard('client')->user()->source == 'BNC')
                                                        <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{auth()->guard('client')->user()->usdt??auth()->guard('client')->user()->pipeline->usdt['BNC']??''}}" readonly>
                                                    @else
                                                        <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{auth()->guard('client')->user()->usdt??auth()->guard('client')->user()->pipeline->usdt['phoenix']??''}}" readonly>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Bank Transfer Details -->
                                            <div id="bank-details" class="payment-details" style="display: none;">
                                                <div class="mb-3">
                                                    <label for="bank-country" class="form-label">{{__('web.bank_country')}} *</label>
                                                    <select class="form-select" id="bank-country" name="country">
                                                        <option selected>{{__('web.select_country')}}</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country }}">{{ $country }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bank-name" class="form-label">{{__('web.select_bank')}} *</label>
                                                    <select class="form-select" id="bank-name" name="bank">
                                                        <option selected>{{__('web.select_bank')}}</option>
                                                        @foreach($banks as $bank)
                                                            <option 
                                                                data-country="{{ $bank->country }}" 
                                                                data-address="{{ $bank->address }}" 
                                                                data-swift="{{ $bank->swift_code }}" 
                                                                data-iban="{{ $bank->iban }}" 
                                                                data-account="{{ $bank->account_number }}" 
                                                                data-beneficiary-name="{{ $bank->beneficiary_name }}" 
                                                                data-beneficiary-address="{{ $bank->beneficiary_address }}" 
                                                                data-beneficiary-country="{{ $bank->beneficiary_country }}" 
                                                                value="{{ $bank->id }}">
                                                                {{ $bank->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="bank-info" style="display: none;">
                                                    <h5 class="mb-1">{{__('web.bank_information')}}</h5>
                                                    <br>
                                                    <p><strong>{{__('web.bank_name')}}:           </strong> <span id="bank-name-detail">                </span></p>
                                                    <p><strong>{{__('web.address')}}:             </strong> <span id="bank-address-detail">             </span></p>
                                                    <p><strong>{{__('web.swift')}}:               </strong> <span id="bank-swift-code-detail">          </span></p>
                                                    <p><strong>{{__('web.iban')}}:                </strong> <span id="bank-iban-detail">                </span></p>
                                                    <p><strong>{{__('web.account_number')}}:      </strong> <span id="bank-account-number-detail">      </span></p>
                                                    <p><strong>{{__('web.beneficiary_name')}}:    </strong> <span id="bank-beneficiary-name-detail">    </span></p>
                                                    <p><strong>{{__('web.beneficiary_address')}}: </strong> <span id="bank-beneficiary-address-detail"> </span></p>
                                                    <p><strong>{{__('web.beneficiary_country')}}: </strong> <span id="bank-beneficiary-country-detail"> </span></p>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="amount" class="form-label"><strong>{{__('web.amount')}} *</strong></label>
                                                <input type="number" class="form-control" id="amount" name="amount" required>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <button type="button" class="btn btn-secondary" onclick="stepper1.previous()">{{__('web.previous')}}</button>
                                                <button type="button" class="btn btn-primary" onclick="stepper1.next()">{{__('web.next')}}</button>
                                            </div>
                                        </div>

                                        <!-- Step 3: Upload Receipt -->
                                        <div id="step-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                                            <h5 class="mb-4">{{__('web.upload_your_receipt')}}</h5>
                                            <div class="mb-3">
                                                <label for="receipt" class="form-label">{{__('web.upload_receipt')}} *</label>
                                                <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*" required>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class="bx bx-left-arrow-alt me-2"></i>{{__('web.previous')}}</button>
                                                <button type="submit" class="btn btn-success px-4">{{__('web.submit')}}</button>
                                            </div>
                                        </div>
                                        <!-- Hidden input fields for bank details -->
                                        <input type="hidden" id="bank-name-hidden" name="bank_name">
                                        <input type="hidden" id="bank-address" name="bank_address">
                                        <input type="hidden" id="bank-swift-code" name="bank_swift_code">
                                        <input type="hidden" id="bank-iban" name="bank_iban">
                                        <input type="hidden" id="bank-account-number" name="bank_account_number">
                                        <input type="hidden" id="bank-beneficiary-name" name="bank_beneficiary_name">
                                        <input type="hidden" id="bank-beneficiary-address" name="bank_beneficiary_address">
                                        <input type="hidden" id="bank-beneficiary-country" name="bank_beneficiary_country">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset

@isset(auth()->guard('client')->user()->options['enableWithdrawalRequest'])
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 text-white" style="background: #F3F4F6; padding: 20px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
                            <h5 class="mb-3 text-dark text-center me-3" style="font-size: 1.25rem;">{{__('web.withdraw')}}</h5>
                            <ul class="nav nav-pills flex-column" id="withdrawTabs">
                                <li class="nav-item">
                                    <a class="nav-link active d-flex align-items-center" id="bank-tab" data-bs-toggle="tab" href="#bank-form" style="color: #1EBC74; font-size: 1rem; margin-top: 5px;">
                                        <span class="iconify me-1" data-icon="proicons:bank" data-inline="false"></span> {{__('web.bank')}}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" id="usdt-tab" data-bs-toggle="tab" href="#usdt-form" style="color: #1EBC74; font-size: 1rem; margin-top: 5px;">
                                        <span class="iconify me-1" data-icon="cryptocurrency:usdt" data-inline="false"></span> {{__('web.usdt')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-8 p-4">
                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="tab-content">
                                <!-- Bank Form -->
                                <div class="tab-pane fade show active" id="bank-form">
                                    <form method="POST" action="{{ route('client.withdraw.submit') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="swift" class="form-label">{{__('web.swift')}} *</label>
                                                <input type="text" class="form-control" id="swift" name="swift" placeholder="{{__('web.enter_swift')}}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="iban" class="form-label">{{__('web.iban')}} *</label>
                                                <input type="text" class="form-control" id="iban" name="iban" placeholder="{{__('web.enter_iban')}}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="bank-name" class="form-label">{{__('web.bank_name')}} *</label>
                                                <input type="text" class="form-control" id="bank-name" name="bank_name" placeholder="{{__('web.enter_bank_name')}}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="bank-country" class="form-label">{{__('web.bank_country')}} *</label>
                                                <select class="form-select" id="bank-country" name="bank_country" required>
                                                    <option selected>{{__('web.select_country')}}</option>
                                                    @foreach (__('web.country_list') as $key => $country)
                                                        <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="bank-address" class="form-label">{{__('web.bank_address')}} *</label>
                                                <input type="text" class="form-control" id="bank-address" name="bank_address" placeholder="{{__('web.enter_bank_address')}}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="beneficiary-name" class="form-label">{{__('web.beneficiary_name')}} *</label>
                                                <input type="text" class="form-control" id="beneficiary-name" name="beneficiary_name" placeholder="{{__('web.enter_beneficiary_name')}}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="beneficiary-country" class="form-label">{{__('web.beneficiary_country')}} *</label>
                                                <select class="form-select" id="beneficiary-country" name="beneficiary_country" required>
                                                    <option selected>{{__('web.select_country')}}</option>
                                                    @foreach (__('web.country_list') as $key => $country)
                                                        <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="beneficiary-address" class="form-label">{{__('web.beneficiary_address')}} *</label>
                                                <input type="text" class="form-control" id="beneficiary-address" name="beneficiary_address" placeholder="{{__('web.enter_beneficiary_address')}}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="currency" class="form-label">{{__('web.currency')}} *</label>
                                                <input type="text" class="form-control" id="currency" name="currency" value="USD" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="amount" class="form-label">{{__('web.amount')}} *</label>
                                                <input type="number" class="form-control" id="amount" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="aba-routing-number" class="form-label">{{__('web.aba_routing_number')}} *</label>
                                                <input type="text" class="form-control" id="aba-routing-number" name="aba_routing_number" placeholder="{{__('web.enter_aba_routing_number')}}" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">{{__('web.confirm')}}</button>
                                    </form>
                                </div>

                                <!-- USDT Form -->
                                <div class="tab-pane fade" id="usdt-form">
                                    <form method="POST" action="{{ route('client.withdraw.submit') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="currency" class="form-label">{{__('web.currency')}} *</label>
                                            <input type="text" class="form-control" id="currency" name="currency" value="USD" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="usdt" class="form-label">{{__('web.please_specify_usdt_address')}} *</label>
                                            <input type="text" class="form-control" id="usdt" name="usdt" placeholder="{{__('web.enter_usdt_address')}}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">{{__('web.amount')}} *</label>
                                            <input type="number" class="form-control" id="amount" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">{{__('web.confirm')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.stepper1 = new Stepper(document.querySelector('#stepper1'));

        document.getElementById('payment-method').addEventListener('change', function () {
            let method = this.value;
            document.querySelectorAll('.payment-details').forEach(function (element) {
                element.style.display = 'none';
            });
            if (method === 'usdt') {
                document.getElementById('usdt-details').style.display = 'block';
            } else if (method === 'bank') {
                document.getElementById('bank-details').style.display = 'block';
            }
        });

        document.getElementById('bank-name').addEventListener('change', function () {
            let selectedOption = this.options[this.selectedIndex];
            document.getElementById('bank-name-detail').textContent = selectedOption.textContent;
            document.getElementById('bank-address-detail').textContent = selectedOption.dataset.address || 'N/A';
            document.getElementById('bank-swift-code-detail').textContent = selectedOption.dataset.swift || 'N/A';
            document.getElementById('bank-iban-detail').textContent = selectedOption.dataset.iban || 'N/A';
            document.getElementById('bank-account-number-detail').textContent = selectedOption.dataset.account || 'N/A';
            document.getElementById('bank-beneficiary-name-detail').textContent = selectedOption.dataset.beneficiaryName || 'N/A';
            document.getElementById('bank-beneficiary-address-detail').textContent = selectedOption.dataset.beneficiaryAddress || 'N/A';
            document.getElementById('bank-beneficiary-country-detail').textContent = selectedOption.dataset.beneficiaryCountry || 'N/A';
            
            document.getElementById('bank-name-hidden').value = selectedOption.textContent || '';
            document.getElementById('bank-address').value = selectedOption.dataset.address || '';
            document.getElementById('bank-swift-code').value = selectedOption.dataset.swift || '';
            document.getElementById('bank-iban').value = selectedOption.dataset.iban || '';
            document.getElementById('bank-account-number').value = selectedOption.dataset.account || '';
            document.getElementById('bank-beneficiary-name').value = selectedOption.dataset.beneficiaryName || '';
            document.getElementById('bank-beneficiary-address').value = selectedOption.dataset.beneficiaryAddress || '';
            document.getElementById('bank-beneficiary-country').value = selectedOption.dataset.beneficiaryCountry || '';
            
            document.getElementById('bank-info').style.display = 'block';
        });

    });
</script>
@endsection
@extends('layouts.client')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .nav-tabs .nav-link {
        color: white !important;
    }
    .nav-tabs .nav-link.active {
        color: black !important;
        background-color: white !important;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: white;
        background-color: #b2d356 !important;
    }
    .btn-close {
        filter: invert(1);
    }
</style>
@section('content')
<div class="container mt-5 p-0 pt-4">
    @if(session('fail'))
        <div class="alert alert-danger">
            {{ session('fail') }}
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-white">{{__('web.withdrawals')}}</h4>
        @isset (auth()->guard('client')->user()->options['enableWithdrawalRequest'])
            <button class="btn" style="background-color: #b2d356; color: white; border-radius: 55px;" data-bs-toggle="modal" data-bs-target="#withdrawModal">{{__('web.withdraw')}}</button>
        @endisset
    </div>

    <ul class="nav nav-tabs mt-3" id="withdrawTabs">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending">{{__('web.pending')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history">{{__('web.history')}}</a>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <div class="tab-pane fade show active" id="pending">
            <div class="table-responsive">
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th style="font-size: 10px; color: white;">{{__('web.date_of_withdraw')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.status')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.bank_name')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.amount')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.currency')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingTransactions as $transaction)
                            <tr>
                                <td style="font-size: 10px; color: white;">{{ $transaction->created_at }}</td>
                                <td style="font-size: 10px; color: white;">{{__('web.'.$transaction->status)}}</td>
                                <td style="font-size: 10px; color: white;">
                                    {{$transaction->bank_details ? $transaction->bank_details['bank_name'] : $transaction->usdt}}
                                </td>
                                <td style="font-size: 10px; color: white;">{{ $transaction->amount }}</td>
                                <td style="font-size: 10px; color: white;">USD</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-white">{{__('web.no_data')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="history">
            <div class="table-responsive">
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th style="font-size: 10px; color: white;">{{__('web.date_of_withdraw')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.status')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.comment')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.payment_method')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.bank_name')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.amount')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.currency')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nonPendingTransactions as $transaction)
                            <tr>
                                <td style="font-size: 10px; color: white;">{{ $transaction->created_at }}</td>
                                <td style="font-size: 10px; color: white;">{{__('web.'.$transaction->status)}}</td>
                                <td style="font-size: 10px; color: white;">{!! $transaction->comment !!}</td>
                                <td style="font-size: 10px; color: white;">{{ $transaction->bank_details ? $transaction->bank_details['bank_name'] : $transaction->usdt }}</td>
                                <td style="font-size: 10px; color: white;">{{ $transaction->bank_details ? $transaction->bank_details['bank_name'] : $transaction->usdt }}</td>
                                <td style="font-size: 10px; color: white;">{{ $transaction->amount }}</td>
                                <td style="font-size: 10px; color: white;">USD</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-white">{{__('web.no_data')}}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px; background: linear-gradient(to bottom, #0b1a22 0%, #1c3b44 30%, #5f767b 60%, #d6d6d6 100%);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-4 text-white" style="background: linear-gradient(to bottom, #0b1a22 0%, #1c3b44 30%, #5f767b 60%, #d6d6d6; padding: 20px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;">
                        <h5 class="mb-3 text-white text-center me-3" style="font-size: 1.25rem;">{{__('web.withdraw')}}</h5>
                        <ul class="nav nav-pills flex-column" id="withdrawTabs">
                            <li class="nav-item">
                                <a class="nav-link active d-flex align-items-center" id="bank-tab" data-bs-toggle="tab" href="#bank-form" style="color: white; font-size: 1rem; margin-top: 5px;">
                                    <span class="iconify me-1 text-white" data-icon="proicons:bank" data-inline="false"></span> {{__('web.bank')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" id="usdt-tab" data-bs-toggle="tab" href="#usdt-form" style="color: white; font-size: 1rem; margin-top: 5px;">
                                    <span class="iconify me-1 text-white" data-icon="cryptocurrency:usdt" data-inline="false"></span> {{__('web.usdt')}}
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
                                            <label for="swift" class="form-label text-white">{{__('web.swift')}} *</label>
                                            <input type="text" class="form-control" id="swift" name="swift" placeholder="{{__('web.enter_swift')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="iban" class="form-label text-white">{{__('web.iban')}} *</label>
                                            <input type="text" class="form-control" id="iban" name="iban" placeholder="{{__('web.enter_iban')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank-name" class="form-label text-white">{{__('web.bank_name')}} *</label>
                                            <input type="text" class="form-control" id="bank-name" name="bank_name" placeholder="{{__('web.enter_bank_name')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bank-country" class="form-label text-white">{{__('web.bank_country')}} *</label>
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
                                            <label for="bank-address" class="form-label text-white">{{__('web.bank_address')}} *</label>
                                            <input type="text" class="form-control" id="bank-address" name="bank_address" placeholder="{{__('web.enter_bank_address')}}" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-name" class="form-label text-white">{{__('web.beneficiary_name')}} *</label>
                                            <input type="text" class="form-control" id="beneficiary-name" name="beneficiary_name" placeholder="{{__('web.enter_beneficiary_name')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-country" class="form-label text-white">{{__('web.beneficiary_country')}} *</label>
                                            <select class="form-select" id="beneficiary-country" name="beneficiary_country" required>
                                                <option selected>{{__('web.select_country')}}</option>
                                                @foreach (__('web.country_list') as $key => $country)
                                                    <option value="{{$country}}">{{__('web.country_list.'.$key)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="beneficiary-address" class="form-label text-white">{{__('web.beneficiary_address')}} *</label>
                                            <input type="text" class="form-control" id="beneficiary-address" name="beneficiary_address" placeholder="{{__('web.enter_beneficiary_address')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="currency" class="form-label text-white">{{__('web.currency')}} *</label>
                                            <input type="text" class="form-control" id="currency" name="currency" value="USD" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="amount" class="form-label text-white">{{__('web.amount')}} *</label>
                                            <input type="number" class="form-control" id="amount" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="aba-routing-number" class="form-label text-white">{{__('web.aba_routing_number')}} *</label>
                                            <input type="text" class="form-control" id="aba-routing-number" name="aba_routing_number" placeholder="{{__('web.enter_aba_routing_number')}}" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn w-100" style="background-color: #b2d356; color:white;">{{__('web.confirm')}}</button>
                                </form>
                            </div>

                            <!-- USDT Form -->
                            <div class="tab-pane fade" id="usdt-form">
                                <form method="POST" action="{{ route('client.withdraw.submit') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="currency" class="form-label text-white">{{__('web.currency')}} *</label>
                                        <input type="text" class="form-control" id="currency" name="currency" value="USD" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="usdt" class="form-label text-white">{{__('web.please_specify_usdt_address')}} *</label>
                                        <input type="text" class="form-control" id="usdt" name="usdt" placeholder="{{__('web.enter_usdt_address')}}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="amount" class="form-label text-white">{{__('web.amount')}} *</label>
                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="{{__('web.enter_amount')}}" required>
                                    </div>
                                    <button type="submit" class="btn w-100" style="background-color: #b2d356; color:white;">{{__('web.confirm')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <form action="{{ route('client.reset_password') }}" method="POST">
                    @csrf
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const bankTab = document.getElementById("bank-tab");
        const usdtTab = document.getElementById("usdt-tab");
        const bankForm = document.getElementById("bank-form");
        const usdtForm = document.getElementById("usdt-form");

        bankTab.addEventListener("click", function () {
            bankForm.classList.add("show", "active");
            usdtForm.classList.remove("show", "active");
        });

        usdtTab.addEventListener("click", function () {
            usdtForm.classList.add("show", "active");
            bankForm.classList.remove("show", "active");
        });
    });
</script>
<script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
@endsection

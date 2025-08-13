@extends('layouts.mobile')

@section('content')
<div class="container-fluid p-3">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-gradient-primary text-white d-block text-center position-relative">
                    <h4 class="card-title mb-0 d-inline-block align-middle">
                        <i class="iconify me-2" data-icon="material-symbols:arrow-downward"></i>
                        {{__('web.withdraw')}}
                    </h4>
                    <div class="d-flex justify-content-center mt-2">
                        <div class="small simple-balance-box p-2 rounded-2 shadow-sm" style="background:#fafbfc; min-width:160px; max-width:220px;">
                            <div class="text-center" style="font-size:0.95rem; color:#222; font-weight:600;">{{__('web.available_balance')}}</div>
                            <div class="text-center fw-bold" style="font-size:1.25rem; color:#1db954;">${{ number_format($balance ?? (isset($finance['balance']) ? $finance['balance'] : 0), 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light bg-opacity-75 rounded-bottom-4 p-4">
                    <form action="{{ route('client.withdraw.submit') }}" method="POST" id="withdrawForm">
                        @csrf
                        <div class="row g-4 align-items-end">
                            <div class="col-12 col-md-6">
                                <label for="amount" class="form-label fw-semibold">{{__('web.amount')}} (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="iconify" data-icon="mdi:currency-usd"></i></span>
                                    <input type="number" class="form-control border-start-0" id="amount" name="amount" min="1" max="{{ $balance ?? (isset($finance['balance']) ? $finance['balance'] : 0) }}" step="0.01" required placeholder="0.00">
                                </div>
                                <small class="form-text text-muted">{{__('web.max')}}: ${{ number_format($balance ?? (isset($finance['balance']) ? $finance['balance'] : 0), 2) }}</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="payment_method" class="form-label fw-semibold">{{__('web.payment_method')}}</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">{{__('web.choose_payment_method')}}</option>
                                    <option value="bank_transfer">{{__('web.bank_transfer')}}</option>
                                    <option value="cryptocurrency">{{__('web.cryptocurrency')}}</option>
                                </select>
                            </div>
                            <!-- Bank Transfer Details -->
                            <div id="bankTransferDetails" class="col-12" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="bank_name" class="form-label">{{__('web.bank_name')}}</label>
                                        <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="e.g. HSBC" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="account_number" class="form-label">{{__('web.account_number')}}</label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" placeholder="e.g. 1234567890" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="account_name" class="form-label">{{__('web.account_name')}}</label>
                                        <input type="text" class="form-control" id="account_name" name="account_name" placeholder="e.g. John Doe" required>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="swift_code" class="form-label">{{__('web.swift')}}</label>
                                        <input type="text" class="form-control" id="swift_code" name="swift_code" placeholder="e.g. HSBCHKHHHKH" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Cryptocurrency Details -->
                            <div id="cryptoDetails" class="col-12" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="crypto_type" class="form-label">{{__('web.cryptocurrency_type')}}</label>
                                        <select class="form-select" id="crypto_type" name="crypto_type">
                                            <option value="">{{__('web.select_cryptocurrency')}}</option>
                                            <option value="USDT">Tether (USDT)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="crypto_address" class="form-label">{{__('web.wallet_address')}}</label>
                                        <input type="text" class="form-control" id="crypto_address" name="crypto_address" placeholder="e.g. 0x...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label">{{__('web.notes')}} <span class="text-muted">({{__('web.optional')}})</span></label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="{{__('web.additional_notes')}}"></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill shadow-sm mx-auto">
                                    <i class="iconify me-2" data-icon="material-symbols:send"></i>
                                    {{__('web.submit_withdrawal')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Withdrawal History Tabs -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-gradient-secondary text-white">
                    <ul class="nav nav-tabs card-header-tabs" id="withdrawalHistoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">{{__('web.all')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button" role="tab" aria-controls="accepted" aria-selected="false">{{__('web.accepted')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">{{__('web.pending')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">{{__('web.rejected')}}</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content" id="withdrawalHistoryTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        @if($allWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr class="text-white-dark">
                                        <th class="text-white-dark">{{__('web.amount')}}</th>
                                        <th class="text-white-dark">{{__('web.method')}}</th>
                                        <th class="text-white-dark">{{__('web.date')}}</th>
                                        <th class="text-white-dark">{{__('web.status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark">
                                            @if($transaction->payment_method == 'bank_transfer')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($transaction->payment_method == 'cryptocurrency')
                                                {{ __('web.cryptocurrency') }}
                                            @else
                                                {{ $transaction->payment_method ?? __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                        <td class="text-white-dark">
                                            @if($transaction->status == 'approved')
                                                <span class="badge bg-success">{{__('web.accepted')}}</span>
                                            @elseif($transaction->status == 'pending')
                                                <span class="badge bg-warning text-dark">{{__('web.pending')}}</span>
                                            @else
                                                <span class="badge bg-danger">{{__('web.rejected')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">{{__('web.no_withdrawals_found')}}</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                        @if($acceptedWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr class="text-white-dark">
                                        <th class="text-white-dark">{{__('web.amount')}}</th>
                                        <th class="text-white-dark">{{__('web.method')}}</th>
                                        <th class="text-white-dark">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acceptedWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark">
                                            @if($transaction->payment_method == 'bank_transfer')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($transaction->payment_method == 'cryptocurrency')
                                                {{ __('web.cryptocurrency') }}
                                            @else
                                                {{ $transaction->payment_method ?? __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">{{__('web.no_withdrawals_found')}}</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @if($pendingWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr class="text-white-dark">
                                        <th class="text-white-dark">{{__('web.amount')}}</th>
                                        <th class="text-white-dark">{{__('web.method')}}</th>
                                        <th class="text-white-dark">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark">
                                            @if($transaction->payment_method == 'bank_transfer')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($transaction->payment_method == 'cryptocurrency')
                                                {{ __('web.cryptocurrency') }}
                                            @else
                                                {{ $transaction->payment_method ?? __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">{{__('web.no_withdrawals_found')}}</div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                        @if($rejectedWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr class="text-white-dark">
                                        <th class="text-white-dark">{{__('web.amount')}}</th>
                                        <th class="text-white-dark">{{__('web.method')}}</th>
                                        <th class="text-white-dark">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark">
                                            @if($transaction->payment_method == 'bank_transfer')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($transaction->payment_method == 'cryptocurrency')
                                                {{ __('web.cryptocurrency') }}
                                            @else
                                                {{ $transaction->payment_method ?? __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">{{__('web.no_withdrawals_found')}}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (prefers-color-scheme: dark) {
    .text-white-dark {
        color: white !important;
    }
}

[data-bs-theme="dark"] .text-white-dark {
    color: white !important;
}

.dark-mode .text-white-dark {
    color: white !important;
}
</style>

<script>
$(document).ready(function() {
    $('#payment_method').on('change', function() {
        const method = $(this).val();
        $('#bankTransferDetails, #cryptoDetails').hide();
        if (method === 'bank_transfer') {
            $('#bankTransferDetails').show();
            $('#bank_name, #account_number, #account_name, #swift_code').attr('required', true);
            $('#crypto_type, #crypto_address').attr('required', false);
        } else if (method === 'cryptocurrency') {
            $('#cryptoDetails').show();
            $('#crypto_type, #crypto_address').attr('required', true);
            $('#bank_name, #account_number, #account_name, #swift_code').attr('required', false);
        } else {
            $('#bank_name, #account_number, #account_name, #swift_code, #crypto_type, #crypto_address').attr('required', false);
        }
    });
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val());
        const maxAmount = parseFloat('{{ $balance ?? (isset($finance['balance']) ? $finance['balance'] : 0) }}');
        if (amount > maxAmount) {
            $(this).val(maxAmount);
        }
    });
    // Activate correct tab on click
    const hash = window.location.hash;
    if(hash) {
        $(".nav-link[data-bs-target='"+hash+"']").tab('show');
    }
});
</script>
@endsection

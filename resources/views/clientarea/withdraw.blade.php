@extends('layouts.mobile')

@section('content')

<script>
    // Share the current Laravel runtime locale state with frontend scripts
    window.currentAppLocale = "{{ app()->getLocale() }}";
</script>

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
                                    <option value="ewallet">{{__('web.ewallet')}}</option>
                                    <option value="other">{{__('web.other')}}</option>
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
                                    {{-- <div class="col-12 col-md-6">
                                        <label for="swift_code" class="form-label">{{__('web.swift')}}</label>
                                        <input type="text" class="form-control" id="swift_code" name="swift_code" placeholder="e.g. HSBCHKHHHKH" required>
                                    </div> --}}
                                    <div class="col-12 col-md-6">
                                        <label for="iban" class="form-label">{{__('web.iban')}}</label>
                                        <input type="text" class="form-control" id="iban" name="iban" placeholder="e.g. GB33BUKB20201555555555" required>
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
                            
                            <!-- ewallet Details Fields -->
                            <div id="ewalletDetailsFields" style="display: none;">
                                <div class="form-group-modern mb-3">
                                    <label for="ewallet_country_select" class="form-label">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ __('web.select_country') }}
                                    </label>
                                    <select name="country" id="ewallet_country_select" class="form-select" required>
                                        <option value="">{{ __('web.choose_country') }}</option>
                                        
                                        @foreach($allCountries as $country)
                                            <option value="{{ $country->id }}">{{ app()->getLocale() === 'ar' ? $country->name_ar : $country->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group-modern mb-3" id="ewallet_select_div">
                                    <label for="ewallet_select" class="form-label">
                                        {{ __('web.select_ewallet') }}
                                    </label>
                                    <select name="ewallet_id" id="ewallet_select" class="form-select" disabled>
                                        <option value="">{{ __('web.first_select_country') }}</option>
                                    </select>
                                </div>

                                <div id="ewalletDetailsDisplay" style="display: none;">
                                </div>

                            </div>

                            <div class="col-12" id="notes_div">
                                <label for="notes" class="form-label">{{__('web.notes')}} <span class="text-muted">({{__('web.optional')}})</span></label>
                                <textarea class="form-control" id="notes" name="note" rows="2" placeholder="{{__('web.additional_notes')}}"></textarea>
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
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.amount')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.method')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.date')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark" style="color: white !important;">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark" style="color: white !important;">
                                            @php
                                                $method = $transaction->payment_method ?? $transaction->method ?? 'unknown';
                                            @endphp
                                            @if($method == 'bank_transfer' || $method == 'bank')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($method == 'cryptocurrency' || $method == 'crypto' || $method == 'USDT')
                                                {{ __('web.cryptocurrency') }}
                                            @elseif($method == 'paypal')
                                                PayPal
                                            @elseif($method == 'credit_card' || $method == 'card')
                                                {{ __('web.credit_card') }}
                                            @elseif($method === 'wallet')
                                                {{ __('web.ewallet') }}
                                            @else
                                                {{ $method ?: __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark" style="color: white !important;">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                        <td class="text-white-dark" style="color: white !important;">
                                            <span class="badge {{ $transaction->status_badge_class }}">{{ $transaction->status_display }}</span>
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
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.amount')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.method')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($acceptedWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark" style="color: white !important;">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark" style="color: white !important;">
                                            @php
                                                $method = $transaction->payment_method ?? $transaction->method ?? 'unknown';
                                            @endphp
                                            @if($method == 'bank_transfer' || $method == 'bank')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($method == 'cryptocurrency' || $method == 'crypto' || $method == 'USDT')
                                                {{ __('web.cryptocurrency') }}
                                            @elseif($method == 'paypal')
                                                PayPal
                                            @elseif($method == 'credit_card' || $method == 'card')
                                                {{ __('web.credit_card') }}
                                            @else
                                                {{ $method ?: __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark" style="color: white !important;">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
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
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.amount')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.method')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark" style="color: white !important;">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark" style="color: white !important;">
                                            @php
                                                $method = $transaction->payment_method ?? $transaction->method ?? 'unknown';
                                            @endphp
                                            @if($method == 'bank_transfer' || $method == 'bank')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($method == 'cryptocurrency' || $method == 'crypto' || $method == 'USDT')
                                                {{ __('web.cryptocurrency') }}
                                            @elseif($method == 'paypal')
                                                PayPal
                                            @elseif($method == 'credit_card' || $method == 'card')
                                                {{ __('web.credit_card') }}
                                            @else
                                                {{ $method ?: __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark" style="color: white !important;">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
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
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.amount')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.method')}}</th>
                                        <th class="text-white-dark" style="color: white !important;">{{__('web.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedWithdrawals as $transaction)
                                    <tr class="text-white-dark">
                                        <td class="text-white-dark" style="color: white !important;">${{ number_format($transaction->amount, 2) }}</td>
                                        <td class="text-white-dark" style="color: white !important;">
                                            @php
                                                $method = $transaction->payment_method ?? $transaction->method ?? 'unknown';
                                            @endphp
                                            @if($method == 'bank_transfer' || $method == 'bank')
                                                {{ __('web.bank_transfer') }}
                                            @elseif($method == 'cryptocurrency' || $method == 'crypto' || $method == 'USDT')
                                                {{ __('web.cryptocurrency') }}
                                            @elseif($method == 'paypal')
                                                PayPal
                                            @elseif($method == 'credit_card' || $method == 'card')
                                                {{ __('web.credit_card') }}
                                            @else
                                                {{ $method ?: __('web.not_available') }}
                                            @endif
                                        </td>
                                        <td class="text-white-dark" style="color: white !important;">{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
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
/* Dark mode text styling */
@media (prefers-color-scheme: dark) {
    .text-white-dark {
        color: white !important;
    }
    .table-striped tbody tr:nth-of-type(odd) td {
        color: white !important;
    }
    .table-striped tbody tr:nth-of-type(even) td {
        color: white !important;
    }
    .table thead th {
        color: white !important;
    }
}

[data-bs-theme="dark"] .text-white-dark,
[data-bs-theme="dark"] .table-striped tbody tr td,
[data-bs-theme="dark"] .table thead th {
    color: white !important;
}

.dark-mode .text-white-dark,
.dark-mode .table-striped tbody tr td,
.dark-mode .table thead th {
    color: white !important;
}

/* Force white text for all table content in dark environments */
body.dark .text-white-dark,
body.dark .table-striped tbody tr td,
body.dark .table thead th,
html[data-theme="dark"] .text-white-dark,
html[data-theme="dark"] .table-striped tbody tr td,
html[data-theme="dark"] .table thead th {
    color: white !important;
}

/* Additional dark mode compatibility */
.table-dark .text-white-dark,
.table-dark tbody tr td,
.table-dark thead th {
    color: white !important;
}
</style>

<script>
$(document).ready(function() {
    window.ewalletData = @json($wallets ?? []);
    window.defaultFields = @json($defaultFields ?? []);

    $('#payment_method').on('change', function() {
        const method = $(this).val();
        $('#bankTransferDetails, #cryptoDetails').hide();
        $('#ewalletDetailsFields').hide();
        $('#notes_div').show();
        if (method === 'bank_transfer') {
            $('#bankTransferDetails').show();
            $('#bank_name, #account_number, #account_name, #iban').attr('required', true);
            $('#crypto_type, #crypto_address').attr('required', false);
        } else if (method === 'cryptocurrency') {
            $('#cryptoDetails').show();
            $('#crypto_type, #crypto_address').attr('required', true);
            $('#bank_name, #account_number, #account_name, #iban').attr('required', false);
        } else if (method === 'ewallet') {
            $('#ewalletDetailsFields').show();
            $('#receiptUpload').hide();
            $('#notes_div').hide();
            $('#crypto_type, #crypto_address').attr('required', false);
            $('#bank_name, #account_number, #account_name, #iban').attr('required', false);

        } else {
            $('#bank_name, #account_number, #account_name, #crypto_type, #crypto_address, #iban').attr('required', false);
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


    // e-wallet
    const ewallet_country_select = document.getElementById("ewallet_country_select");
    const ewallet_select = document.getElementById("ewallet_select");
    const ewalletDetailsDisplay = document.getElementById("ewalletDetailsDisplay");

    if (ewallet_country_select && ewallet_select) {
        ewallet_country_select.addEventListener("change", function () {
            const selectedCountry = this.value;

            const ewalletData = window.ewalletData || [];

            // Clear and reset ewallet select
            ewallet_select.innerHTML = '<option value="">Choose a wallet...</option>';
            ewallet_select.disabled = !selectedCountry;
            ewalletDetailsDisplay.style.display = "none";
            ewalletDetailsDisplay.innerHTML = "";

            if (selectedCountry) {
                // Filter ewallet by selected country 
                const countryewallet = ewalletData.filter((wallet) => {
                    if (!wallet.countries || !Array.isArray(wallet.countries)) {
                        return false;
                    }
                    return wallet.countries.some(
                        (country) => String(country.id) === String(selectedCountry)
                    );
                });

                if (countryewallet.length) {
                    document.getElementById("ewallet_select_div").classList.remove("d-none", "hidden");
                    countryewallet.forEach((ewallet) => {
                        const option = document.createElement("option");
                        option.value = ewallet.id;

                        option.textContent = ewallet.name;

                        option.setAttribute("data-fields", JSON.stringify(ewallet.fields || []));

                        ewallet_select.appendChild(option);
                    });
                }else{

                    document.getElementById("ewallet_select_div").classList.add("d-none", "hidden");

                    ewalletDetailsDisplay.innerHTML = "";

                    // Loop through fields to generate customized dynamic inputs layout configurations
                    defaultFields.forEach((field) => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.className = "mb-3";

                        // Determine primary display name based on the globally assigned locale state string
                        const localizedFieldName = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_name 
                            : field.english_field_name;

                        fieldGroup.innerHTML = `
                            <label class="form-label">${localizedFieldName}</label>
                            <input type="text" class="form-control" name="extra_fields[${field.id}]" placeholder="..." required>
                        `;
                        ewalletDetailsDisplay.appendChild(fieldGroup);
                    });

                    // Show the container details block
                    ewalletDetailsDisplay.style.display = "block";
                }
            }
        });

        ewallet_select.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];

            // Reset details container view state
            ewalletDetailsDisplay.innerHTML = "";

            if (selectedOption && selectedOption.value) {
                // Retrieve and parse your encoded string back into a structural JSON object array
                const fieldsJson = selectedOption.getAttribute("data-fields");
                const fields = fieldsJson ? JSON.parse(fieldsJson) : [];

                if (fields.length > 0) {
                    // Loop through fields to generate customized dynamic inputs layout configurations
                    fields.forEach((field) => {
                        const fieldGroup = document.createElement("div");
                        fieldGroup.className = "mb-3";

                        // Determine primary display name based on the globally assigned locale state string
                        const localizedFieldName = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_name 
                            : field.english_field_name;

                        fieldGroup.innerHTML = `
                            <label class="form-label">${localizedFieldName}</label>
                            <input type="text" class="form-control" name="extra_fields[${field.id}]" placeholder="..." required>
                        `;
                        ewalletDetailsDisplay.appendChild(fieldGroup);
                    });

                    // Show the container details block
                    ewalletDetailsDisplay.style.display = "block";
                } else {
                    // Keep block hidden if the chosen wallet contains no customized extra data field specifications
                    ewalletDetailsDisplay.style.display = "none";
                }
            } else {
                ewalletDetailsDisplay.style.display = "none";
            }
        });
    }

</script>
@endsection

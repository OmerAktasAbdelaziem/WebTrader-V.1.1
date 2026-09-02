@extends('layouts.mobile')

@section('title', 'Deposit - WebTrader Mobile')

@section('content')

<script>
    // Share the current Laravel runtime locale state with frontend scripts
    window.currentAppLocale = "{{ app()->getLocale() }}";
</script>

<div class="container-fluid p-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="iconify me-2" data-icon="material-symbols:arrow-upward"></i>
                        {{__('web.deposit')}}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Deposit Form -->
                    <form action="{{ route('deposit.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="deposit_method" class="form-label">{{__('web.deposit_method')}} <span class="text-danger">*</span></label>
                                <select class="form-select" id="deposit_method" name="deposit_method" required>
                                    <option value="">{{__('web.select_deposit_method')}}</option>
                                    <option value="bank">{{__('web.bank_transfer')}}</option>
                                    <option value="credit_card">{{__('web.credit_card')}}</option>
                                    <option value="crypto">{{__('web.crypto')}}</option>
                                    <option value="ewallet">{{__('web.ewallet')}}</option>
                                </select>
                                <small class="form-text text-muted">{{__('web.choose_deposit_method')}}</small>
                            </div>
                            <div class="col-12">
                                <label for="amount" class="form-label">{{__('web.amount')}} (USD)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="amount" name="amount" min="10" step="0.01" required placeholder="{{__('web.amount')}}">
                                    <span class="input-group-text bg-light text-primary" id="currentBalanceField">
                                        {{ isset($finance['balance']) ? number_format($finance['balance'], 2) : (auth()->guard('client')->user()->balance ?? '0.00') }} USD
                                    </span>
                                </div>
                                <small class="form-text text-muted">{{__('web.current_balance')}}: ${{ isset($finance['balance']) ? number_format($finance['balance'], 2) : (auth()->guard('client')->user()->balance ?? '0.00') }}</small>
                            </div>

                            <!-- Bank Transfer Fields -->
                            <div id="bankFields" style="display:none;">
                                <div class="col-12">
                                    <label for="country" class="form-label">{{__('web.country')}} <span class="text-danger">*</span></label>
                                    <select class="form-select" id="country" name="country">
                                        <option value="">{{__('web.select_country')}}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">{{__('web.choose_payment_method')}}</small>
                                </div>
                                <div class="col-12">
                                    <label for="bank_id" class="form-label">{{__('web.select_bank')}} <span class="text-danger">*</span></label>
                                    <select class="form-select" id="bank_id" name="bank_id">
                                        <option value="">{{__('web.select_country')}} first</option>
                                    </select>
                                    <small class="form-text text-muted">Select a country first to see available banks</small>
                                </div>
                            </div>

                            <!-- USDT TRC20 Only -->
                            <div id="cryptoFields" style="display:none;">
                                <div class="col-12">
                                    <label class="form-label">USDT TRC20 <span class="text-danger">*</span></label>
                                </div>
                                @if ($usdtWalletAddress)
                                    <div class="col-12">
                                        <div id="cryptoQrCode" class="mb-3 d-flex justify-content-center align-items-center" style="display:none; min-height:170px;"></div>
                                        <label for="wallet_address" class="form-label">{{__('web.wallet_address')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="wallet_address" name="wallet_address" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button" id="copyWalletBtn">
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">{{__('web.copy_or_scan_wallet_address')}}</small>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="alert-warning p-3 rounded-3" style="color: var(--bs-warning-text-emphasis);">
                                            <i class="iconify mb-1 me-2" data-icon="bi:exclamation-triangle"></i>
                                            <strong>USDT Address Not Available</strong>
                                            <p class="mb-0 mt-2" style="color: inherit;">Please contact our support team to set up your USDT deposit address.</p>
                                            <small class="d-block mt-2" style="color: inherit !important;">
                                                <i class="iconify mb-1 me-1" data-icon="bi:envelope"></i>
                                                Contact support for assistance with cryptocurrency deposits.
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Credit Card Fields -->
                            <div id="creditCardFields" style="display:none;">
                                <div class="col-12">
                                    <label for="card_number" class="form-label">{{__('web.card_number')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" maxlength="19" placeholder="0000 0000 0000 0000">
                                </div>
                                <div class="col-6">
                                    <label for="expiry_date" class="form-label">{{__('web.expiry_date')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="expiry_date" name="expiry_date" maxlength="5" placeholder="MM/YY">
                                </div>
                                <div class="col-6">
                                    <label for="cvv" class="form-label">{{__('web.cvv')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cvv" name="cvv" maxlength="4" placeholder="CVV">
                                </div>
                                <div class="col-12">
                                    <label for="cardholder_name" class="form-label">{{__('web.cardholder_name')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="cardholder_name" name="cardholder_name" placeholder="{{__('web.cardholder_name')}}">
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
                                        
                                        @php
                                            $filteredCountries = $wallets->pluck('countries')->flatten()->unique('id');
                                        @endphp

                                        @foreach($filteredCountries as $country)
                                            <option value="{{ $country->id }}">{{ app()->getLocale() === 'ar' ? $country->name_ar : $country->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group-modern mb-3">
                                    <label for="ewallet_select" class="form-label">
                                        {{ __('web.select_ewallet') }}
                                    </label>
                                    <select name="ewallet_id" id="ewallet_select" class="form-select" required disabled>
                                        <option value="">{{ __('web.first_select_country') }}</option>
                                    </select>
                                </div>

                                <div id="ewalletDetailsDisplay" style="display: none;">
                                </div>

                            </div>

                            <!-- Receipt Upload (required for bank and crypto, not for credit card) -->
                            <div class="col-12" id="receiptUpload">
                                <label for="receipt" class="form-label">{{__('web.upload_receipt')}}</label>
                                <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*,.pdf" required>
                                <small class="form-text text-muted">Upload payment receipt (Image or PDF)</small>
                            </div>
                        </div>
                        
                        <!-- Bank Details Display (no tabs) -->
                        <div id="bankDetails" class="mt-4" style="display: none;">
                            <div class="card border-primary">
                                <div class="card-header bg-primary" style="color: #fff;">
                                    <h6 class="mb-0">
                                        <i class="iconify me-2" data-icon="material-symbols:account-balance"></i>
                                        {{__('web.bank_details')}} - {{__('web.payment_details')}}
                                    </h6>
                                </div>
                                <div class="card-body" id="bankInfo">
                                    <!-- Bank details will be loaded here via AJAX -->
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyAllBankDetails()">
                                            <i class="iconify me-1" data-icon="material-symbols:content-copy"></i>
                                            {{__('web.copy_all_details')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100" id="submitDepositBtn">
                                <i class="iconify me-2" data-icon="material-symbols:cloud-upload"></i>
                                {{__('web.submit_deposit')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Section with Tabs (Deposit History) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{__('web.deposit_history')}}</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="depositHistoryTabs" role="tablist">
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
                    <div class="tab-content" id="depositHistoryTabsContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('web.amount')}}</th>
                                            <th>{{__('web.method')}}</th>
                                            <th>{{__('web.date')}}</th>
                                            <th>{{__('web.status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allDeposits as $deposit)
                                        <tr>
                                            <td>${{ number_format($deposit->amount, 2) }}</td>
                                            <td>
                                                @if($deposit->method == 'bank')
                                                    {{ __('web.bank_transfer') }}
                                                @elseif($deposit->method == 'crypto')
                                                    {{ __('web.crypto') }}
                                                @elseif($deposit->method == 'credit_card')
                                                    {{ __('web.credit_card') }}
                                                @elseif($deposit->method === 'wallet')
                                                    {{ __('web.ewallet') }}
                                                @else
                                                    {{ $deposit->method }}
                                                @endif
                                            </td>
                                            <td>{{ date('M d, Y', strtotime($deposit->created_at)) }}</td>
                                            <td>
                                                @if($deposit->status == 'pending')
                                                    <span class="badge bg-warning">{{__('web.pending')}}</span>
                                                @elseif($deposit->status == 'approved' || $deposit->status == 'accepted')
                                                    <span class="badge bg-success">{{__('web.approved')}}</span>
                                                @elseif($deposit->status == 'rejected')
                                                    <span class="badge bg-danger">{{__('web.rejected')}}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $deposit->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="accepted" role="tabpanel" aria-labelledby="accepted-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('web.amount')}}</th>
                                            <th>{{__('web.method')}}</th>
                                            <th>{{__('web.date')}}</th>
                                            <th>{{__('web.status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allDeposits as $deposit)
                                            @if($deposit->status == 'approved' || $deposit->status == 'accepted')
                                            <tr>
                                                <td>${{ number_format($deposit->amount, 2) }}</td>
                                                <td>
                                                    @if($deposit->method == 'bank')
                                                        {{ __('web.bank_transfer') }}
                                                    @elseif($deposit->method == 'crypto')
                                                        {{ __('web.crypto') }}
                                                    @elseif($deposit->method == 'credit_card')
                                                        {{ __('web.credit_card') }}
                                                    @else
                                                        {{ $deposit->method }}
                                                    @endif
                                                </td>
                                                <td>{{ date('M d, Y', strtotime($deposit->created_at)) }}</td>
                                                <td><span class="badge bg-success">{{__('web.approved')}}</span></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('web.amount')}}</th>
                                            <th>{{__('web.method')}}</th>
                                            <th>{{__('web.date')}}</th>
                                            <th>{{__('web.status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allDeposits as $deposit)
                                            @if($deposit->status == 'pending')
                                            <tr>
                                                <td>${{ number_format($deposit->amount, 2) }}</td>
                                                <td>
                                                    @if($deposit->method == 'bank')
                                                        {{ __('web.bank_transfer') }}
                                                    @elseif($deposit->method == 'crypto')
                                                        {{ __('web.crypto') }}
                                                    @elseif($deposit->method == 'credit_card')
                                                        {{ __('web.credit_card') }}
                                                    @else
                                                        {{ $deposit->method }}
                                                    @endif
                                                </td>
                                                <td>{{ date('M d, Y', strtotime($deposit->created_at)) }}</td>
                                                <td><span class="badge bg-warning">{{__('web.pending')}}</span></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{__('web.amount')}}</th>
                                            <th>{{__('web.method')}}</th>
                                            <th>{{__('web.date')}}</th>
                                            <th>{{__('web.status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allDeposits as $deposit)
                                            @if($deposit->status == 'rejected')
                                            <tr>
                                                <td>${{ number_format($deposit->amount, 2) }}</td>
                                                <td>
                                                    @if($deposit->method == 'bank')
                                                        {{ __('web.bank_transfer') }}
                                                    @elseif($deposit->method == 'crypto')
                                                        {{ __('web.crypto') }}
                                                    @elseif($deposit->method == 'credit_card')
                                                        {{ __('web.credit_card') }}
                                                    @else
                                                        {{ $deposit->method }}
                                                    @endif
                                                </td>
                                                <td>{{ date('M d, Y', strtotime($deposit->created_at)) }}</td>
                                                <td><span class="badge bg-danger">{{__('web.rejected')}}</span></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Global copy functions for deposit page
    window.copyToClipboard = function(fieldId, button) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const value = field.value || field.textContent;
        if (!value || value === 'N/A') {
            alert('No data to copy');
            return;
        }

        if (navigator.clipboard && window.isSecureContext) {
            // Use modern clipboard API
            navigator.clipboard.writeText(value).then(function() {
                showCopySuccess(button);
            }).catch(function() {
                fallbackCopyToClipboard(value, button);
            });
        } else {
            // Fallback for older browsers
            fallbackCopyToClipboard(value, button);
        }
    };

    window.copyAllBankDetails = function() {
        if (!window.currentBankData) {
            alert('No bank details to copy');
            return;
        }

        const data = window.currentBankData;
        let allDetails = `Bank Details:\n`;
        allDetails += `Bank Name: ${data.name}\n`;
        if (data.account_number) allDetails += `Account Number: ${data.account_number}\n`;
        if (data.beneficiary_name) allDetails += `Beneficiary Name: ${data.beneficiary_name}\n`;
        if (data.iban) allDetails += `IBAN: ${data.iban}\n`;
        // if (data.swift_code) allDetails += `SWIFT Code: ${data.swift_code}\n`;
        if (data.bic) allDetails += `BIC: ${data.bic}\n`;
        if (data.address) allDetails += `Address: ${data.address}\n`;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(allDetails).then(function() {
                alert('All bank details copied to clipboard!');
            }).catch(function() {
                fallbackCopyToClipboard(allDetails);
            });
        } else {
            fallbackCopyToClipboard(allDetails);
        }
    };

    function fallbackCopyToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                if (button) {
                    showCopySuccess(button);
                } else {
                    alert('Copied to clipboard!');
                }
            } else {
                alert('Failed to copy to clipboard');
            }
        } catch (err) {
            console.error('Copy failed:', err);
            alert('Failed to copy to clipboard');
        } finally {
            document.body.removeChild(textArea);
        }
    }

    function showCopySuccess(button) {
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="iconify" data-icon="material-symbols:check"></i>';
        button.classList.add('btn-success');
        button.classList.remove('btn-outline-secondary');
        
        setTimeout(function() {
            button.innerHTML = originalHtml;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
        
        alert('Copied!');
    }

    $(document).ready(function() {
        window.ewalletData = @json($wallets ?? []);


        // Debug form submission
        $('form[action="{{ route('deposit.process') }}"]').on('submit', function(e) {
            console.log('=== DEPOSIT FORM SUBMISSION DEBUG ===');
            console.log('Form action:', $(this).attr('action'));
            console.log('Form method:', $(this).attr('method'));
            console.log('Form data:', $(this).serialize());
            console.log('Deposit method:', $('#deposit_method').val());
            console.log('Amount:', $('#amount').val());
            
            // Check required fields based on method
            var method = $('#deposit_method').val();
            var isValid = true;
            var errors = [];
            
            if (!method) {
                errors.push('Deposit method is required');
                isValid = false;
            }
            
            if (!$('#amount').val() || $('#amount').val() < 10) {
                errors.push('Amount must be at least $10');
                isValid = false;
            }
            
            if (method === 'bank') {
                if (!$('#country').val()) {
                    errors.push('Country is required for bank transfer');
                    isValid = false;
                }
                if (!$('#bank_id').val()) {
                    errors.push('Bank selection is required');
                    isValid = false;
                }
                if (!$('#receipt')[0].files.length) {
                    errors.push('Receipt is required for bank transfer');
                    isValid = false;
                }
            }
            
            if (method === 'crypto') {
                if (!$('#receipt')[0].files.length) {
                    errors.push('Receipt is required for crypto deposit');
                    isValid = false;
                }
            }
            
            if (method === 'credit_card') {
                if (!$('#card_number').val()) {
                    errors.push('Card number is required');
                    isValid = false;
                }
                if (!$('#expiry_date').val()) {
                    errors.push('Expiry date is required');
                    isValid = false;
                }
                if (!$('#cvv').val()) {
                    errors.push('CVV is required');
                    isValid = false;
                }
                if (!$('#cardholder_name').val()) {
                    errors.push('Cardholder name is required');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                console.log('Form validation errors:', errors);
                alert('Please fix the following errors:\n' + errors.join('\n'));
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            
            // Disable submit button to prevent double submission
            $('#submitDepositBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        });

        // Show/hide fields based on deposit method
        $('#deposit_method').on('change', function() {
            var method = $(this).val();
            $('#bankFields').hide();
            $('#cryptoFields').hide();
            $('#creditCardFields').hide();
            $('#bankDetails').hide();
            $('#receiptUpload').show();
            $('#receipt').prop('required', true);
            $('#ewalletDetailsFields').hide();
            
            if (method === 'bank') {
                $('#bankFields').show();
            } else if (method === 'crypto') {
                $('#cryptoFields').show();
            } else if (method === 'credit_card') {
                $('#creditCardFields').show();
                $('#receiptUpload').hide();
                $('#receipt').prop('required', false);
            } else if (method === 'ewallet') {
                $('#ewalletDetailsFields').show();
                $('#receiptUpload').hide();
                $('#receipt').prop('required', false);
            }
        });

        // Bank country/bank selection logic
        $('#country').on('change', function() {
            const country = $(this).val();
            if (country) {
                $('#bank_id').html('<option value="">Loading banks...</option>');
                $.post('{{ route("get.banks") }}', {
                    country: country,
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    $('#bank_id').html('<option value="">{{__("web.select_bank")}}</option>');
                    if (response && response.length > 0) {
                        response.forEach(function(bank) {
                            $('#bank_id').append(`<option value="${bank.id}">${bank.name}</option>`);
                        });
                    } else {
                        $('#bank_id').html('<option value="">{{__("web.no_bank_in_country")}}</option>');
                    }
                }).fail(function() {
                    $('#bank_id').html('<option value="">Error loading banks</option>');
                    alert('Failed to load banks. Please try again.');
                });
            } else {
                $('#bank_id').html('<option value="">{{__("web.select_bank")}}</option>');
                $('#bankDetails').hide();
            }
        });

        $('#bank_id').on('change', function() {
            const bankId = $(this).val();
            if (bankId) {
                $.post('{{ route("get.bank.details") }}', {
                    bank_id: bankId,
                    _token: '{{ csrf_token() }}'
                }).done(function(response) {
                    if (response && response.name) {
                        let bankInfo = `
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">{{__('web.bank_name')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="${response.name}" readonly id="bank_name_field">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('bank_name_field', this)">
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">{{__('web.account_number')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="${response.account_number || 'N/A'}" readonly id="account_number_field">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('account_number_field', this)" ${!response.account_number ? 'disabled' : ''}>
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">{{__('web.beneficiary_name')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="${response.beneficiary_name || 'N/A'}" readonly id="beneficiary_name_field">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('beneficiary_name_field', this)" ${!response.beneficiary_name ? 'disabled' : ''}>
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                ${response.iban ? `
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">{{__('web.iban')}}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="${response.iban}" readonly id="iban_field">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('iban_field', this)">
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                ${response.bic ? `
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">BIC</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="${response.bic}" readonly id="bic_field">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('bic_field', this)">
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                                ${response.address ? `
                                <div class="col-12">
                                    <div class="bank-detail-item">
                                        <label class="form-label fw-bold text-primary">{{__('web.address')}}</label>
                                        <div class="input-group">
                                            <textarea class="form-control" readonly id="address_field" rows="2">${response.address}</textarea>
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('address_field', this)">
                                                <i class="iconify" data-icon="material-symbols:content-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                        $('#bankInfo').html(bankInfo);
                        $('#bankDetails').show();
                        window.currentBankData = response;
                    } else {
                        alert('Bank details not found.');
                        $('#bankDetails').hide();
                    }
                }).fail(function() {
                    alert('Failed to load bank details. Please try again.');
                    $('#bankDetails').hide();
                });
            } else {
                $('#bankDetails').hide();
            }
        });

        // Credit card number formatting
        $('#card_number').on('input', function() {
            var value = $(this).val().replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            var formattedValue = value.match(/.{1,4}/g)?.join(' ') ?? value;
            if (formattedValue.length > 19) formattedValue = formattedValue.substr(0, 19);
            $(this).val(formattedValue);
        });

        // Expiry date formatting
        $('#expiry_date').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substr(0, 2) + '/' + value.substr(2, 2);
            }
            $(this).val(value);
        });

        // CVV validation
        $('#cvv').on('input', function() {
            var value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });

        // USDT TRC20 logic: get wallet address from backend (pipelines.usdt, fallback to clients.usdt)
        var usdtWalletAddress = @json($usdtWalletAddress ?? '');
        if (usdtWalletAddress) {
            $('#wallet_address').val(usdtWalletAddress);
            generateQrCode(usdtWalletAddress);
            $('#cryptoQrCode').show();
        } else {
            $('#wallet_address').val('');
            $('#cryptoQrCode').hide();
        }
        $('#copyWalletBtn').on('click', function() {
            var address = $('#wallet_address').val();
            if (address) {
                navigator.clipboard.writeText(address);
                alert('Wallet address copied!');
            }
        });

        function generateQrCode(address) {
            // Use a free QR code API for demo
            var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=170x170&data=' + encodeURIComponent(address);
            var qrHtml = `
                <div class="bg-white border rounded shadow-sm p-3 d-flex flex-column align-items-center" style="max-width: 210px;">
                    <img src="${qrUrl}" alt="QR Code" class="img-fluid mb-2" style="width: 170px; height: 170px; object-fit: contain;" />
                    <div class="text-center small text-muted">Scan this code to deposit</div>
                </div>
            `;
            $('#cryptoQrCode').html(qrHtml).show();
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

                countryewallet.forEach((ewallet) => {
                        const option = document.createElement("option");
                        option.value = ewallet.id;

                        option.textContent = window.currentAppLocale === 'ar' 
                        ? ewallet.name_ar
                        : ewallet.name_en;

                        option.setAttribute("data-fields", JSON.stringify(ewallet.fields || []));

                        ewallet_select.appendChild(option);
                    });
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

                        const localizedFieldValue = window.currentAppLocale === 'ar' 
                            ? field.arabic_field_value 
                            : field.english_field_value;

                        fieldGroup.innerHTML = `
                            <label class="form-label">${localizedFieldName}</label>
                            
                            <input type="hidden" name="extra_fields[${field.id}]" value="${localizedFieldValue}">
                            <p class="form-control">${localizedFieldValue}</p>
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

<style>
    /* Dark theme styling for deposit history tabs and table */
    [data-theme="dark"] .nav-tabs .nav-link {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link:hover {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link.active {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .table td {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .table th {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .table tbody tr td {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .table-striped tbody tr:nth-of-type(odd) td {
        color: #ffffff !important;
    }
    
    [data-theme="dark"] .table-striped tbody tr:nth-of-type(even) td {
        color: #ffffff !important;
    }
</style>

@endsection

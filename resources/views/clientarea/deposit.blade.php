@extends('layouts.client')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">

<style>
    .bs-stepper-header {
        display: flex;
        justify-content: center;
        gap: 5px;
    }
    @media (max-width: 2    76px) {
        .btn {
            width: 100%;
        }
    }
    select.form-select {
        min-height: 45px;
        font-size: 16px;
    }
    .nav-tabs .nav-link {
        color: white !important;
    }
    .nav-tabs .nav-link.active {
        color: black !important;
        background-color: white !important;
    }
</style>

@section('content')
<div class="container mt-5 p-0 pt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="text-white">{{__('web.deposits')}}</h4>
        @isset (auth()->guard('client')->user()->options['enableDepositRequest'])
            <button class="btn" style="background-color: #b2d356; color: white; border-radius: 55px;" data-bs-toggle="modal" data-bs-target="#depositModal">{{__('web.deposit')}}</button>
        @endif
    </div>

    <ul class="nav nav-tabs mt-3" id="depositTabs">
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
                            <th style="font-size: 10px;  color: white;">{{__('web.date_of_deposit')}}</th>
                            <th style="font-size: 10px;  color: white;">{{__('web.status')}}</th>
                            <th style="font-size: 10px;  color: white;">{{__('web.payment_method')}}</th>
                            <th style="font-size: 10px;  color: white;">{{__('web.amount')}}</th>
                            <th style="font-size: 10px;  color: white;">{{__('web.currency')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingDeposits as $deposit)
                            <tr>
                                <td style="font-size: 10px; color: white;">{{ $deposit->created_at->format('Y-m-d') }}</td>
                                <td style="font-size: 10px; color: white;">{{__('web.'.$deposit->status)}}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->usdt ? 'USDT' : 'Bank Transfer' }}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->amount }}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->bank_details['currency'] ?? 'USD' }}</td>
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
                            <th style="font-size: 10px; color: white;">{{__('web.date_of_deposit')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.payment_method')}}</th>
                            <th style="font-size: 10px;  color: white;">{{__('web.comment')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.status')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.amount')}}</th>
                            <th style="font-size: 10px; color: white;">{{__('web.currency')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nonPendingDeposits as $deposit)
                            <tr>
                                <td style="font-size: 10px; color: white;">{{ $deposit->created_at->format('Y-m-d') }}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->usdt ? 'USDT' : 'Bank Transfer' }}</td>
                                <td style="font-size: 10px; color: white;">{!! $deposit->comment !!}</td>
                                <td style="font-size: 10px; color: white;">{{__('web.'.$deposit->status)}}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->amount }}</td>
                                <td style="font-size: 10px; color: white;">{{ $deposit->bank_details['currency'] ?? 'USD' }}</td>
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
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content" style="border-radius: 15px; background: linear-gradient(to bottom, #0b1a22 0%, #1c3b44 30%, #5f767b 60%, #d6d6d6 100%);">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="depositModalLabel">{{__('web.deposit')}}</h5>
                <button type="button" class="btn-close text" data-bs-dismiss="modal" aria-label="Close" style="background-color: white"></button>
            </div>
            <div class="modal-body container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div id="stepper1" class="bs-stepper linear">
                            <div class="bs-stepper-header d-flex" role="tablist">
                                <div class="step" data-target="#step-1">
                                    <button type="button" class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="step-1" style="font-size: x-small;">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label text-white">{{__('web.choose_payment_method')}}</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#step-2">
                                    <button type="button" class="step-trigger" role="tab" id="stepper1trigger2" style="font-size: x-small;" aria-controls="step-2">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label text-white">{{__('web.payment_details')}}</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#step-3">
                                    <button type="button" class="step-trigger" role="tab" id="stepper1trigger3" style="font-size: x-small;" aria-controls="step-3">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label text-white">{{__('web.upload_receipt')}}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <form id="depositForm" method="POST" action="{{ route('deposit.process') }}" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Step 1: Payment Method Selection -->
                                    <div id="step-1" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger1">
                                        <div class="mb-3">
                                            <label for="payment-method" class="form-label text-white">{{__('web.payment_method')}} *</label>
                                            <select class="form-select" id="payment-method" name="payment_method" required>
                                                <option selected>{{__('web.choose_payment_method')}}</option>
                                                <option value="usdt">USDT</option>
                                                <option value="bank">Bank Transfer</option>
                                            </select>
                                        </div>
                                        <button type="button" class="btn" style="background-color: #b2d356; color:white;" onclick="stepper1.next()">{{__('web.next')}}</button>
                                    </div>

                                    <!-- Step 2: Payment Details -->
                                    <div id="step-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">
                                        <!-- USDT Details -->
                                        <div id="usdt-details" class="payment-details" style="display: none;">
                                            <div class="mb-3">
                                                <label for="usdt-address" class="form-label text-white">{{__('web.usdt_address')}}</label>
                                                <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{auth()->guard('client')->user()->usdt??auth()->guard('client')->user()->pipeline->usdt['BNC']??''}}" readonly>
                                            </div>
                                        </div>

                                        <!-- Bank Transfer Details -->
                                        <div id="bank-details" class="payment-details" style="display: none;">
                                            <div class="mb-3">
                                                <label for="bank-country" class="form-label text-white">{{__('web.bank_country')}} *</label>
                                                <select id="bank-country" class="single-select form-select inside-modal" name="country">
                                                    <option value="Afghanistan">Afghanistan</option>
                                                    <option value="Åland Islands">Åland Islands</option>
                                                    <option value="Albania">Albania</option>
                                                    <option value="Algeria">Algeria</option>
                                                    <option value="American Samoa">American Samoa</option>
                                                    <option value="Andorra">Andorra</option>
                                                    <option value="Angola">Angola</option>
                                                    <option value="Anguilla">Anguilla</option>
                                                    <option value="Antarctica">Antarctica</option>
                                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                    <option value="Argentina">Argentina</option>
                                                    <option value="Armenia">Armenia</option>
                                                    <option value="Aruba">Aruba</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Austria">Austria</option>
                                                    <option value="Azerbaijan">Azerbaijan</option>
                                                    <option value="Bahamas">Bahamas</option>
                                                    <option value="Bahrain">Bahrain</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Barbados">Barbados</option>
                                                    <option value="Belarus">Belarus</option>
                                                    <option value="Belgium">Belgium</option>
                                                    <option value="Belize">Belize</option>
                                                    <option value="Benin">Benin</option>
                                                    <option value="Bermuda">Bermuda</option>
                                                    <option value="Bhutan">Bhutan</option>
                                                    <option value="Bolivia">Bolivia</option>
                                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                    <option value="Botswana">Botswana</option>
                                                    <option value="Bouvet Island">Bouvet Island</option>
                                                    <option value="Brazil">Brazil</option>
                                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic</option>
                                                    <option value="Chad">Chad</option>
                                                    <option value="Chile">Chile</option>
                                                    <option value="China">China</option>
                                                    <option value="Christmas Island">Christmas Island</option>
                                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                    <option value="Colombia">Colombia</option>
                                                    <option value="Comoros">Comoros</option>
                                                    <option value="Congo">Congo</option>
                                                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                                    <option value="Cook Islands">Cook Islands</option>
                                                    <option value="Costa Rica">Costa Rica</option>
                                                    <option value="Cote D'ivoire">Cote D'ivoire</option>
                                                    <option value="Croatia">Croatia</option>
                                                    <option value="Cuba">Cuba</option>
                                                    <option value="Cyprus">Cyprus</option>
                                                    <option value="Czech Republic">Czech Republic</option>
                                                    <option value="Denmark">Denmark</option>
                                                    <option value="Djibouti">Djibouti</option>
                                                    <option value="Dominica">Dominica</option>
                                                    <option value="Dominican Republic">Dominican Republic</option>
                                                    <option value="Ecuador">Ecuador</option>
                                                    <option value="Egypt">Egypt</option>
                                                    <option value="El Salvador">El Salvador</option>
                                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                    <option value="Eritrea">Eritrea</option>
                                                    <option value="Estonia">Estonia</option>
                                                    <option value="Ethiopia">Ethiopia</option>
                                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                                    <option value="Faroe Islands">Faroe Islands</option>
                                                    <option value="Fiji">Fiji</option>
                                                    <option value="Finland">Finland</option>
                                                    <option value="France">France</option>
                                                    <option value="French Guiana">French Guiana</option>
                                                    <option value="French Polynesia">French Polynesia</option>
                                                    <option value="French Southern Territories">French Southern Territories</option>
                                                    <option value="Gabon">Gabon</option>
                                                    <option value="Gambia">Gambia</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Germany">Germany</option>
                                                    <option value="Ghana">Ghana</option>
                                                    <option value="Gibraltar">Gibraltar</option>
                                                    <option value="Greece">Greece</option>
                                                    <option value="Greenland">Greenland</option>
                                                    <option value="Grenada">Grenada</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Guam">Guam</option>
                                                    <option value="Guatemala">Guatemala</option>
                                                    <option value="Guernsey">Guernsey</option>
                                                    <option value="Guinea">Guinea</option>
                                                    <option value="Guinea-bissau">Guinea-bissau</option>
                                                    <option value="Guyana">Guyana</option>
                                                    <option value="Haiti">Haiti</option>
                                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                                    <option value="Honduras">Honduras</option>
                                                    <option value="Hong Kong">Hong Kong</option>
                                                    <option value="Hungary">Hungary</option>
                                                    <option value="Iceland">Iceland</option>
                                                    <option value="India">India</option>
                                                    <option value="Indonesia">Indonesia</option>
                                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                                    <option value="Iraq">Iraq</option>
                                                    <option value="Ireland">Ireland</option>
                                                    <option value="Isle of Man">Isle of Man</option>
                                                    <option value="Israel">Israel</option>
                                                    <option value="Italy">Italy</option>
                                                    <option value="Jamaica">Jamaica</option>
                                                    <option value="Japan">Japan</option>
                                                    <option value="Jersey">Jersey</option>
                                                    <option value="Jordan">Jordan</option>
                                                    <option value="Kazakhstan">Kazakhstan</option>
                                                    <option value="Kenya">Kenya</option>
                                                    <option value="Kiribati">Kiribati</option>
                                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                                    <option value="Korea, Republic of">Korea, Republic of</option>
                                                    <option value="Kuwait">Kuwait</option>
                                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                                    <option value="Latvia">Latvia</option>
                                                    <option value="Lebanon">Lebanon</option>
                                                    <option value="Lesotho">Lesotho</option>
                                                    <option value="Liberia">Liberia</option>
                                                    <option value="Libya">Libya</option>
                                                    <option value="Liechtenstein">Liechtenstein</option>
                                                    <option value="Lithuania">Lithuania</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Macao">Macao</option>
                                                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                                    <option value="Madagascar">Madagascar</option>
                                                    <option value="Malawi">Malawi</option>
                                                    <option value="Malaysia">Malaysia</option>
                                                    <option value="Maldives">Maldives</option>
                                                    <option value="Mali">Mali</option>
                                                    <option value="Malta">Malta</option>
                                                    <option value="Marshall Islands">Marshall Islands</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Mauritania">Mauritania</option>
                                                    <option value="Mauritius">Mauritius</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                    <option value="Mexico">Mexico</option>
                                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                                    <option value="Monaco">Monaco</option>
                                                    <option value="Mongolia">Mongolia</option>
                                                    <option value="Montenegro">Montenegro</option>
                                                    <option value="Montserrat">Montserrat</option>
                                                    <option value="Morocco">Morocco</option>
                                                    <option value="Mozambique">Mozambique</option>
                                                    <option value="Myanmar">Myanmar</option>
                                                    <option value="Namibia">Namibia</option>
                                                    <option value="Nauru">Nauru</option>
                                                    <option value="Nepal">Nepal</option>
                                                    <option value="Netherlands">Netherlands</option>
                                                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                    <option value="New Caledonia">New Caledonia</option>
                                                    <option value="New Zealand">New Zealand</option>
                                                    <option value="Nicaragua">Nicaragua</option>
                                                    <option value="Niger">Niger</option>
                                                    <option value="Nigeria">Nigeria</option>
                                                    <option value="Niue">Niue</option>
                                                    <option value="Norfolk Island">Norfolk Island</option>
                                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                    <option value="Norway">Norway</option>
                                                    <option value="Oman">Oman</option>
                                                    <option value="Pakistan">Pakistan</option>
                                                    <option value="Palau">Palau</option>
                                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                                    <option value="Panama">Panama</option>
                                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                                    <option value="Paraguay">Paraguay</option>
                                                    <option value="Peru">Peru</option>
                                                    <option value="Philippines">Philippines</option>
                                                    <option value="Pitcairn">Pitcairn</option>
                                                    <option value="Poland">Poland</option>
                                                    <option value="Portugal">Portugal</option>
                                                    <option value="Puerto Rico">Puerto Rico</option>
                                                    <option value="Qatar">Qatar</option>
                                                    <option value="Reunion">Reunion</option>
                                                    <option value="Romania">Romania</option>
                                                    <option value="Russia">Russia</option>
                                                    <option value="Rwanda">Rwanda</option>
                                                    <option value="Saint Helena">Saint Helena</option>
                                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                    <option value="Saint Lucia">Saint Lucia</option>
                                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                                    <option value="Samoa">Samoa</option>
                                                    <option value="San Marino">San Marino</option>
                                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                                    <option value="Senegal">Senegal</option>
                                                    <option value="Serbia">Serbia</option>
                                                    <option value="Seychelles">Seychelles</option>
                                                    <option value="Sierra Leone">Sierra Leone</option>
                                                    <option value="Singapore">Singapore</option>
                                                    <option value="Slovakia">Slovakia</option>
                                                    <option value="Slovenia">Slovenia</option>
                                                    <option value="Solomon Islands">Solomon Islands</option>
                                                    <option value="Somalia">Somalia</option>
                                                    <option value="South Africa">South Africa</option>
                                                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="Sri Lanka">Sri Lanka</option>
                                                    <option value="Sudan">Sudan</option>
                                                    <option value="Suriname">Suriname</option>
                                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                    <option value="Eswatini">Eswatini</option>
                                                    <option value="Sweden">Sweden</option>
                                                    <option value="Switzerland">Switzerland</option>
                                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                                    <option value="Taiwan">Taiwan</option>
                                                    <option value="Tajikistan">Tajikistan</option>
                                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                                    <option value="Thailand">Thailand</option>
                                                    <option value="Timor-leste">Timor-leste</option>
                                                    <option value="Togo">Togo</option>
                                                    <option value="Tokelau">Tokelau</option>
                                                    <option value="Tonga">Tonga</option>
                                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                    <option value="Tunisia">Tunisia</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Turkmenistan">Turkmenistan</option>
                                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                    <option value="Tuvalu">Tuvalu</option>
                                                    <option value="Uganda">Uganda</option>
                                                    <option value="Ukraine">Ukraine</option>
                                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                                    <option value="United Kingdom">United Kingdom</option>
                                                    <option value="United States">United States</option>
                                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                    <option value="Uruguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Vietnam">Vietnam</option>
                                                    <option value="Virgin Islands, British">Virgin Islands, British</option>
                                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                                    <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                    <option value="Western Sahara">Western Sahara</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="bank-name" class="form-label text-white">{{__('web.select_bank')}} *</label>
                                                <select class="form-select" id="bank-name" name="bank">
                                                    <option>{{__('web.select_bank')}}</option>
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
                                                <p id="no-bank-message" class="text-warning mt-2" style="display: none;">
                                                    {{ __('web.no_bank_in_country') }}. <a href="#">{{ __('web.contact_us') }}</a>.
                                                </p>
                                            </div>
                                            <div id="bank-info" style="display: none;">
                                                <h5 class="mb-1 text-white">{{__('web.bank_information')}}</h5>
                                                <br>
                                                <p class="text-white"><strong>{{__('web.bank_name')}}:           </strong> <span id="bank-name-detail">                </span></p>
                                                <p class="text-white"><strong>{{__('web.address')}}:             </strong> <span id="bank-address-detail">             </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.swift')}}:               </strong> <span id="bank-swift-code-detail">          </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.iban')}}:                </strong> <span id="bank-iban-detail">                </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.account_number')}}:      </strong> <span id="bank-account-number-detail">      </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.beneficiary_name')}}:    </strong> <span id="bank-beneficiary-name-detail">    </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.beneficiary_address')}}: </strong> <span id="bank-beneficiary-address-detail"> </span class="text-white"></p>
                                                <p class="text-white"><strong>{{__('web.beneficiary_country')}}: </strong> <span id="bank-beneficiary-country-detail"> </span></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="amount" class="form-label text-white"><strong>{{__('web.amount')}} *</strong></label>
                                            <input type="number" class="form-control" id="amount" name="amount" required>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-secondary" onclick="stepper1.previous()">{{__('web.previous')}}</button>
                                            <button type="button" class="btn btn-primary" onclick="stepper1.next()" style="background-color: #b2d356; color:white;">{{__('web.next')}}</button>
                                        </div>
                                    </div>

                                    <!-- Step 3: Upload Receipt -->
                                    <div id="step-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                                        <h5 class="mb-4 text-white">{{__('web.upload_your_receipt')}}</h5>
                                        <div class="mb-3">
                                            <label for="receipt" class="form-label text-white">{{__('web.upload_receipt')}} *</label>
                                            <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*" required>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <button type="button" class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i class="bx bx-left-arrow-alt me-2"></i>{{__('web.previous')}}</button>
                                            <button type="submit" class="btn px-4" style="background-color: #b2d356; color:white;">{{__('web.submit')}}</button>
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

<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const countrySelect = document.getElementById("bank-country");
        const bankSelect = document.getElementById("bank-name");
        const noBankMessage = document.getElementById("no-bank-message");

        countrySelect.addEventListener("change", function() {
            const selectedCountry = this.value;
            let hasBanks = false;

            // Show all banks first
            Array.from(bankSelect.options).forEach(option => {
                if (option.value === "") return; // Keep default option visible

                if (option.dataset.country === selectedCountry) {
                    option.style.display = "block";
                    hasBanks = true;
                } else {
                    option.style.display = "none";
                }
            });

            // Show/hide the "No bank available" message
            if (!hasBanks) {
                noBankMessage.style.display = "block";
                bankSelect.value = ""; // Reset the bank selection
            } else {
                noBankMessage.style.display = "none";
            }
        });
    });
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

<script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
@endsection
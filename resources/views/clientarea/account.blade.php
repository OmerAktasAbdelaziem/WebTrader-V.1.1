@extends('layouts.mobile')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
<link href="{{ url('css/webtrader.css') }}" rel="stylesheet" />
<link href="{{ url('css/webtrader2.css') }}" rel="stylesheet" />
@php
    // Process USDT address to handle JSON format properly
    $client = auth()->guard('client')->user();
    $usdtAddress = '';
    
    if ($client) {
        // First try client's usdt column
        if (!empty($client->usdt)) {
            $clientUsdt = $client->usdt;
            
            // Handle JSON format like {"phoenix":"address1","BNC":"address2"}
            if (is_string($clientUsdt)) {
                $decodedUsdt = json_decode($clientUsdt, true);
                if (is_array($decodedUsdt)) {
                    // Get address based on client source
                    if ($client->source == 'BNC' && !empty($decodedUsdt['BNC'])) {
                        $usdtAddress = $decodedUsdt['BNC'];
                    } elseif (!empty($decodedUsdt['phoenix'])) {
                        $usdtAddress = $decodedUsdt['phoenix'];
                    } else {
                        // Get the first non-null address
                        foreach ($decodedUsdt as $key => $address) {
                            if (!empty($address) && $address !== null) {
                                $usdtAddress = $address;
                                break;
                            }
                        }
                    }
                } else {
                    $usdtAddress = $clientUsdt;
                }
            }
        }
        // If client usdt is empty, try pipeline
        elseif (!empty($client->pipeline_id)) {
            $pipeline = \App\Models\Pipeline::find($client->pipeline_id);
            
            if ($pipeline && !empty($pipeline->usdt)) {
                $pipelineUsdt = $pipeline->usdt;
                
                if (is_string($pipelineUsdt)) {
                    $decodedUsdt = json_decode($pipelineUsdt, true);
                    if (is_array($decodedUsdt)) {
                        // Get address based on client source
                        if ($client->source == 'BNC' && !empty($decodedUsdt['BNC'])) {
                            $usdtAddress = $decodedUsdt['BNC'];
                        } elseif (!empty($decodedUsdt['phoenix'])) {
                            $usdtAddress = $decodedUsdt['phoenix'];
                        } else {
                            // Get the first non-null address
                            foreach ($decodedUsdt as $key => $address) {
                                if (!empty($address) && $address !== null) {
                                    $usdtAddress = $address;
                                    break;
                                }
                            }
                        }
                    } else {
                        $usdtAddress = trim($pipelineUsdt);
                    }
                } elseif (is_array($pipelineUsdt)) {
                    // Get address based on client source
                    if ($client->source == 'BNC' && !empty($pipelineUsdt['BNC'])) {
                        $usdtAddress = $pipelineUsdt['BNC'];
                    } elseif (!empty($pipelineUsdt['phoenix'])) {
                        $usdtAddress = $pipelineUsdt['phoenix'];
                    } else {
                        // Get the first non-null address
                        foreach ($pipelineUsdt as $key => $address) {
                            if (!empty($address) && $address !== null) {
                                $usdtAddress = $address;
                                break;
                            }
                        }
                    }
                }
            }
        }
    }
@endphp

<style>
    .account-container {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(30, 185, 116, 0.08), 0 1.5px 6px rgba(70, 153, 217, 0.07);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        max-width: 430px;
        margin: 2rem auto;
    }
    .account-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1EBC74 60%, #4699D9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 0.75rem;
        box-shadow: 0 2px 8px rgba(30, 185, 116, 0.12);
    }
    .account-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.25rem;
    }
    .account-id {
        font-size: 0.95rem;
        color: #888;
        margin-bottom: 0.5rem;
    }
    .account-type {
        font-size: 0.95rem;
        color: #1EBC74;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .account-date {
        font-size: 0.9rem;
        color: #aaa;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #4699D9;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        border-bottom: 1.5px solid #e3e3e3;
        padding-bottom: 0.25rem;
    }
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }
    .info-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f2f2f2;
        font-size: 1rem;
    }
    .info-list li:last-child {
        border-bottom: none;
    }
    .info-label {
        color: #888;
        font-weight: 500;
    }
    .info-value {
        color: #222;
        font-weight: 600;
    }
    .info-value.positive {
        color: #1EBC74;
    }
    .info-value.negative {
        color: #e74c3c;
    }
    .logout-btn {
        width: 100%;
        padding: 12px;
        border: none;
        background: linear-gradient(90deg, #1EBC74 60%, #4699D9 100%);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        margin-top: 1.5rem;
        box-shadow: 0 2px 8px rgba(30, 185, 116, 0.10);
        transition: background 0.2s;
    }
    .logout-btn:hover {
        background: linear-gradient(90deg, #4699D9 60%, #1EBC74 100%);
    }
    @media (max-width: 600px) {
        .account-container {
            padding: 1.2rem 0.5rem 1rem 0.5rem;
        }
        .avatar {
            width: 60px;
            height: 60px;
            font-size: 1.7rem;
        }
    }

    /* Dark Theme Styles */
    [data-theme="dark"] .account-container {
        background: #1C1F26 !important;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3), 0 1.5px 6px rgba(0, 0, 0, 0.2) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .avatar {
        background: linear-gradient(135deg, #FFD700 60%, #e6c200 100%) !important;
        color: #121624 !important;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3) !important;
    }

    [data-theme="dark"] .account-title {
        color: #ffffff !important;
    }

    [data-theme="dark"] .account-id {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .account-type {
        color: #FFD700 !important;
    }

    [data-theme="dark"] .account-balance {
        color: #FFD700 !important;
    }

    [data-theme="dark"] .account-date {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .section-title {
        color: #FFD700 !important;
        border-bottom: 1.5px solid #2a2d3a !important;
    }

    [data-theme="dark"] .info-list li {
        border-bottom: 1px solid #2a2d3a !important;
    }

    [data-theme="dark"] .info-label {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .info-value {
        color: #ffffff !important;
    }

    [data-theme="dark"] .info-value.positive {
        color: #FFD700 !important;
    }

    [data-theme="dark"] .info-value.negative {
        color: #ff6b6b !important;
    }

    [data-theme="dark"] .logout-btn {
        background: linear-gradient(90deg, #FFD700 60%, #e6c200 100%) !important;
        color: #121624 !important;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3) !important;
    }

    [data-theme="dark"] .logout-btn:hover {
        background: linear-gradient(90deg, #e6c200 60%, #FFD700 100%) !important;
        color: #121624 !important;
    }

    /* Dark Theme Modal Styles */
    [data-theme="dark"] .modal-content {
        background: #1C1F26 !important;
        border: 1px solid #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .modal-header {
        background: #121624 !important;
        border-bottom: 1px solid #2a2d3a !important;
    }

    [data-theme="dark"] .modal-title {
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-close {
        filter: invert(1) !important;
    }

    [data-theme="dark"] .modal-body {
        background: #1C1F26 !important;
    }

    /* Dark Theme Stepper Styles */
    [data-theme="dark"] .bs-stepper {
        background: #1C1F26 !important;
    }

    [data-theme="dark"] .bs-stepper-header {
        background: #121624 !important;
        border-bottom: 1px solid #2a2d3a !important;
    }

    [data-theme="dark"] .step-trigger {
        background: transparent !important;
        color: #b0b3b8 !important;
        border: none !important;
    }

    [data-theme="dark"] .bs-stepper-circle {
        background: #2a2d3a !important;
        color: #ffffff !important;
        border: 2px solid #FFD700 !important;
    }

    [data-theme="dark"] .step.active .bs-stepper-circle {
        background: #FFD700 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .bs-stepper-label {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .step.active .bs-stepper-label {
        color: #FFD700 !important;
    }

    [data-theme="dark"] .line {
        background: #2a2d3a !important;
    }

    [data-theme="dark"] .bs-stepper-content {
        background: #1C1F26 !important;
    }

    /* Dark Theme Form Controls */
    [data-theme="dark"] .form-label {
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
        background: #141927 !important;
        border: 1px solid #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        background: #141927 !important;
        border-color: #FFD700 !important;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.25) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control::placeholder {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .form-select option {
        background: #141927 !important;
        color: #ffffff !important;
    }

    /* Dark Theme Button Styles */
    [data-theme="dark"] .btn-primary {
        background: #FFD700 !important;
        border-color: #FFD700 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-primary:hover {
        background: #e6c200 !important;
        border-color: #e6c200 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-secondary {
        background: #2a2d3a !important;
        border-color: #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-secondary:hover {
        background: #3a3d4a !important;
        border-color: #3a3d4a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .btn-success {
        background: #FFD700 !important;
        border-color: #FFD700 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-success:hover {
        background: #e6c200 !important;
        border-color: #e6c200 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-outline-secondary {
        background: transparent !important;
        border-color: #FFD700 !important;
        color: #FFD700 !important;
    }

    [data-theme="dark"] .btn-outline-secondary:hover {
        background: #FFD700 !important;
        border-color: #FFD700 !important;
        color: #121624 !important;
    }

    /* Dark Theme Withdraw Modal Sidebar */
    [data-theme="dark"] .modal-body .col-md-4 {
        background: #121624 !important;
    }

    [data-theme="dark"] .modal-body .col-md-4 h5 {
        color: #ffffff !important;
    }

    [data-theme="dark"] .nav-pills .nav-link {
        color: #FFD700 !important;
        background: transparent !important;
    }

    [data-theme="dark"] .nav-pills .nav-link.active {
        background: #FFD700 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .nav-pills .nav-link:hover {
        background: rgba(255, 215, 0, 0.1) !important;
        color: #FFD700 !important;
    }

    /* Dark Theme Bank Information Display */
    [data-theme="dark"] #bank-info {
        background: #141927 !important;
        border: 1px solid #2a2d3a !important;
        border-radius: 8px !important;
        padding: 1rem !important;
        margin-top: 1rem !important;
    }

    [data-theme="dark"] #bank-info h5 {
        color: #FFD700 !important;
        border-bottom: 1px solid #2a2d3a !important;
        padding-bottom: 0.5rem !important;
    }

    [data-theme="dark"] #bank-info p {
        color: #ffffff !important;
        margin-bottom: 0.5rem !important;
    }

    [data-theme="dark"] #bank-info strong {
        color: #b0b3b8 !important;
    }
</style>

@section('content')

<div class="account-container">
    <div class="account-header">
        <div class="avatar">
            <span class="iconify" data-icon="mdi:account-circle"></span>
        </div>
        <div class="account-title">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="account-id">ID: {{ $user->id }}</div>
        <div class="account-type">{{__('web.'.$user->account_type??'Demo')}}</div>
        <div class="account-balance" style="font-size:1.1rem;color:#1EBC74;font-weight:600;margin-bottom:0.5rem;">
            $ {{ number_format($finance['balance'] ?? 0, 2, '.', ',') }} USD
        </div>
    </div>

    <div class="section-title"><span class="iconify" data-icon="mdi:information-outline"></span> {{__('web.personal_information')}}</div>
    <ul class="info-list">
        <li><span class="info-label">{{__('web.name')}}</span><span class="info-value">{{ $user->first_name }} {{ $user->last_name }}</span></li>
        <li><span class="info-label">{{__('web.id')}}</span><span class="info-value">{{ $user->id }}</span></li>
        <li><span class="info-label">{{__('web.account_type')}}</span><span class="info-value">{{__('web.'.$user->account_type??'Demo')}}</span></li>
        <li><span class="info-label">{{__('web.registration_date')}}</span><span class="info-value">{{ $user->reg_date->format('m/d/Y') }}</span></li>
    </ul>

    <div class="section-title"><span class="iconify" data-icon="mdi:chart-line"></span> {{__('web.trading_information')}}</div>
    <ul class="info-list">
        <li><span class="info-label">{{__('web.currency')}}</span><span class="info-value">USD</span></li>
        <li><span class="info-label">{{__('web.leverage')}}</span><span class="info-value">{{$user->leverage??'1:500'}}</span></li>
        <li><span class="info-label">{{__('web.profitloss')}}</span><span class="info-value @if (isset($finance['currentPL']) && $finance['currentPL'] < 0) negative @else positive @endif">{{number_format(isset($finance['currentPL']) ? $finance['currentPL'] : 0, '3','.',',')}} $</span></li>
    </ul>

    <form action="{{ route('client.logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">{{__('web.logout')}}</button>
    </form>
</div>

<div class="document-content-modern">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- KYC Documents Card -->
            <div class="col-lg-6">
                <div class="document-card-modern kyc-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon-section">
                            <div class="icon-bg-modern kyc-icon-bg">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="header-text-section">
                                <h3 class="card-title-modern">{{ __('web.kyc_documents') }}</h3>
                                <p class="card-subtitle-modern">{{ __('web.identity_verification') }}</p>
                                <div class="requirement-badges">
                                    <span class="badge-modern required">{{ __('web.required') }}</span>
                                    <span class="badge-modern one-time">{{ __('web.one_time_upload') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-modern">
                        <!-- Upload Zone -->
                        <div class="upload-zone-modern" id="kycUploadZone">
                            <div class="dropzone-modern" id="kycDropzone" ondrop="dropHandler(event, 'kyc')" ondragover="dragOverHandler(event)" ondragenter="dragEnterHandler(event)" ondragleave="dragLeaveHandler(event)">
                                <div class="dropzone-content-modern">
                                    <div class="upload-icon-modern kyc-upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                    <h4 class="upload-title-modern">{{ __('web.drag_drop_browse') }}</h4>
                                    <p class="upload-description-modern">{{ __('web.or_click_browse') }}</p>
                                    <div class="file-types-modern">
                                        <span class="file-type-badge">PDF</span>
                                        <span class="file-type-badge">JPG</span>
                                        <span class="file-type-badge">PNG</span>
                                    </div>
                                    <p class="size-limit-modern">{{ __('web.maximum_file_size') }}</p>
                                    <button type="button" class="btn-upload-modern kyc-btn" onclick="triggerKycFileInput()">
                                        <i class="bi bi-folder-plus"></i>
                                        <span>{{ __('web.choose_files') }}</span>
                                    </button>
                                    <input type="file" id="kycFileInput" multiple accept=".pdf,.jpg,.jpeg,.png" style="display: none;" onchange="handleFileSelect(event, 'kyc')">
                                </div>
                            </div>
                        </div>

                        <!-- Document Requirements -->
                        <div class="requirements-section-modern">
                            <h5 class="requirements-title">{{ __('web.required_documents') }}</h5>
                            <div class="requirements-list">
                                <div class="requirement-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <span>{{ __('web.government_id') }}</span>
                                </div>
                                <div class="requirement-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <span>{{ __('web.proof_address') }}</span>
                                </div>
                                <div class="requirement-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <span>{{ __('web.selfie_with_id') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded Files Display -->
                        <div class="uploaded-files-modern" id="kycFilesList" style="display: none;">
                            <div class="files-header-modern">
                                <h5><i class="bi bi-files"></i> Uploaded KYC Documents</h5>
                                <span class="files-count" id="kycFilesCount">0 files</span>
                            </div>
                            <div class="files-grid-modern" id="kycFilesContainer">
                                <!-- Files will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Documents Card -->
            <div class="col-lg-6">
                <div class="document-card-modern additional-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon-section">
                            <div class="icon-bg-modern additional-icon-bg">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="header-text-section">
                                <h3 class="card-title-modern">Additional Documents</h3>
                                <p class="card-subtitle-modern">Supporting documents and certificates</p>
                                <div class="requirement-badges">
                                    <span class="badge-modern optional">Optional</span>
                                    <span class="badge-modern multiple">Multiple Uploads</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body-modern">
                        <!-- Upload Zone -->
                        <div class="upload-zone-modern" id="additionalUploadZone">
                            <div class="dropzone-modern" id="additionalDropzone" ondrop="dropHandler(event, 'additional')" ondragover="dragOverHandler(event)" ondragenter="dragEnterHandler(event)" ondragleave="dragLeaveHandler(event)">
                                <div class="dropzone-content-modern">
                                    <div class="upload-icon-modern additional-upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                    <h4 class="upload-title-modern">Drop additional documents here</h4>
                                    <p class="upload-description-modern">or click to browse from your computer</p>
                                    <div class="file-types-modern">
                                        <span class="file-type-badge">PDF</span>
                                        <span class="file-type-badge">DOC</span>
                                        <span class="file-type-badge">DOCX</span>
                                        <span class="file-type-badge">JPG</span>
                                        <span class="file-type-badge">PNG</span>
                                    </div>
                                    <p class="size-limit-modern">Maximum file size: 10MB per file</p>
                                    <button type="button" class="btn-upload-modern additional-btn" onclick="triggerAdditionalFileInput()">
                                        <i class="bi bi-folder-plus"></i>
                                        <span>Choose Files</span>
                                    </button>
                                    <input type="file" id="additionalFileInput" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="display: none;" onchange="handleFileSelect(event, 'additional')">
                                </div>
                            </div>
                        </div>

                        <!-- Document Types -->
                        <div class="document-types-section-modern">
                            <h5 class="document-types-title">Accepted Document Types:</h5>
                            <div class="document-types-grid">
                                <div class="document-type-item">
                                    <i class="bi bi-building text-primary"></i>
                                    <span>Business Certificates</span>
                                </div>
                                <div class="document-type-item">
                                    <i class="bi bi-award text-success"></i>
                                    <span>Professional Licenses</span>
                                </div>
                                <div class="document-type-item">
                                    <i class="bi bi-bank text-warning"></i>
                                    <span>Financial Statements</span>
                                </div>
                                <div class="document-type-item">
                                    <i class="bi bi-file-text text-info"></i>
                                    <span>Supporting Documents</span>
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded Files Display -->
                        <div class="uploaded-files-modern" id="additionalFilesList" style="display: none;">
                            <div class="files-header-modern">
                                <h5><i class="bi bi-files"></i> Uploaded Additional Documents</h5>
                                <span class="files-count" id="additionalFilesCount">0 files</span>
                            </div>
                            <div class="files-grid-modern" id="additionalFilesContainer">
                                <!-- Files will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Progress Section -->
        <div class="row mt-4" id="uploadProgressSection" style="display: none;">
            <div class="col-12">
                <div class="progress-card-modern">
                    <div class="progress-header-modern">
                        <div class="progress-icon-modern">
                            <i class="bi bi-cloud-arrow-up"></i>
                        </div>
                        <div class="progress-text-modern">
                            <h5>Uploading Documents...</h5>
                            <p id="progressDescription">Preparing files for upload</p>
                        </div>
                        <div class="progress-percentage-modern">
                            <span id="progressText">0%</span>
                        </div>
                    </div>
                    <div class="progress-bar-modern">
                        <div class="progress-fill-modern" id="progressBar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Status Messages -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="upload-messages-modern" id="uploadMessages">
                    <!-- Success/Error messages will appear here -->
                </div>
            </div>
        </div>

        <!-- Security Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="security-info-modern">
                    <div class="security-icon-modern">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div class="security-content-modern">
                        <h5>Your Documents Are Secure</h5>
                        <p>All uploads are protected with bank-level encryption. Your personal information is handled in accordance with international privacy standards and regulations.</p>
                        <div class="security-features">
                            <span class="security-feature">
                                <i class="bi bi-lock"></i>
                                <span>256-bit SSL Encryption</span>
                            </span>
                            <span class="security-feature">
                                <i class="bi bi-eye-slash"></i>
                                <span>Privacy Protected</span>
                            </span>
                            <span class="security-feature">
                                <i class="bi bi-check-circle"></i>
                                <span>GDPR Compliant</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
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
                                                    <input type="text" class="form-control" id="usdt-address" name="usdt" value="{{ $usdtAddress }}" readonly>
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
        loadExistingFiles();
        
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

<style>
    .file-item-modern{
        flex-direction: column;
    }
    .file-info{
        flex-direction: column;
    }
    .file-name{
        white-space: unset !important;
    }
</style>
<script src="{{ url('assets/js/webtrader2.js') }}"></script>

<!-- Modern Document Upload JavaScript -->
<script src="{{ asset('assets/js/document-upload-modern.js') }}"></script>
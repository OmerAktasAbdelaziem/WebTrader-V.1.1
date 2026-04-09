<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Qtrade - Trading Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="{{ url('css/webtrader.css') }}" rel="stylesheet" />
    <link href="{{ url('css/webtrader2.css') }}" rel="stylesheet" />
    
    <style>
        /* Modern Language Switcher Styles */
        .language-switcher-container {
            position: relative;
            margin-bottom: 1rem;
            padding: 0.5rem;
            text-align: center;
        }

        .language-switcher-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #e0e0e0;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 10;
        }

        .language-switcher-btn:hover {
            background: rgba(79, 140, 255, 0.2);
            border-color: rgba(79, 140, 255, 0.4);
            color: #4f8cff;
            transform: scale(1.1);
        }

        .language-switcher-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 140, 255, 0.3);
        }

        .language-switcher-btn.active {
            background: rgba(79, 140, 255, 0.3);
            border-color: #4f8cff;
            color: #4f8cff;
            transform: scale(1.1);
        }

        .language-dropdown {
            position: absolute;
            top: 150%;
            left: 65px;
            transform: translateY(-50%);
            min-width: 180px;
            background: rgba(25, 30, 36, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(79, 140, 255, 0.2);
            border-radius: 0.75rem;
            box-shadow: 
                0 10px 30px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-50%) translateX(-10px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 20;
            overflow: hidden;
        }

        .language-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0) scale(1);
        }

        .language-option {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background: rgba(79, 140, 255, 0.1);
            color: #4f8cff;
            text-decoration: none;
        }

        .language-option.active {
            background: rgba(79, 140, 255, 0.15);
            color: #4f8cff;
        }

        .option-flag {
            border-radius: 3px;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        .option-name {
            flex: 1;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .active-check {
            font-size: 0.875rem;
            color: #4f8cff;
            opacity: 0.8;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .language-switcher-container {
                padding: 0.25rem;
            }
            
            .language-switcher-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .language-dropdown {
                left: 45px;
                min-width: 160px;
            }
            
            .option-flag {
                width: 16px;
                height: 12px;
            }
            
            .language-option {
                padding: 0.625rem 0.875rem;
            }
            
            .option-name {
                font-size: 0.8rem;
            }
        }

        /* Close dropdown when clicking outside */
        .language-dropdown-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 15;
            background: transparent;
        }

        /* Price Movement Colors */
        .price-up {
            color: #22c55e !important; /* Green for price increase */
            transition: color 0.3s ease;
        }
        
        .price-down {
            color: #ef4444 !important; /* Red for price decrease */
            transition: color 0.3s ease;
        }
        
        .price-unchanged {
            color: #e0e0e0 !important; /* Default color */
            transition: color 0.3s ease;
        }
        
        /* Bid/Ask specific styling */
        .bid_price, .ask_price {
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
        }
        
        .trade-price {
            font-weight: 600;
            font-size: 1.1em;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }
        
        /* Modal fixes for input interaction */
        #editOrderModal, #newOrderModal {
            z-index: 9999 !important;
        }
        
        #editOrderModal .modal-dialog, #newOrderModal .modal-dialog {
            z-index: 10000 !important;
            position: relative;
        }
        
        #editOrderModal .form-control, #newOrderModal .form-control {
            pointer-events: auto !important;
            z-index: 10001 !important;
            position: relative;
        }
        
        #editOrderModal input[type="number"], #newOrderModal input[type="number"] {
            pointer-events: auto !important;
            user-select: auto !important;
            cursor: text !important;
        }
        
        /* Ensure no overlays block the modal */
        .notification-popup-overlay,
        .language-dropdown-overlay {
            z-index: 15 !important;
        }
        
        /* PnL Display Styles */
        .pnl-display {
            font-weight: bold !important;
            font-size: 1em !important;
            display: inline-block !important;
            transition: color 0.3s ease;
        }
        
        .pnl-value {
            font-weight: bold !important;
            display: inline !important;
        }
        
        .pnl-positive {
            color: #22c55e !important; /* Green for positive */
        }
        
        .pnl-negative {
            color: #ef4444 !important; /* Red for negative */
        }
        
        .pnl-neutral {
            color: #e0e0e0 !important; /* Default color */
        }
        
        /* Ensure dollar sign and formatting persist */
        .pnl-display .pnl-currency {
            font-weight: bold !important;
            display: inline !important;
        }
        
        /* Prevent any JavaScript from easily overriding PnL content */
        .pnl-display strong {
            font-weight: bold !important;
            display: inline !important;
        }
        
        /* Force the PnL structure to be protected */
        .active_pnl .pnl-display {
            min-height: 1.2em !important;
            position: relative !important;
        }
        
        .active_pnl .pnl-display strong {
            position: relative !important;
            z-index: 10 !important;
        }
        
        /* Refresh Button Animation */
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        @keyframes fa-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        /* Refresh Button Styles */
        .refresh-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .refresh-btn .fa-spin {
            animation: fa-spin 1s infinite linear;
        }
        
        /* Modal and Backdrop Fixes - Force to Front */
        #newWithdrawalModal {
            z-index: -1 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: block !important;
        }
        
        #newWithdrawalModal.show {
            z-index: -1 !important;
            display: block !important;
        }
        
        #newWithdrawalModal .modal-dialog {
            z-index: -1 !important;
            position: relative !important;
            margin: 1.75rem auto !important;
            max-width: 500px !important;
        }
        
        #newWithdrawalModal .modal-content {
            z-index: -1 !important;
            position: relative !important;
            background: white !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .modal-backdrop {
            z-index: -1 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
        
        .modal-backdrop.show {
            opacity: 0.5 !important;
            z-index: -1 !important;
        }
        
        /* Force modal to be visible */
        .modal.show {
            display: block !important;
            z-index: -1 !important;
        }
        
        /* Override any conflicting styles */
        body.modal-open {
            overflow: hidden !important;
        }
        
        /* Withdrawal Methods Grid Styles */
        .withdrawal-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .withdrawal-method-card {
            background: rgba(25, 30, 36, 0.8);
            border: 1px solid rgba(79, 140, 255, 0.2);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .withdrawal-method-card:hover {
            border-color: rgba(79, 140, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 140, 255, 0.1);
        }
        
        .method-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .method-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f8cff, #64b5f6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.8rem;
            color: white;
        }
        
        .method-info h3 {
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .method-info p {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }
        
        .method-features {
            display: flex;
            gap: 0.5rem;
        }
        
        .feature-tag {
            background: rgba(79, 140, 255, 0.2);
            color: #4f8cff;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .processing-time {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
        
        .form-group-modern {
            margin-bottom: 1rem;
        }
        
        .form-label-modern {
            display: flex;
            align-items: center;
            color: #e0e0e0;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-label-modern i {
            margin-right: 0.5rem;
            color: #4f8cff;
        }
        
        .form-control-modern {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: #ffffff;
            padding: 0.75rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .form-control-modern:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.25);
            color: #ffffff;
        }
        
        .form-control-modern::placeholder {
            color: #888;
        }
        
        /* Specific styling for select elements */
        select.form-control-modern {
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        
        select.form-control-modern option {
            background: #1a1a1a;
            color: #ffffff;
            border: none;
        }
        
        select.form-control-modern:focus {
            background: #2d2d2d;
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.25);
            color: #ffffff;
        }
        
        /* Bank Details Styling */
        .bank-details-card {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .details-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .detail-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
        }
        
        .detail-label {
            display: block;
            color: #4f8cff;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .detail-value-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .detail-value {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.75rem;
            color: #ffffff;
            font-size: 0.9rem;
            font-family: 'Courier New', monospace;
            word-wrap: break-word;
            word-break: break-all;
            white-space: pre-wrap;
            line-height: 1.4;
            min-height: 2.5rem;
            max-width: 100%;
            overflow-wrap: break-word;
        }
        
        .copy-btn-modern {
            background: linear-gradient(135deg, #4f8cff, #64b5f6);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            align-self: center;
            margin: 0 auto;
        }
        
        .copy-btn-modern:hover {
            background: linear-gradient(135deg, #3d7cff, #52a3f3);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 140, 255, 0.3);
        }
        
        .copy-btn-modern:active {
            transform: translateY(0);
        }
        
        .copy-btn-modern i {
            font-size: 0.75rem;
        }
        
        /* Deposit submit buttons styling */
        .btn-deposit-submit {
            background: linear-gradient(135deg, #4f8cff, #64b5f6);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-deposit-submit:hover {
            background: linear-gradient(135deg, #3d7cff, #52a3f3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 140, 255, 0.4);
        }
        
        .btn-deposit-submit:active {
            transform: translateY(0);
        }
        
        .btn-deposit-submit:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .input-help {
            font-size: 0.8rem;
            color: #888;
            margin-top: 0.25rem;
        }
        
        .btn-submit-modern {
            background: linear-gradient(135deg, #4f8cff, #64b5f6);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-submit-modern:hover {
            background: linear-gradient(135deg, #3a7bd5, #4f8cff);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(79, 140, 255, 0.3);
        }
        
        .btn-submit-modern:active {
            transform: translateY(0);
        }
        .sidebar>*{
            margin-block: 0 !important;
        }





        
    /* Enhanced Form Controls for Modals */
    .modal .form-control, 
    .modal .form-select {
        border-radius: 12px;
        border: 1px solid var(--border-light);
        background: var(--bg-secondary);
        font-size: 15px;
        font-weight: 500;
        padding: 16px 20px;
        transition: all 0.3s ease;
        color: var(--text-primary);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .modal .form-control:focus, 
    .modal .form-select:focus {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
        background: var(--bg-card);
        transform: translateY(-1px);
    }

    .modal .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 12px;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal .form-label::before {
        content: '';
        width: 4px;
        height: 4px;
        background: var(--text-primary);
        border-radius: 50%;
        opacity: 0.6;
    }

    /* Enhanced Button Styling for Modals */
    .modal .btn {
        border-radius: 12px !important;
        font-size: 15px;
        font-weight: 600;
        padding: 16px 24px;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        min-width: 120px;
    }

    .modal .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .modal .btn:hover::before {
        left: 100%;
    }

    .modal .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .modal .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .modal .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .modal .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .modal .btn-primary {
        background: linear-gradient(135deg, var(--text-secondary) 0%, var(--text-primary) 100%);
        color: var(--bg-card);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .modal .btn-primary:hover {
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--accent-dark) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        color: var(--bg-card);
    }

    /* Modern Toggle Switches */
    .modal .form-check {
        margin-bottom: 16px;
    }

    .modal .form-check-input {
        width: 56px;
        height: 28px;
        border-radius: 14px;
        background: var(--border-medium);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .modal .form-check-input:checked {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .modal .form-check-input:focus {
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }

    .modal .form-check-input::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 24px;
        height: 24px;
        background: white;
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .modal .form-check-input:checked::before {
        transform: translateX(28px);
    }

    .modal .form-check-label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 15px;
        margin-left: 12px;
        cursor: pointer;
    }

    /* Modern Modal Design */
    .modal {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.5);
    }

    .modal-dialog {
        margin: 1rem auto;
        max-width: 95vw;
        width: 100%;
        display: flex;
        align-items: center;
        min-height: calc(100vh - 2rem);
    }

    .modal-content {
        border-radius: 16px;
        border: none;
        background: var(--bg-card);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        animation: modalSlideUp 0.3s ease-out;
    }

       /* Modal Action Button Groups */
        .modal .btn-group-modern {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .modal .btn-group-modern .btn {
            flex: 1;
            min-width: 140px;
        }

        /* Modal Form Grid */
        .modal-form-grid {
            display: grid;
            gap: 20px;
        }

        .modal-form-grid .row {
            margin: 0;
        }

        .modal-form-grid .col-6,
        .modal-form-grid .col-12 {
            padding: 0;
        }
        .modal .form-control:focus, 
        .modal .form-select:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.08);
            background: var(--bg-card);
            transform: translateY(-1px);
        }

        .modal .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal .form-label::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--text-primary);
            border-radius: 50%;
            opacity: 0.6;
        }
        
        .modal .form-control,
        .modal .form-select {
            padding: 14px 16px;
            font-size: 16px; /* Prevents zoom on iOS */
        }
    
    </style>

</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <!-- Language Switcher  -->
    <div class="language-switcher-container">
        <button class="language-switcher-btn">
            <i class="bi bi-globe"></i>
        </button>
        
        <div class="language-dropdown" id="languageDropdown">
            @foreach(['en' => 'English', 'ar' => 'العربية'] as $language => $name)
                <a href="{{ switchUrlLocaleTo($language) }}" class="language-option {{ app()->getLocale() == $language ? 'active' : '' }}">
                    <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="18" height="13" alt="{{ $name }}" class="option-flag">
                    <span class="option-name">{{ $name }}</span>
                    @if(app()->getLocale() == $language)
                        <i class="bi bi-check-lg active-check"></i>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <i class="bi bi-bell nav-icon notification-icon" title="{{ __('web.notifications') }}" style="font-size:1.2rem; padding:0.3rem; position: relative; cursor: pointer; z-index: 1000;">
        @if(!empty($notifications) && count($notifications) > 0)
            <span class="notification-badge">{{ count($notifications) }}</span>
        @endif
    </i>
    <i class="bi bi-chat-dots nav-icon chat-icon" title="{{ __('web.live_chat') }}" style="font-size:1.2rem; padding:0.3rem;"></i>

    <div style="width: 20px; border-bottom: 2px solid #4f8cff; margin: 0 10px 20px 10px; opacity: 0.7;"></div>

    <i class="bi bi-bar-chart nav-icon markets-icon active" title="{{ __('web.markets') }}" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-person nav-icon account-icon" title="{{ __('web.account') }}" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-arrow-up-circle nav-icon deposit-icon" title="{{ __('web.deposit') }}" style="font-size:1.2rem; padding:0.3rem;"></i>
    @if(isset(auth()->guard('client')->user()->options['enableWithdrawalRequest']) && auth()->guard('client')->user()->options['enableWithdrawalRequest'] == 1)
        <i class="bi bi-arrow-down-circle nav-icon withdrawal-icon" title="{{ __('web.withdrawal') }}" style="font-size:1.2rem; padding:0.3rem;"></i>
    @endif
    <i class="bi bi-box-arrow-right nav-icon logout-icon" title="{{ __('web.logout') }}" style="font-size:1.2rem; padding:0.3rem;"></i>
</div>

<!-- Custom Context Menu -->
<div id="customContextMenu" class="shadow-lg p-2">
    <button id="goToAssetBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-arrow-right-circle fs-5"></i>
        <span>{{ __('web.go_to_asset') }}</span>
    </button>
    <button id="addToFavouriteBtn" data-asset-id="{{ $asset && $asset->id ? $asset->id : '' }}" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star fs-5 text-primary"></i>
        <span>{{ __('web.add_to_favourites') }}</span>
    </button>
    <button id="removeFromFavouriteBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star-fill fs-5 text-warning"></i>
        <span>{{ __('web.remove_from_favourites') }}</span>
    </button>
</div>

<!-- Main Trading Interface -->
<div id="mainContent" class="main-content">
    <div class="row">
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
    <div class="row g-3 align-items-start">

        <!-- Chart & Tabs -->
        <div class="col-lg-8">
            <div class="panel">
                <!-- TradingView Widget -->
                <div class="tv-widget-container">
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                    {
                        "autosize": true,
                        "symbol": "{{ $symbol ?? 'XAUUSD' }}",
                        "interval": "5",
                        "timezone": "Etc/UTC",
                        "theme": "dark",
                        "style": "1",
                        "locale": "en",
                        "allow_symbol_change": false,
                        "support_host": "https://www.tradingview.com"
                    }
                    </script>
                </div>
            </div>
        </div>

        <!-- Right Side Panel -->
        <div class="col-lg-4">
            <div class="right-side-panel">
                <!-- Asset Search & Filters -->
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <div class="flex-grow-1">
                        <input type="text" id="assetSearch" class="searchbar form-control-sm w-100" placeholder="{{ __('web.search_symbols') }}" style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem;">
                    </div>
                    <div>
                        <select id="categoryFilter" class="filtercategory form-select-sm" style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem; min-width: 100px;">
                            <option value="">{{ __('web.all') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" id="showFavouritesBtn" class="btn btn-sm" title="{{ __('web.show_favourites') }}" style="background:#23272f; color:#4f8cff; border:1px solid #353b48; padding: 0.5rem 0.75rem; border-radius: 6px;">
                            <i class="bi bi-star-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="assets d-grid gap-2" id="assetGrid">
                    <div class="row fw-bold text-secondary mb-2" style="font-size: 1rem;">
                        <div class="col-6">{{ __('web.market') }}</div>
                        <div class="col-3 text-center">{{ __('web.bid') }}</div>
                        <div class="col-3 text-center">{{ __('web.ask') }}</div>
                    </div>
                    
                    {{-- Current Asset First --}}
                    @if($asset)
                        <button type="button" class="row align-items-center asset-button asset-item market-assets mb-2 current-asset"
                                data-asset-id="{{ $asset->id }}"
                                data-id="{{ $asset->id }}"
                                data-symbol="{{ $asset->symbol }}"
                                data-name="{{ $asset->name }}"
                                data-category="{{ $asset->category }}"
                                data-url="{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}"
                                onclick="window.location.href='{{ route('client.webtrader', ['symbol' => $asset->symbol]) }}'"
                                oncontextmenu="showContextMenu(event, {{ $asset->id }})">
                            <div class="col-6 text-start">
                                <span class="name text-white fw-bold">
                                    {{ $asset->name }}
                                    @if (in_array($asset->id, $favourite_assets_ids))
                                        <span class="star-icon" style="color: gold; margin-left: 6px;">★</span>
                                    @endif
                                </span>
                            </div>

                            <div class="col-3 text-center">
                                <span class="bid_price text-danger" data-asset-id="{{$asset->id}}" class="bid_price" data-asset-id="{{$asset->id}}">
                                    {{$asset->bid_price}}
                                </span>
                                </span>
                            </div>

                            <div class="col-3 text-center">
                                <span class="ask_price text-success" data-asset-id="{{$asset->id}}">
                                    {{$asset->ask_price}} 
                                </span>
                                </span>
                            </div>
                        </button>
                    @endif
                    
                    {{-- Other Assets --}}
                    @foreach($assetsPrices as $assetPrice)
                        @if(!$asset || $assetPrice->id !== $asset->id)
                            <button type="button" class="row align-items-center asset-button asset-item market-assets mb-2"
                                    data-asset-id="{{ $assetPrice->id }}"
                                    data-id="{{ $assetPrice->id }}"
                                    data-symbol="{{ $assetPrice->symbol }}"
                                    data-name="{{ $assetPrice->name }}"
                                    data-category="{{ $assetPrice->category }}"
                                    data-url="{{ route('client.webtrader', ['symbol' => $assetPrice->symbol]) }}"
                                    onclick="window.location.href='{{ route('client.webtrader', ['symbol' => $assetPrice->symbol]) }}'"
                                    oncontextmenu="showContextMenu(event, {{ $assetPrice->id }})">
                                <div class="col-6 text-start">
                                    <span class="name text-white fw-bold">
                                        {{ $assetPrice->name }}
                                        @if (in_array($assetPrice->id, $favourite_assets_ids))
                                            <span class="star-icon" style="color: gold; margin-left: 6px;">★</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="col-3 text-center">
                                    <span class="bid_price text-danger" data-asset-id="{{ $assetPrice->id }}">
                                        {{ number_format($assetPrice->bid_price, 4) }}
                                    </span>
                                </div>

                                <div class="col-3 text-center">
                                    <span class="ask_price text-success" data-asset-id="{{ $assetPrice->id }}">
                                        {{ number_format($assetPrice->ask_price, 4) }}
                                    </span>
                                </div>
                            </button>
                        @endif
                    @endforeach
                </div>
                
                <!-- Order Form -->
                <div class="mt-3">
                    <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="orderType" name="type" value="1">
                    <input type="hidden" id="selectedAssetId" name="currency" value="{{ $asset && $asset->id ? $asset->id : '' }}">
                    <input type="hidden" id="selectedAssetSymbol" name="asset_symbol" value="{{ $symbol ?? '' }}">
                    <input type="hidden" id="currentBidPrice" name="bid" value="{{ $asset && $asset->bid_price ? $asset->bid_price : '0' }}">
                    <input type="hidden" id="currentAskPrice" name="ask" value="{{ $asset && $asset->ask_price ? $asset->ask_price : '0' }}">
                    <input type="hidden" id="currentChartSymbol" value="{{ $symbol ?? '' }}">
                    

                    <!-- Trading Controls -->
                    <div class="trading-controls mb-3">
                        <!-- Quick Amount Buttons -->
                        <div class="quick-amounts-row mb-2">
                            <button type="button" class="quick-amount-btn" onclick="setAmount(0.01)">Min</button>
                            <button type="button" class="quick-amount-btn" onclick="setAmount(0.10)">0.10</button>
                            <button type="button" class="quick-amount-btn" onclick="setAmount(1.00)">1.00</button>
                            <button type="button" class="quick-amount-btn" onclick="setAmount(10.00)">10.00</button>
                        </div>

                        <!-- Trade Buttons with Amount in Center -->
                        <div class="trade-buttons-with-amount">
                            <button type="button" id="sellBtn" class="trade-btn sell-btn" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                <div class="trade-btn-content">
                                    <span class="trade-action">{{ __('web.sell') }}</span>
                                    <span class="trade-price" id="displayBidPrice">{{ $asset && $asset->bid_price ? number_format($asset->bid_price, 4) : '0.0000' }}</span>
                                </div>
                            </button>

                            <div class="amount-control-center">
                                <label for="amount" class="amount-label">{{ __('web.amount') }}</label>
                                <div class="amount-input-group">
                                    <button type="button" class="amount-btn minus-btn" onclick="changeAmount(-0.01)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" id="amount" name="amount" min="0.01" step="0.01" value="0.01" class="amount-input" readonly/>
                                    <button type="button" class="amount-btn plus-btn" onclick="changeAmount(0.01)">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="buyBtn" class="trade-btn buy-btn" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                                <div class="trade-btn-content">
                                    <span class="trade-action">{{ __('web.buy') }}</span>
                                    <span class="trade-price" id="displayAskPrice">{{ $asset && $asset->ask_price ? number_format($asset->ask_price, 4) : '0.0000' }}</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tabs and Account Summary Row -->
        <div class="col-12">
            <div class="details-panel">
                <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center">
                    <ul class="nav nav-tabs border-0 mb-0" id="tradeTabs" role="tablist">
                        <li class="nav-item"><a class="nav-link {{ (!isset($tab) || $tab == 'openedOrder') ? 'active' : '' }}" data-bs-toggle="tab" href="#openOrders" role="tab">{{ __('web.orders') }}</a></li>
                        <li class="nav-item"><a class="nav-link {{ (isset($tab) && $tab == 'summary') ? 'active' : '' }}" data-bs-toggle="tab" href="#summary" role="tab">{{ __('web.pending') }}</a></li>
                        <li class="nav-item"><a class="nav-link {{ (isset($tab) && $tab == 'history') ? 'active' : '' }}" data-bs-toggle="tab" href="#history" role="tab">{{ __('web.history') }}</a></li>
                    </ul>
                    <div class="account-summary-inline d-flex flex-wrap">
                        <div><span class="text-secondary">{{ __('web.balance') }}:</span> <span class="text-light">${{ number_format($finance['balance']??0, 2) }}</span></div>
                        <div><span class="text-secondary">{{ __('web.margin') }}:</span> <span class="text-light">${{ number_format($finance['freeMargin']??0, 2) }}</span></div>
                        <div><span class="text-secondary">{{ __('web.equity') }}:</span> <span class="text-light">${{ number_format($finance['equity']??0, 2) }}</span></div>
                        <div><span class="text-secondary">{{ __('web.credit') }}:</span> <span class="text-light">${{ number_format($finance['credit']??0, 2) }}</span></div>
                        <div><span class="text-secondary">{{ __('web.bonus') }}:</span> <span class="text-light">${{ number_format($finance['bonus']??0, 2) }}</span></div>
                    </div>
                </div>
                <div class="tab-content">
                    <!-- Open Orders Tab -->
                    <div class="tab-pane fade {{ (!isset($tab) || $tab == 'openedOrder') ? 'show active' : '' }}" id="openOrders" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-dark table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>{{ __('web.instrument') }}</th>
                                    <th>{{ __('web.type') }}</th>
                                    <th>{{ __('web.size') }}</th>
                                    <th>{{ __('web.entry_price') }}</th>
                                    <th>{{ __('web.current_price') }}</th>
                                    <th>{{ __('web.stop_loss') }}</th>
                                    <th>{{ __('web.take_profit') }}</th>
                                    <th>{{ __('web.created_at') }}</th>
                                    <th>{{ __('web.profit_loss') }}</th>
                                    <th>{{ __('web.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($openOrders as $order)
                                        <tr>
                                            <td>{{ $order->asset->name }}</td>
                                            <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                            <td>{{ number_format($order->amount, 2) }}</td>
                                            <td>{{ number_format($order->open_price, 5) }}</td>
                                            <td>{{ number_format($order->type == 1 ? $order->asset->bid_price : $order->asset->ask_price, 5) }}</td>
                                            <td>{{ $order->s_l ?? '-' }}</td>
                                            <td>{{ $order->s_p ?? '-' }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                            <td class="pnl @if($order->closed_at == null && $order->status == 'active' && $order->pnl) active_pnl @endif" data-order-id="{{$order->id}}">
                                                <div class="pnl-display {{$order->pnl < 0 ? 'text-danger' : 'text-success'}}">
                                                    <strong>$ <span class="pnl-value">{{ number_format($order->pnl, 2) }}</span></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{ route('order.close', ['id'=>$order->id]) }}" class="d-inline" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('web.confirm_close_order') }}')">
                                                        {{ __('web.close') }}
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                    {{ __('web.edit') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            {{ __('web.no_orders_found') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- History Tab -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab == 'history') ? 'show active' : '' }}" id="history" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-dark table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>{{ __('web.instrument') }}</th>
                                    <th>{{ __('web.type') }}</th>
                                    <th>{{ __('web.size') }}</th>
                                    <th>{{ __('web.open_price') }}</th>
                                    <th>{{ __('web.close_price') }}</th>
                                    <th>{{ __('web.stop_loss') }}</th>
                                    <th>{{ __('web.take_profit') }}</th>
                                    <th>{{ __('web.opened') }}</th>
                                    <th>{{ __('web.closed') }}</th>
                                    <th>{{ __('web.profit_loss') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($closedOrders as $order)
                                        <tr>
                                            <td>{{ $order->asset->name }}</td>
                                            <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                            <td>{{ number_format($order->amount, 2) }}</td>
                                            <td>{{ number_format($order->open_price, 5) }}</td>
                                            <td>{{ number_format($order->close_price, 5) }}</td>
                                            <td>{{ $order->s_l ?? '-' }}</td>
                                            <td>{{ $order->s_p ?? '-' }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($order->closed_at)) }}</td>
                                            <td class="pnl-display {{ $order->pnl < 0 ? 'text-danger' : 'text-success' }}">
                                                <strong>$ <span class="pnl-value">{{ number_format($order->pnl, 2) }}</span></strong>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            {{ __('web.no_orders_found') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($closedOrders->hasPages())
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $closedOrders->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Pending Orders Tab -->
                    <div class="tab-pane fade {{ (isset($tab) && $tab == 'summary') ? 'show active' : '' }}" id="summary" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-dark table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Instrument</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Order Price</th>
                                    <th>Current Price</th>
                                    <th>Stop Loss</th>
                                    <th>Take Profit</th>
                                    <th>Created at</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendingOrders as $order)
                                        <tr>
                                            <td>{{ $order->asset->name }}</td>
                                            <td>{{ $order->type == 1 ? __('web.buy') : __('web.sell') }}</td>
                                            <td>{{ number_format($order->amount, 2) }}</td>
                                            <td>{{ number_format($order->open_price, 5) }}</td>
                                            <td>{{ number_format($order->type == 1 ? $order->asset->ask_price : $order->asset->bid_price, 5) }}</td>
                                            <td>{{ $order->s_l ?? '-' }}</td>
                                            <td>{{ $order->s_p ?? '-' }}</td>
                                            <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('order.delete', ['id'=>$order->id]) }}" class="d-inline" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('web.confirm_cancel_pending_order') }}')">
                                                        {{ __('web.cancel') }}
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                    {{ __('web.edit') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            {{ __('web.no_pending_orders') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Interface -->
<div id="accountInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="header-text">
                    <h1>{{ __('web.account_dashboard') }}</h1>
                    <p>{{ __('web.overview_trading_account') }}</p>
                </div>
            </div>                <div class="header-actions">
                <button class="btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('web.back_to_trading') }}</span>
                </button>
            </div>
        </div>

        <!-- Account Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card balance-card">
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['balance']??0, 2) }}</h3>
                    <p>{{ __('web.account_balance') }}</p>
                </div>
                <div class="stat-trend positive">
                    <i class="bi bi-arrow-up"></i>
                </div>
            </div>

            <div class="stat-card equity-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['equity'] ?? 0, 2) }}</h3>
                    <p>{{ __('web.total_equity') }}</p>
                </div>
                <div class="stat-trend positive">
                    <i class="bi bi-arrow-up"></i>
                </div>
            </div>

            <div class="stat-card margin-card">
                <div class="stat-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-content">
                    <h3>${{ number_format($finance['freeMargin'] ?? 0, 2) }}</h3>
                    <p>{{ __('web.free_margin') }}</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="bi bi-dash"></i>
                </div>
            </div>

            <div class="stat-card pnl-card">
                <div class="stat-icon">
                    <i class="bi bi-lightning"></i>
                </div>
                <div class="stat-content">
                    <h3 class="{{ (isset($finance['currentPL']) && $finance['currentPL'] >= 0) ? 'text-success' : 'text-danger' }}">${{ number_format($finance['currentPL'] ?? 0, 2) }}</h3>
                    <p>{{ __('web.current_pnl') }}</p>
                </div>
                <div class="stat-trend {{ (isset($finance['currentPL']) && $finance['currentPL'] >= 0) ? 'positive' : 'negative' }}">
                    <i class="bi bi-{{ (isset($finance['currentPL']) && $finance['currentPL'] >= 0) ? 'arrow-up' : 'arrow-down' }}"></i>
                </div>
            </div>
        </div>

        <!-- Account Info Cards -->
        <div class="info-cards-grid">
            <div class="info-card personal-info">
                <div class="card-header">
                    <h3><i class="bi bi-person-lines-fill"></i> {{ __('web.personal_information') }}</h3>
                    <button class="btn btn-sm btn-outline-primary edit-profile-btn" id="editProfileBtn">
                        <i class="bi bi-pencil"></i> {{ __('web.edit') }}
                    </button>
                </div>
                <div class="card-content">
                    <form id="profileForm" action="{{ route('client.update.profile') }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <div class="info-row">
                            <span class="label">{{ __('web.name') }}</span>
                            <input type="text" name="name" class="form-control-modern" value="{{ auth()->guard('client')->user()->name }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.email') }}</span>
                            <input type="email" name="email" class="form-control-modern" value="{{ auth()->guard('client')->user()->email }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.phone') }}</span>
                            <input type="text" name="phone" class="form-control-modern" value="{{ auth()->guard('client')->user()->phone ?? '' }}" placeholder="Enter your phone number">
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.country') }}</span>
                            <input type="text" name="country" class="form-control-modern" value="{{ auth()->guard('client')->user()->country ?? '' }}" placeholder="Enter your country">
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.account_type') }}</span>
                            <span class="value badge-premium">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                            <small class="text-muted">{{ __('web.account_type_cannot_change') }}</small>
                        </div>
                        <div class="form-actions mt-3">
                            <button type="submit" class="btn btn-gradient-primary me-2">
                                <i class="bi bi-check-lg"></i> {{ __('web.save_changes') }}
                            </button>
                            <button type="button" class="btn btn-secondary cancel-edit-btn">
                                <i class="bi bi-x-lg"></i> {{ __('web.cancel') }}
                            </button>
                        </div>
                    </form>
                    
                    <div id="profileDisplay" class="profile-display">
                        <div class="info-row">
                            <span class="label">{{ __('web.name') }}</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.email') }}</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.phone') }}</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->phone ?? __('web.not_provided') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.country') }}</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->country ?? __('web.not_provided') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">{{ __('web.account_type') }}</span>
                            <span class="value badge-premium enhanced">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card trading-stats">
                <div class="card-header">
                    <h3><i class="bi bi-graph-up-arrow"></i> {{ __('web.trading_statistics') }}</h3>
                </div>
                <div class="card-content">
                    <div class="stats-grid-3x2">
                        <div class="stat-item">
                            <div class="stat-circle total-orders">
                                <span>{{ number_format($finance['totalOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>{{ __('web.total_orders') }}</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle active-orders">
                                <span>{{ number_format($finance['activeOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>{{ __('web.active_orders') }}</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle closed-orders">
                                <span>{{ number_format($finance['closedOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>{{ __('web.closed_orders') }}</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle win-orders">
                                <span>{{ number_format($finance['winOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>{{ __('web.win_orders') }}</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle lose-orders">
                                <span>{{ number_format($finance['loseOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>{{ __('web.lose_orders') }}</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle total-pnl {{ ($finance['totalPnL'] ?? 0) >= 0 ? 'profit' : 'loss' }}">
                                <span>${{ number_format($finance['totalPnL'] ?? 0, 2) }}</span>
                            </div>
                            <p>{{ __('web.total_pnl') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="action-btn deposit-action" onclick="showDepositInterface()">
                <i class="bi bi-arrow-up-circle"></i>
                <span>{{ __('web.make_deposit') }}</span>
            </button>
            @if(isset(auth()->guard('client')->user()->options['enableWithdrawalRequest']) && auth()->guard('client')->user()->options['enableWithdrawalRequest'] == 1)
                <button class="action-btn withdrawal-action" onclick="showWithdrawalInterface()">
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>{{ __('web.withdraw_funds') }}</span>
                </button>
            @endif
            <button class="action-btn trading-action" onclick="showMainContent()">
                <i class="bi bi-graph-up"></i>
                <span>{{ __('web.start_trading') }}</span>
            </button>
            <button class="action-btn upload-document-action" onclick="showUploadDocumentInterface()">
                <i class="bi bi-file-earmark-arrow-up"></i>
                <span>{{ __('web.upload_document') }}</span>
            </button>
        </div>
    </div>
</div>

<!-- Deposit Interface -->
<div id="depositInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-arrow-up-circle"></i>
                </div>
                <div class="header-text">
                    <h1>{{ __('web.deposit_funds') }}</h1>
                    <p>{{ __('web.fund_account_quickly') }}</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('web.back_to_trading') }}</span>
                </button>
            </div>
        </div>

        <!-- Current Balance Display -->
        <div class="balance-display-card mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="balance-icon me-3">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <h6 class="text-white mb-1">{{ __('web.current_balance') }}</h6>
                        <h3 class="text-white mb-0">${{ number_format($finance['balance']??0, 2) }}</h3>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-white">{{ __('web.available_withdrawal') }}</small>
                    <div class="text-success font-weight-bold">${{ number_format($finance['balance']??0, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Deposit Methods Grid -->
        <div class="deposit-methods-grid">
            <!-- Bank Transfer Method -->
            <div class="deposit-method-card bank-transfer-card">
                <div class="method-header">
                    <div class="method-icon bank-icon">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="method-info">
                        <h4>{{ __('web.bank_transfer') }}</h4>
                        <p>{{ __('web.secure_bank_transfer') }}</p>
                        <div class="method-features">
                            <span class="feature-badge">{{ __('web.no_fees') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="bankDepositForm" action="{{ route('deposit.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="bank_transfer">
                        
                        <div class="form-group-modern mb-3">
                            <label for="bank_deposit_amount" class="form-label-modern">
                                <i class="bi bi-currency-dollar"></i>
                                {{ __('web.amount_usd') }}
                            </label>
                            <input type="number" name="amount" id="bank_deposit_amount" class="form-control-modern" step="0.01" min="10" placeholder="10.00" required>
                        </div>
                        
                        <div class="form-group-modern mb-3">
                            <label for="country_select" class="form-label-modern">
                                <i class="bi bi-geo-alt"></i>
                                {{ __('web.select_country') }}
                            </label>
                            <select name="country" id="country_select" class="form-control-modern" required>
                                <option value="">{{ __('web.choose_country') }}</option>
                                @php
                                    $countries = [];
                                    foreach($banks ?? [] as $bank) {
                                        if (!empty($bank->country) && !in_array($bank->country, $countries)) {
                                            $countries[] = $bank->country;
                                        }
                                    }
                                    sort($countries);
                                @endphp
                                @foreach($countries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group-modern mb-3">
                            <label for="bank_select" class="form-label-modern">
                                <i class="bi bi-bank"></i>
                                {{ __('web.select_bank') }}
                            </label>
                            <select name="bank_id" id="bank_select" class="form-control-modern" required disabled>
                                <option value="">{{ __('web.first_select_country') }}</option>
                            </select>
                        </div>
                        
                        <!-- Bank Details Display -->
                        <div id="bankDetailsDisplay" class="bank-details-card" style="display: none;">
                            <h5 class="text-white mb-3"><i class="bi bi-bank me-2"></i>{{ __('web.bank_transfer_details') }}</h5>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Bank Name:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayBankName">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayBankName').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Beneficiary Name:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayAccountName">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayAccountName').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayAccountNumber">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayAccountNumber').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="ibanRow" style="display: none;">
                                    <span class="detail-label">IBAN:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayIban">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayIban').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="swiftCodeRow" style="display: none;">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displaySwiftCode">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displaySwiftCode').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="abaRoutingRow" style="display: none;">
                                    <span class="detail-label">ABA Routing Number:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayAbaRouting">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayAbaRouting').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="beneficiaryAddressRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Address:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayBeneficiaryAddress">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayBeneficiaryAddress').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="beneficiaryCountryRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Country:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayBeneficiaryCountry">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayBeneficiaryCountry').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="bankAddressRow" style="display: none;">
                                    <span class="detail-label">Bank Address:</span>
                                    <div class="detail-value-container">
                                        <div class="detail-value" id="displayBankAddress">-</div>
                                        <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('displayBankAddress').textContent)">
                                            <i class="bi bi-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Please use these details to make your bank transfer and upload the receipt below.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bank_receipt" class="form-label">{{ __('web.upload_receipt') }}</label>
                            <div class="file-upload-area">
                                <input type="file" name="receipt" id="bank_receipt" class="file-input" accept=".pdf,.png,.jpg,.jpeg" required>
                                <div class="file-upload-content">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <small>PDF, PNG, JPG, JPEG (Max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-deposit-submit bank-submit">
                            <i class="bi bi-bank me-2"></i>
                            <span>Submit Bank Transfer</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cryptocurrency Method -->
            <div class="deposit-method-card crypto-card">
                <div class="method-header">
                    <div class="method-icon crypto-icon">
                        <i class="bi bi-currency-bitcoin"></i>
                    </div>
                    <div class="method-info">
                        <h4>{{ __('web.cryptocurrency') }}</h4>
                        <p>{{ __('web.fast_secure_crypto') }}</p>
                        <div class="method-features">
                            <span class="feature-badge crypto">{{ __('web.low_fees') }}</span>
                            <span class="feature-badge crypto">{{ __('web.instant') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="cryptoDepositForm" action="{{ route('deposit.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="cryptocurrency">
                        
                        <div class="form-group-modern mb-3">
                            <label for="crypto_deposit_amount" class="form-label-modern">
                                <i class="bi bi-currency-dollar"></i>
                                {{ __('web.amount_usd') }}
                            </label>
                            <input type="number" name="amount" id="crypto_deposit_amount" class="form-control-modern" step="0.01" min="10" placeholder="10.00" required>
                        </div>
                        
                        <div class="form-group-modern mb-3">
                            <label for="crypto_type_select" class="form-label-modern">
                                <i class="bi bi-coin"></i>
                                {{ __('web.cryptocurrency') }}
                            </label>
                            <select name="crypto_type" id="crypto_type_select" class="form-control-modern" required>
                                <option value="">Select cryptocurrency...</option>
                                <option value="USDT">Tether (USDT)</option>
                            </select>
                        </div>
                        
                        <!-- USDT Address Display -->
                        <div id="usdtAddressDisplay" class="crypto-address-card" style="display: none;">
                            <h5 class="text-white mb-3"><i class="bi bi-wallet2 me-2"></i>USDT Deposit Address</h5>
                            @php
                                // Get USDT address with fallback logic
                                $client = auth('client')->user();
                                $usdtAddress = null;
                                
                                if ($client) {
                                    // First try client's usdt column
                                    if (!empty($client->usdt)) {
                                        $clientUsdt = $client->usdt;
                                        
                                        // Handle JSON format like {"phoenix":"address1","BNC":"address2"}
                                        if (is_string($clientUsdt)) {
                                            $decodedUsdt = json_decode($clientUsdt, true);
                                            if (is_array($decodedUsdt)) {
                                                // Get the first non-null address
                                                $usdtAddress = null;
                                                foreach ($decodedUsdt as $key => $address) {
                                                    if (!empty($address) && $address !== null) {
                                                        $usdtAddress = $address;
                                                        break;
                                                    }
                                                }
                                            } else {
                                                $usdtAddress = $clientUsdt;
                                            }
                                        } elseif (is_array($clientUsdt)) {
                                            // Get the first non-null address
                                            $usdtAddress = null;
                                            foreach ($clientUsdt as $key => $address) {
                                                if (!empty($address) && $address !== null) {
                                                    $usdtAddress = $address;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    // If client usdt is null/empty, get USDT from the pipeline this client belongs to
                                    elseif (!empty($client->pipeline_id)) {
                                        // Get the pipeline by pipeline_id
                                        $pipeline = \App\Models\Pipeline::find($client->pipeline_id);
                                        
                                        if ($pipeline && !empty($pipeline->usdt)) {
                                            $pipelineUsdt = $pipeline->usdt;
                                            
                                            // Handle different data types for pipeline usdt
                                            if (is_string($pipelineUsdt)) {
                                                $decodedUsdt = json_decode($pipelineUsdt, true);
                                                if (is_array($decodedUsdt)) {
                                                    // Get the first non-null address
                                                    $usdtAddress = null;
                                                    foreach ($decodedUsdt as $key => $address) {
                                                        if (!empty($address) && $address !== null) {
                                                            $usdtAddress = $address;
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    $usdtAddress = trim($pipelineUsdt);
                                                }
                                            } elseif (is_array($pipelineUsdt) && !empty($pipelineUsdt)) {
                                                // Get the first non-null address
                                                $usdtAddress = null;
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
                            @endphp
                            
                            @if($usdtAddress)
                                <div class="address-section">
                                    <div class="usdt-deposit-container">
                                        <!-- QR Code Section -->
                                        <div class="qr-section-top mb-4">
                                            <div class="qr-code-frame">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($usdtAddress) }}&format=png&bgcolor=FFFFFF&color=000000" alt="USDT Address QR Code" class="qr-code-image-large">
                                                <div class="qr-label">
                                                    <i class="bi bi-qr-code me-2"></i>
                                                    <span>Scan with Any Crypto Wallet</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- USDT Address Display -->
                                        <div class="address-section-main">
                                            <div class="address-frame">
                                                <label for="usdtAddressValue" class="address-label">
                                                    <i class="bi bi-wallet2 me-2"></i>
                                                    USDT (TRC20) Deposit Address
                                                </label>
                                                
                                                <!-- Address Input Display (Above Copy Button) -->
                                                <div class="address-display-container mb-3">
                                                    <div class="address-value-modern" id="usdtAddress">{{ $usdtAddress }}</div>
                                                    <input type="hidden" id="usdtAddressValue" value="{{ $usdtAddress }}">
                                                </div>
                                                
                                                <!-- Copy Button (Below Address) -->
                                                <div class="copy-button-container">
                                                    <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('usdtAddress').textContent)">
                                                        <i class="bi bi-copy"></i>
                                                        <span>Copy Address</span>
                                                    </button>
                                                </div>
                                                
                                                <div class="address-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    <span>Send only USDT (TRC20) to this address. Scan QR code or copy address to your crypto wallet.</span>
                                                </div>
                                                <div class="address-info mt-3">
                                                    <small class="text-light text-center d-block">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Compatible with all major crypto wallets and exchanges. Scan QR or copy and paste address.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="address-section">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>USDT Address Not Available</strong>
                                        <p class="mb-0 mt-2">Please contact our support team to set up your USDT deposit address.</p>
                                        <small class="d-block mt-2">
                                            <i class="bi bi-envelope me-1"></i>
                                            Contact support for assistance with cryptocurrency deposits.
                                        </small>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="crypto_receipt" class="form-label">Upload Receipt</label>
                            <div class="file-upload-area">
                                <input type="file" name="receipt" id="crypto_receipt" class="file-input" accept=".pdf,.png,.jpg,.jpeg" required>
                                <div class="file-upload-content">
                                    <i class="bi bi-cloud-upload"></i>
                                    <p>Click to upload or drag and drop</p>
                                    <small>PDF, PNG, JPG, JPEG (Max 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-deposit-submit crypto-submit">
                            <i class="bi bi-currency-bitcoin me-2"></i>
                            <span>Submit Crypto Deposit</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Credit Card Method -->
            <div class="deposit-method-card credit-card-card">
                <div class="method-header">
                    <div class="method-icon credit-card-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div class="method-info">
                        <h4>{{ __('web.credit_card') }}</h4>
                        <p>{{ __('web.fast_secure_card') }}</p>
                        <div class="method-features">
                            <span class="feature-badge credit-card">{{ __('web.instant') }}</span>
                            <span class="feature-badge credit-card">{{ __('web.secure') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="creditCardDepositForm" action="{{ route('deposit.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="credit_card">
                        
                        <div class="form-group-modern mb-3">
                            <label for="credit_card_deposit_amount" class="form-label-modern">
                                <i class="bi bi-currency-dollar"></i>
                                {{ __('web.amount_usd') }}
                            </label>
                            <input type="number" name="amount" id="credit_card_deposit_amount" class="form-control-modern" step="0.01" min="10" placeholder="10.00" required>
                        </div>

                        <!-- Credit Card Details -->
                        <div class="credit-card-details">
                            <h6 class="text-white mb-3"><i class="bi bi-credit-card me-2"></i>{{ __('web.card_information') }}</h6>
                            
                            <div class="form-group-modern mb-3">
                                <input type="text" name="card_number" id="card_number" class="form-control-modern"  placeholder="1234567890123456" minlength="16" maxlength="16" pattern="\d{16}"  title="Please enter exactly 16 digits" required>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group-modern mb-3">
                                        <label for="card_expiry" class="form-label-modern">
                                            <i class="bi bi-calendar"></i>
                                            {{ __('web.expiry_date') }}
                                        </label>
                                        <input type="text" name="card_expiry" id="card_expiry" class="form-control-modern" placeholder="MM/YY" maxlength="5" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group-modern mb-3">
                                        <label for="card_cvv" class="form-label-modern">
                                            <i class="bi bi-shield-lock"></i>
                                            {{ __('web.cvv') }}
                                        </label>
                                        <input type="text" name="card_cvv" id="card_cvv" class="form-control-modern" placeholder="123" maxlength="4" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group-modern mb-3">
                                <label for="card_holder_name" class="form-label-modern">
                                    <i class="bi bi-person"></i>
                                    {{ __('web.cardholder_name') }}
                                </label>
                                <input type="text" name="card_holder_name" id="card_holder_name" class="form-control-modern" placeholder="{{ __('web.name') }}" required>
                            </div>

                            <div class="form-group-modern mb-3">
                                <label for="billing_address" class="form-label-modern">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ __('web.billing_address') }}
                                </label>
                                <textarea name="billing_address" id="billing_address" class="form-control-modern" rows="3" placeholder="123 Main St, City, State, ZIP" required></textarea>
                            </div>

                        </div>

                        <button type="submit" class="btn-deposit-submit credit-card-submit">
                            <i class="bi bi-credit-card me-2"></i>
                            <span>Process Payment</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Deposits Section -->
        <div class="recent-transactions-section">
            <div class="section-header">
                <h3><i class="bi bi-clock-history me-2"></i>{{ __('web.recent_deposits') }}</h3>
                <button class="btn btn-outline-primary btn-sm refresh-btn">
                    <i class="bi bi-arrow-clockwise me-1"></i>{{ __('web.refresh') }}
                </button>
            </div>
            
            <!-- Deposit Tabs -->
            <div class="transaction-tabs">
                <nav class="nav nav-tabs nav-tabs-dark" id="depositTabs" role="tablist">
                    <button class="nav-link active" id="all-deposits-tab" data-bs-toggle="tab" data-bs-target="#all-deposits" type="button" role="tab" aria-controls="all-deposits" aria-selected="true">
                        <i class="bi bi-list-ul me-1"></i>{{ __('web.all') }}
                    </button>
                    <button class="nav-link" id="pending-deposits-tab" data-bs-toggle="tab" data-bs-target="#pending-deposits" type="button" role="tab" aria-controls="pending-deposits" aria-selected="false">
                        <i class="bi bi-clock me-1"></i>{{ __('web.pending') }}
                    </button>
                    <button class="nav-link" id="accepted-deposits-tab" data-bs-toggle="tab" data-bs-target="#accepted-deposits" type="button" role="tab" aria-controls="accepted-deposits" aria-selected="false">
                        <i class="bi bi-check-circle me-1"></i>{{ __('web.accepted') }}
                    </button>
                    <button class="nav-link" id="rejected-deposits-tab" data-bs-toggle="tab" data-bs-target="#rejected-deposits" type="button" role="tab" aria-controls="rejected-deposits" aria-selected="false">
                        <i class="bi bi-x-circle me-1"></i>{{ __('web.rejected') }}
                    </button>
                </nav>
                
                <div class="tab-content" id="depositTabContent">
                    <!-- All Deposits Tab -->
                    <div class="tab-pane fade show active" id="all-deposits" role="tabpanel" aria-labelledby="all-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>{{ __('web.transaction_id') }}</th>
                                        <th>{{ __('web.amount') }}</th>
                                        <th>{{ __('web.method') }}</th>
                                        <th>{{ __('web.status') }}</th>
                                        <th>{{ __('web.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="all-deposits-tbody">
                                    @php
                                        $currentClient = auth()->guard('client')->user();
                                        $clientId = $currentClient ? $currentClient->id : null;
                                        $brokerIdToUse = ($currentClient && $currentClient->broker_id) ? $currentClient->broker_id : $clientId;
                                        
                                        $allDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $allDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    
                                    @forelse($allDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    {{ __('web.cryptocurrency') }}
                                                @elseif($deposit->credit_card_details)
                                                    {{ __('web.credit_card') }}
                                                @else
                                                    {{ __('web.bank_transfer') }}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $status = strtolower($deposit->status ?? '');
                                                    $displayStatus = ucfirst($deposit->status ?? 'Unknown');
                                                @endphp
                                                @if($status === 'pending')
                                                    <span class="badge bg-warning">{{ $displayStatus }}</span>
                                                @elseif($status === 'accepted')
                                                    <span class="badge bg-success">{{ $displayStatus }}</span>
                                                @elseif($status === 'rejected')
                                                    <span class="badge bg-danger">{{ $displayStatus }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $displayStatus }}</span>
                                                @endif
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-inbox display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No deposits found</p>
                                                    <small class="text-light opacity-75">Your deposit history will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pending Deposits Tab -->
                    <div class="tab-pane fade" id="pending-deposits" role="tabpanel" aria-labelledby="pending-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="pending-deposits-tbody">
                                    @php
                                        $pendingDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $pendingDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'pending')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    
                                    @forelse($pendingDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    {{ __('web.cryptocurrency') }}
                                                @elseif($deposit->credit_card_details)
                                                    {{ __('web.credit_card') }}
                                                @else
                                                    {{ __('web.bank_transfer') }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ ucfirst($deposit->status ?? __('web.pending')) }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                    <p class="text-light">{{ __('web.no_pending_deposits') }}</p>
                                                    <small class="text-light opacity-75">{{ __('web.pending_deposits_here') }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Accepted Deposits Tab -->
                    <div class="tab-pane fade" id="accepted-deposits" role="tabpanel" aria-labelledby="accepted-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="accepted-deposits-tbody">
                                    @php
                                        $acceptedDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $acceptedDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'accepted')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    @forelse($acceptedDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ ucfirst($deposit->status ?? 'Accepted') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-check-circle display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No accepted deposits found</p>
                                                    <small class="text-light opacity-75">Accepted deposits will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Rejected Deposits Tab -->
                    <div class="tab-pane fade" id="rejected-deposits" role="tabpanel" aria-labelledby="rejected-deposits-tab">
                        <div class="transactions-table-container">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="rejected-deposits-tbody">
                                    @php
                                        $rejectedDeposits = collect();
                                        if ($brokerIdToUse) {
                                            $rejectedDeposits = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                ->where('type', '=', 'deposit')
                                                ->where('status', '=', 'rejected')
                                                ->orderBy('created_at', 'desc')
                                                ->take(20)
                                                ->get();
                                        }
                                    @endphp
                                    @forelse($rejectedDeposits as $deposit)
                                        <tr>
                                            <td class="text-light">#{{ $deposit->id }}</td>
                                            <td class="text-light">${{ number_format($deposit->amount, 2) }}</td>
                                            <td class="text-light">
                                                @if($deposit->usdt)
                                                    USDT
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">{{ ucfirst($deposit->status ?? 'Rejected') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-x-circle display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No rejected deposits found</p>
                                                    <small class="text-light opacity-75">Rejected deposits will appear here</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Interface -->
<div id="withdrawalInterface" class="main-content" style="display: none;">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-12">
                <div class="panel h-100">
                    <!-- Header Section -->
                    <div class="interface-header">
                        <div class="header-left">
                            <div class="interface-icon">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <div class="header-text">
                                <h1>{{ __('web.withdrawal') }}</h1>
                                <p>{{ __('web.withdraw_funds_from_account') }}</p>
                            </div>
                        </div>
                        <div class="header-actions">
                            <button class="btn btn-modern btn-secondary back-to-trading-btn">
                                <i class="bi bi-arrow-left"></i>
                                <span>{{ __('web.back_to_trading') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Current Balance Display -->
                    <div class="balance-display-card mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="balance-info">
                                    <h3 class="d-flex align-items-center mb-2">
                                        <i class="bi bi-wallet2 me-2"></i>
                                        {{ __('web.available_balance') }}
                                    </h3>
                                    <p class="d-flex align-items-center mb-0">
                                        <i class="bi bi-arrow-down-circle me-2"></i>
                                        {{ __('web.amount_available_withdrawal') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="balance-amount">${{ number_format($finance['balance']??0, 2) }}</div>
                                <div class="balance-equity">{{ __('web.equity') }}: ${{ number_format($finance['equity'] ?? 0, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Methods Grid -->
                    <div class="withdrawal-methods-grid">
                        <!-- Bank Transfer Method -->
                        <div class="withdrawal-method-card bank-transfer-card">
                            <div class="method-header">
                                <div class="method-icon">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div class="method-info">
                                    <h3>{{ __('web.bank_transfer') }}</h3>
                                    <p>{{ __('web.withdraw_to_bank_account') }}</p>
                                    <div class="method-features">
                                        <span class="feature-tag">{{ __('web.secure') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="method-form">
                                <form id="bankWithdrawalForm" action="{{ route('client.withdrawal.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="withdrawal_method" value="bank_transfer">
                                    
                                    {{-- <div class="form-group-modern mb-3">
                                        <label for="bank_withdrawal_amount" class="form-label-modern">
                                            <i class="bi bi-currency-dollar"></i>
                                            {{ __('web.withdrawal_amount') }}
                                        </label>
                                        <input type="number" name="amount" id="bank_withdrawal_amount" class="form-control-modern" placeholder="0.00" min="10" step="0.01" required>
                                    </div> --}}

                                    <div class="form-group-modern mb-3">
                                        <label for="bank_withdrawal_amount" class="form-label-modern">
                                            <i class="bi bi-currency-dollar"></i>
                                            {{ __('web.withdrawal_amount') }}
                                        </label>     
                                        <input type="number" class="form-control-modern" id="bank_withdrawal_amount" name="amount" min="1" max="{{ $finance['balance'] ??  0 }}" step="0.01" required placeholder="0.00">
                                        <small class="form-text text-white">{{ __('web.max') }}: ${{ number_format($finance['balance'] ?? 0, 2) }}</small>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="bank_name" class="form-label-modern">
                                            <i class="bi bi-building"></i>
                                            {{ __('web.bank_name') }}
                                        </label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control-modern" placeholder="{{ __('web.enter_bank_name') }}" required>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="account_number" class="form-label-modern">
                                            <i class="bi bi-credit-card-2-front"></i>
                                            {{ __('web.account_number') }}
                                        </label>
                                        <input type="text" name="account_number" id="account_number" class="form-control-modern" placeholder="{{ __('web.enter_account_number') }}" required>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="account_holder_name" class="form-label-modern">
                                            <i class="bi bi-person"></i>
                                            {{ __('web.account_holder_name') }}
                                        </label>
                                        <input type="text" name="account_holder_name" id="account_holder_name" class="form-control-modern" placeholder="{{ __('web.enter_account_holder_name') }}" required>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="swift_code" class="form-label-modern">
                                            <i class="bi bi-globe"></i>
                                            {{ __('web.swift') }}
                                        </label>
                                        <input type="text" name="swift_code" id="swift_code" class="form-control-modern" placeholder="{{ __('web.enter_swift_code') }}" required>
                                    </div>

                                    <button type="submit" class="btn-submit-modern">
                                        <i class="bi bi-check-circle"></i>
                                        {{ __('web.submit_withdrawal') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Cryptocurrency Method -->
                        <div class="withdrawal-method-card crypto-card">
                            <div class="method-header">
                                <div class="method-icon">
                                    <i class="bi bi-currency-bitcoin"></i>
                                </div>
                                <div class="method-info">
                                    <h3>{{ __('web.cryptocurrency') }}</h3>
                                    <p>{{ __('web.withdraw_to_crypto_wallet') }}</p>
                                    <div class="method-features">
                                        <span class="feature-tag">{{ __('web.fast') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="method-form">
                                <form id="cryptoWithdrawalForm" action="{{ route('client.withdrawal.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="withdrawal_method" value="cryptocurrency">
                                    
                                    {{-- <div class="form-group-modern mb-3">
                                        <label for="crypto_withdrawal_amount" class="form-label-modern">
                                            <i class="bi bi-currency-dollar"></i>
                                            {{ __('web.withdrawal_amount') }}
                                        </label>
                                        <input type="number" name="amount" id="crypto_withdrawal_amount" class="form-control-modern" placeholder="0.00" min="10" step="0.01" required>
                                    </div> --}}
                                    
                                    <div class="form-group-modern mb-3">
                                        <label for="crypto_withdrawal_amount" class="form-label-modern">
                                            <i class="bi bi-currency-dollar"></i>
                                            {{ __('web.withdrawal_amount') }}
                                        </label>     
                                        <input type="number" class="form-control-modern" id="crypto_withdrawal_amount" name="amount" min="1" max="{{ $balance ?? (isset($finance['balance']) ? $finance['balance'] : 0) }}" step="0.01" required placeholder="0.00">
                                        <small class="form-text text-white">{{__('web.max')}}: ${{ number_format($balance ?? (isset($finance['balance']) ? $finance['balance'] : 0), 2) }}</small>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="crypto_type" class="form-label-modern">
                                            <i class="bi bi-coin"></i>
                                            {{ __('web.cryptocurrency_type') }}
                                        </label>
                                        <select name="crypto_type" id="crypto_type" class="form-control-modern" required>
                                            <option value="USDT">Tether USDT (TRC20)</option>
                                        </select>
                                    </div>

                                    <div class="form-group-modern mb-3">
                                        <label for="wallet_address" class="form-label-modern">
                                            <i class="bi bi-wallet"></i>
                                            {{ __('web.wallet_address') }}
                                        </label>
                                        <input type="text" name="wallet_address" id="wallet_address" class="form-control-modern" placeholder="{{ __('web.enter_wallet_address') }}" required>
                                        <div class="input-help">{{ __('web.double_check_wallet_address') }}</div>
                                    </div>

                                    <input type="hidden" name="network_type" value="TRC20">

                                    <button type="submit" class="btn-submit-modern">
                                        <i class="bi bi-check-circle"></i>
                                        {{ __('web.submit_withdrawal') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon balance-icon me-3">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('web.available_balance') }}</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['balance']??0, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon pending-icon me-3">
                                            <i class="bi bi-clock-history"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('web.pending_withdrawals') }}</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['pendingWithdrawal']??0, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon completed-icon me-3">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">{{ __('web.total_withdrawn') }}</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['totalWithdrawal']??0, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Withdrawals Section -->
                    <div class="recent-transactions-section">
                        <div class="section-header">
                            <h3><i class="bi bi-clock-history me-2"></i>{{ __('web.recent_withdrawals') }}</h3>
                            <button class="btn btn-outline-primary btn-sm refresh-btn">
                                <i class="bi bi-arrow-clockwise me-1"></i>{{ __('web.refresh') }}
                            </button>
                        </div>
                        
                        <!-- Withdrawal Tabs -->
                        <div class="transaction-tabs">
                            <nav class="nav nav-tabs nav-tabs-dark" id="withdrawalTabs" role="tablist">
                                <button class="nav-link active" id="all-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#all-withdrawals" type="button" role="tab" aria-controls="all-withdrawals" aria-selected="true">
                                    <i class="bi bi-list-ul me-1"></i>{{ __('web.all') }}
                                </button>
                                <button class="nav-link" id="pending-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#pending-withdrawals" type="button" role="tab" aria-controls="pending-withdrawals" aria-selected="false">
                                    <i class="bi bi-clock me-1"></i>{{ __('web.pending') }}
                                </button>
                                <button class="nav-link" id="accepted-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#accepted-withdrawals" type="button" role="tab" aria-controls="accepted-withdrawals" aria-selected="false">
                                    <i class="bi bi-check-circle me-1"></i>{{ __('web.accepted') }}
                                </button>
                                <button class="nav-link" id="rejected-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#rejected-withdrawals" type="button" role="tab" aria-controls="rejected-withdrawals" aria-selected="false">
                                    <i class="bi bi-x-circle me-1"></i>{{ __('web.rejected') }}
                                </button>
                            </nav>
                            
                            <div class="tab-content" id="withdrawalTabContent">
                                <!-- All Withdrawals Tab -->
                                <div class="tab-pane fade show active" id="all-withdrawals" role="tabpanel" aria-labelledby="all-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="all-withdrawals-tbody">
                                                @php
                                                    $currentClient = auth()->guard('client')->user();
                                                    $clientId = $currentClient ? $currentClient->id : null;
                                                    $brokerIdToUse = ($currentClient && $currentClient->broker_id) ? $currentClient->broker_id : $clientId;
                                                    
                                                    $allWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $allWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                
                                                @forelse($allWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = strtolower($withdrawal->status ?? '');
                                                                $displayStatus = ucfirst($withdrawal->status ?? 'Unknown');
                                                            @endphp
                                                            @if($status === 'pending')
                                                                <span class="badge bg-warning">{{ $displayStatus }}</span>
                                                            @elseif($status === 'accepted')
                                                                <span class="badge bg-success">{{ $displayStatus }}</span>
                                                            @elseif($status === 'rejected')
                                                                <span class="badge bg-danger">{{ $displayStatus }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $displayStatus }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-inbox display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No withdrawals found</p>
                                                                <small class="text-light opacity-75">Your withdrawal history will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Pending Withdrawals Tab -->
                                <div class="tab-pane fade" id="pending-withdrawals" role="tabpanel" aria-labelledby="pending-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pending-withdrawals-tbody">
                                                @php
                                                    $pendingWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $pendingWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'pending')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                
                                                @forelse($pendingWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                {{ __('web.cryptocurrency') }}
                                                            @else
                                                                {{ __('web.bank_transfer') }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning">{{ ucfirst($withdrawal->status ?? __('web.pending')) }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                                <p class="text-light">{{ __('web.no_pending_withdrawals') }}</p>
                                                                <small class="text-light opacity-75">{{ __('web.pending_withdrawals_here') }}</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Accepted Withdrawals Tab -->
                                <div class="tab-pane fade" id="accepted-withdrawals" role="tabpanel" aria-labelledby="accepted-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="accepted-withdrawals-tbody">
                                                @php
                                                    $acceptedWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $acceptedWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'accepted')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                @forelse($acceptedWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">{{ ucfirst($withdrawal->status ?? 'Accepted') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-check-circle display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No accepted withdrawals found</p>
                                                                <small class="text-light opacity-75">Accepted withdrawals will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Rejected Withdrawals Tab -->
                                <div class="tab-pane fade" id="rejected-withdrawals" role="tabpanel" aria-labelledby="rejected-withdrawals-tab">
                                    <div class="transactions-table-container">
                                        <table class="table table-modern">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rejected-withdrawals-tbody">
                                                @php
                                                    $rejectedWithdrawals = collect();
                                                    if ($brokerIdToUse) {
                                                        $rejectedWithdrawals = \App\Models\MoneyTrx::where('broker_id', $brokerIdToUse)
                                                            ->where('type', '=', 'withdraw')
                                                            ->where('status', '=', 'rejected')
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(20)
                                                            ->get();
                                                    }
                                                @endphp
                                                @forelse($rejectedWithdrawals as $withdrawal)
                                                    <tr>
                                                        <td class="text-light">#{{ $withdrawal->id }}</td>
                                                        <td class="text-light">${{ number_format($withdrawal->amount, 2) }}</td>
                                                        <td class="text-light">
                                                            @if($withdrawal->usdt)
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-danger">{{ ucfirst($withdrawal->status ?? 'Rejected') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-x-circle display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No rejected withdrawals found</p>
                                                                <small class="text-light opacity-75">Rejected withdrawals will appear here</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
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
    </div>
</div>

<!-- New Withdrawal Modal - REMOVED - Using direct interface instead -->
<div class="modal fade" id="newWithdrawalModal" tabindex="-1" aria-labelledby="newWithdrawalModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h4 class="modal-title text-white" id="newWithdrawalModalLabel">
                    <i class="bi bi-arrow-down-circle me-2" style="color: #ffcc02;"></i>
                    {{ __('web.request_new_withdrawal') }}
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('web.close') }}"></button>
            </div>
            <div class="modal-body">
                <!-- Withdrawal Method Tabs -->
                <ul class="nav nav-pills mb-4" id="withdrawalMethodTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="font-size: 1.05rem;" id="bank-tab" data-bs-toggle="pill" data-bs-target="#bank-transfer" type="button" role="tab">
                            <i class="bi bi-bank me-2"></i>{{ __('web.bank_transfer') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" style="font-size: 1.05rem;" id="crypto-tab" data-bs-toggle="pill" data-bs-target="#cryptocurrency" type="button" role="tab">
                            <i class="bi bi-currency-bitcoin me-2"></i>{{ __('web.cryptocurrency') }}
                        </button>
                    </li>
                </ul>

                <!-- Available Balance Alert -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong style="font-size: 1.1rem;">{{ __('web.available_balance') }}:</strong> <span style="font-size: 1.1rem;">${{ number_format($finance['balance']??0, 2) }}</span>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="withdrawalMethodContent">
                    <!-- Bank Transfer Tab -->
                    <div class="tab-pane fade show active" id="bank-transfer" role="tabpanel">
                        <form id="bankWithdrawalForm" action="{{ route('client.withdrawal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="bank_transfer">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_amount" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.amount_usd') }}</label>
                                        <input type="number" id="bank_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" min="1" max="{{ $finance['balance']??0 }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">{{ __('web.maximum_amount') }}: ${{ number_format($finance['balance']??0, 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_holder" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.account_holder') }}</label>
                                        <input type="text" name="account_holder" id="account_holder" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.bank_name') }}</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_number" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.account_number') }}</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="swift_code" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.swift_routing_code') }}</label>
                                <input type="text" name="swift_code" id="swift_code" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">{{ __('web.cancel') }}</button>
                                <button type="submit" class="btn btn-gradient-danger" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>{{ __('web.submit_withdrawal_request') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Cryptocurrency Tab -->
                    <div class="tab-pane fade" id="cryptocurrency" role="tabpanel">
                        <form id="cryptoWithdrawalForm" action="{{ route('client.withdrawal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="cryptocurrency">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="crypto_amount" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.amount_usd') }}</label>
                                        <input type="number" id="crypto_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" min="1" max="{{ $finance['balance']??0 }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">{{ __('web.maximum_amount') }}: ${{ number_format($finance['balance']??0, 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="crypto_type_withdrawal" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.cryptocurrency') }}</label>
                                        <select name="crypto_type" id="crypto_type_withdrawal" class="form-select bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                            <option value="">{{ __('web.select_cryptocurrency') }}</option>
                                            <option value="BTC">Bitcoin (BTC)</option>
                                            <option value="ETH">Ethereum (ETH)</option>
                                            <option value="USDT">Tether (USDT)</option>
                                            <option value="LTC">Litecoin (LTC)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="wallet_address" class="form-label text-white" style="font-size: 1.05rem;">{{ __('web.wallet_address') }}</label>
                                <input type="text" name="wallet_address" id="wallet_address" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                <small class="text-white" style="font-size: 0.95rem;">{{ __('web.wallet_address_help') }}</small>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">{{ __('web.cancel') }}</button>
                                <button type="submit" class="btn btn-gradient-warning" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>{{ __('web.submit_withdrawal_request') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="editOrderModalLabel">
                    <i class="bi bi-pencil-square me-2" style="color: #4f8cff;"></i>
                    {{ __('web.edit_order') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('web.close') }}"></button>
            </div>
            <div class="modal-body">
                <form id="editOrderForm" action="" method="POST">
                    @csrf
                    <input type="hidden" id="editOrderId" name="order_id">
                    
                    <div class="mb-3">
                        <label for="edit_stop_loss" class="form-label text-white">{{ __('web.stop_loss') }}</label>
                        <input type="number" id="edit_stop_loss" name="s_l" class="form-control bg-dark text-white border-secondary" step="0.00001" placeholder="{{ __('web.enter_stop_loss_price') }}">
                        <small class="text-muted">{{ __('web.optional_stop_loss_help') }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_take_profit" class="form-label text-white">{{ __('web.take_profit') }}</label>
                        <input type="number" id="edit_take_profit" name="s_p" class="form-control bg-dark text-white border-secondary" step="0.00001" placeholder="{{ __('web.enter_take_profit_price') }}">
                        <small class="text-muted">{{ __('web.optional_take_profit_help') }}</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('web.cancel') }}</button>
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bi bi-check-circle me-2"></i>{{ __('web.update_order') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- New Order Modal -->
<div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newOrderModalLabel">
                    <i class="fas fa-chart-line me-2"></i>
                    {{__('web.new_order')}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="display: flex;justify-content: center;align-content: center;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-form-grid">
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">                            
                            <div class="col-12">
                                <label for="newAmount" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    {{__('web.amount')}}
                                </label>
                                <input type="number" class="form-control-modern" id="newAmount" name="amount" min="0.01" step="any" value="0.01" placeholder="0.01">
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input me-3" type="checkbox" id="newStopLossSwitch">
                                    <label class="form-check-label" for="stopLossSwitch">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        {{__('web.set_stop_loss')}}
                                    </label>
                                </div>
                                <div id="newStopLossContainer" class="mt-3" style="display: none;">
                                    <input type="number" class="form-control-modern" id="newStopLossInput" step="any" name="s_l" placeholder="{{__('web.stop_loss_price')}}">
                                    <label class="text-danger">
                                        {{__('web.estimated_loss')}}
                                        <strong id="newExpectedLoss">0</strong>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check form-switch d-flex align-items-center">
                                    <input class="form-check-input me-3" type="checkbox" id="newTakeProfitSwitch">
                                    <label class="form-check-label" for="takeProfitSwitch">
                                        <i class="fas fa-target me-2"></i>
                                        {{__('web.set_take_profit')}}
                                    </label>
                                </div>
                                <div id="newTakeProfitContainer" class="mt-3" style="display: none;">
                                    <input type="number" class="form-control-modern" id="newTakeProfitInput" step="any" name="s_p" placeholder="{{__('web.take_profit_price')}}">
                                    <label class="text-success">
                                        {{__('web.estimated_profit')}}
                                        <strong id="newExpectedProfit">0</strong>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group-modern">
                            <label>
                                {{__('web.required_margin')}}
                                <strong id="new_required_margin">-</strong>
                            </label>
                        </div>

                        <div class="btn-group-modern">
                            <button type="submit" class="btn btn-success">
                                {{__('web.submit')}}
                            </button>
                        </div>
                        
                        <input type="hidden" class="form-control" name="bid" id="bid" value="0" readonly>
                        <input type="hidden" class="form-control" name="ask" id="ask" value="0" readonly>
                        <input type="hidden" class="form-control" name="type" id="type-input" value="0" readonly>
                        <input type="hidden" id="newSelectedAssetId" name="currency" value="{{ $asset && $asset->id ? $asset->id : '' }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stopLossSwitch = document.getElementById('newStopLossSwitch');
        const stopLossContainer = document.getElementById('newStopLossContainer');
        const takeProfitSwitch = document.getElementById('newTakeProfitSwitch');
        const takeProfitContainer = document.getElementById('newTakeProfitContainer');
        const stopLossInput = document.getElementById('newStopLossInput');
        const takeProfitInput = document.getElementById('newTakeProfitInput');
        const newAmount = document.getElementById('newAmount');
        const requiredMargin = document.getElementById('new_required_margin');
        const clientId = {{auth()->guard('client')->user()->id}};
        const expectedLoss = document.getElementById('newExpectedLoss');
        const expectedProfit = document.getElementById('newExpectedProfit');
        const typeInput = document.getElementById('type-input');


        function calcExpectedLoss() {
            let openPrice;

            if(!document.getElementById('newAmount').value || !stopLossInput.value){
                expectedLoss.textContent = parseFloat(0).toFixed(4);
                return;
            }
            let assetId = {{$asset->id}};

            
            if(typeInput.value == 1){
                openPrice = document.getElementById('ask').value;
            }else{
                openPrice = document.getElementById('bid').value;
            }

            $.ajax({
                url: `{{config('services.crm_api.url')}}/api/calculatePnlWithoutOrder?clientId=${clientId}&asset=${assetId}&orderType=${typeInput.value}&openPrice=${openPrice}&currentPrice=${stopLossInput.value}&amount=${document.getElementById('newAmount').value}`,
                method: 'GET',
                dataType: 'json',
                timeout: 5000,
                headers: {
                    'X-API-KEY': "{{config('services.crm_api.key')}}",
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                },
                success: function(response) {
                    loss = Math.abs(response.pnl);
                    expectedLoss.textContent = parseFloat(loss).toFixed(4);
                },
                error: function(xhr, status, error) {
                }
            });

        }

        function calcExpectedProfit() {
            let openPrice;
            if(!document.getElementById('newAmount').value || !takeProfitInput.value){
                expectedProfit.textContent = parseFloat(0).toFixed(4);
                return;
            }
            if(typeInput.value == 1){
                openPrice = document.getElementById('ask').value;
            }else{
                openPrice = document.getElementById('bid').value;
            }

            $.ajax({
                url: `{{config('services.crm_api.url')}}/api/calculatePnlWithoutOrder?clientId=${clientId}&asset=${assetId}&orderType=${typeInput.value}&openPrice=${openPrice}&currentPrice=${takeProfitInput.value}&amount=${document.getElementById('newAmount').value}`,
                method: 'GET',
                dataType: 'json',
                timeout: 5000,
                headers: {
                    'X-API-KEY': "{{config('services.crm_api.key')}}",
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                },
                success: function(response) {
                    profit = Math.abs(response.pnl);
                    expectedProfit.textContent = parseFloat(profit).toFixed(4);
                },
                error: function(xhr, status, error) {
                }
            });
        }

        function getRequiredMarginFromApi() {
            const assetId = {{$asset->id}};
            const amount = newAmount.value;
            const open_price = typeInput.value == 1 ? document.getElementById('ask').value : document.getElementById('bid').value;
            if(!assetId || !amount || !open_price){
                return;
            }

            $.ajax({
                url: `{{config('services.crm_api.url')}}/api/getRequiredMargin/${assetId}?amount=${amount}&open_price=${open_price}`,
                method: 'GET',
                dataType: 'json',
                timeout: 5000,
                headers: {
                    'X-API-KEY': "{{config('services.crm_api.key')}}",
                    'Accept': 'application/json'
                },
                beforeSend: function() {
                    requiredMargin.textContent = '-';
                },
                success: function(response) {
                    requiredMargin.textContent = response.required_margin;
                },
                error: function(xhr, status, error) {
                }
            });
        } 

        // Handle stop loss and take profit toggles for new order modal
        if (stopLossSwitch) {
            stopLossSwitch.addEventListener('change', function() {
                stopLossContainer.style.display = this.checked ? 'block' : 'none';
            });
        }

        if (takeProfitSwitch) {
            takeProfitSwitch.addEventListener('change', function() {
                takeProfitContainer.style.display = this.checked ? 'block' : 'none';
            });
        }

        if (stopLossInput) {
            stopLossInput.addEventListener('change', function(e) {
                const ask = parseFloat(document.getElementById('ask').value);
                const bid = parseFloat(document.getElementById('bid').value);
                const val = parseFloat(this.value);

                if(typeInput.value == 1){
                    if(val >= ask){
                        this.value = ask
                        return;
                    }
                }else{
                    if(val <= bid){
                        this.value = bid
                        return;
                    }
                }

                calcExpectedLoss()
            });
        }

        if (takeProfitInput) {
            takeProfitInput.addEventListener('change', function() {
                const ask = parseFloat(document.getElementById('ask').value);
                const bid = parseFloat(document.getElementById('bid').value);
                const val = parseFloat(this.value);
                if(typeInput.value == 1){
                    if(val <= ask){
                        this.value = ask
                        return;
                    }
                }else{
                    if(val >= bid){
                        this.value = bid
                        return;
                    }
                }     
                calcExpectedProfit()
            });
        }

        if (newAmount) {
            newAmount.addEventListener('change', function() {
                calcExpectedLoss();
                calcExpectedProfit();
                getRequiredMarginFromApi();
                const amount = document.getElementById("amount");
                if (amount) {
                    amount.value = this.value
                }

            });
        }

        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === "attributes" && mutation.attributeName === "value") {
                    $('#bid').val($('#currentBidPrice').val());
                    $('#ask').val($('#currentAskPrice').val());
                    // calcExpectedLoss();
                    // calcExpectedProfit();
                }
            });
        });

        observer.observe(document.getElementById('currentBidPrice'), {
            attributes: true // Listen for attribute changes
        });


        calcExpectedLoss();
        calcExpectedProfit();
        getRequiredMarginFromApi();
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editOrderForm = document.getElementById('editOrderForm');
    if (editOrderForm) {
        editOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const orderId = document.getElementById('editOrderId').value;
            
            // Validate that we have an order ID
            if (!orderId) {
                alert('{{ __("web.order_id_required") }}');
                return;
            }
            
            // Submit the form normally since we have the action URL set
            this.submit();
        });
    }
});
</script>

<!-- Credit Card Form Validation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const creditCardForm = document.getElementById('creditCardDepositForm');
    const cardNumberInput = document.getElementById('card_number');
    
    if (creditCardForm && cardNumberInput) {
        // Format card number input (remove non-digits and limit to 16)
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) {
                value = value.slice(0, 16);
            }
            e.target.value = value;
        });
        
        // Validate on form submission
        creditCardForm.addEventListener('submit', function(e) {
            const cardNumber = cardNumberInput.value.replace(/\s/g, '');
            
            if (cardNumber.length !== 16) {
                e.preventDefault();
                alert('Credit card number must be exactly 16 digits. You entered ' + cardNumber.length + ' digits.');
                cardNumberInput.focus();
                return false;
            }
            
            if (!/^\d{16}$/.test(cardNumber)) {
                e.preventDefault();
                alert('Credit card number must contain only numbers (16 digits).');
                cardNumberInput.focus();
                return false;
            }
        });
    }
});
</script>

<!-- Hidden logout form -->
<form id="logoutForm" action="{{ route('client.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Chat Interface -->
<div id="chatInterface" class="main-content" style="display: none;">
    <div class="modern-interface-container">
        <!-- Header Section -->
        <div class="interface-header">
            <div class="header-left">
                <div class="interface-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <div class="header-text">
                    <h1>{{ __('web.live_chat_support') }}</h1>
                    <p>{{ __('web.get_instant_help') }}</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('web.back_to_trading') }}</span>
                </button>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <div class="chat-messages" id="chatMessages">
                @foreach ($chat ?? [] as $message)
                    @if ($message->user_id)
                        <!-- Support Message -->
                        <div class="message support-message">
                            <div class="message-avatar">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">{{ __('web.support_team') }}</span>
                                    <span class="message-time">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span>
                                </div>
                                <div class="message-text">{{ $message->message }}</div>
                            </div>
                        </div>
                    @else
                        <!-- User Message -->
                        <div class="message user-message">
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-sender">{{ __('web.you') }}</span>
                                    <span class="message-time">{{ date('d/m/Y H:i', strtotime($message->created_at)) }}</span>
                                </div>
                                <div class="message-text">{{ $message->message }}</div>
                            </div>
                            <div class="message-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </div>
                    @endif
                @endforeach
                
                @if(empty($chat) || count($chat) == 0)
                    <div class="welcome-message">
                        <div class="welcome-icon">
                            <i class="bi bi-chat-heart"></i>
                        </div>
                        <h3>{{ __('web.welcome_live_chat') }}</h3>
                        <p>{{ __('web.support_team_help') }}</p>
                        <p>{{ __('web.send_message_prompt') }}</p>
                    </div>
                @endif
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container">
                <form id="chatForm" action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    <div class="chat-input-wrapper">
                        <div class="input-with-icon">
                            <textarea name="message" id="chatMessage" class="chat-input"  placeholder="{{ __('web.type_message_here') }}"  rows="1" required></textarea>
                            <button type="submit" class="send-button">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Quick Actions -->
                <div class="chat-quick-actions">
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('{{ __('web.need_help_account') }}')">
                        <i class="bi bi-person-gear"></i>
                        <span>{{ __('web.account_help') }}</span>
                    </button>
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('{{ __('web.question_about_trading') }}')">
                        <i class="bi bi-graph-up"></i>
                        <span>{{ __('web.trading_question') }}</span>
                    </button>
                    <button type="button" class="quick-action-btn" onclick="insertQuickMessage('{{ __('web.help_deposits_withdrawals') }}')">
                        <i class="bi bi-credit-card"></i>
                        <span>{{ __('web.payment_help') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Interface -->
<div id="uploadDocumentInterface" class="main-content" style="display: none;">
    <div class="document-interface-wrapper">
        <!-- Modern Header with Glassmorphism -->
        <div class="document-header-modern">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="header-content-modern">
                            <div class="header-icon-modern">
                                <div class="icon-wrapper-modern">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                </div>
                            </div>
                            <div class="header-text-modern">
                                <h1 class="header-title-modern">{{ __('web.document_management_center') }}</h1>
                                <p class="header-subtitle-modern">{{ __('web.securely_upload_manage') }}</p>
                                <div class="header-stats-modern">
                                    <span class="stat-item-modern">
                                        <i class="bi bi-shield-check text-success"></i>
                                        <span>{{ __('web.bank_level_encryption') }}</span>
                                    </span>
                                    <span class="stat-item-modern">
                                        <i class="bi bi-lightning text-warning"></i>
                                        <span>{{ __('web.instant') }}</span>
                                    </span>
                                    <span class="stat-item-modern">
                                        <i class="bi bi-cloud-check text-info"></i>
                                        <span>{{ __('web.secure') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="header-actions-modern">
                            <button class="btn-modern-secondary back-to-trading-btn">
                                <i class="bi bi-arrow-left"></i>
                                <span>{{ __('web.back_to_trading') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
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
    </div>
</div>

<!-- Modern Notification Popup -->
<div id="notificationPopup" class="notification-popup-modern" style="display: none;">
    <div class="notification-popup-container">
        <div class="notification-popup-header-modern">
            <div class="notification-popup-title-modern">
                <div class="notification-icon-modern">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="notification-title-text">
                    <h4>{{ __('web.notifications') }}</h4>
                    <span class="notification-count-text">{{ count($notifications ?? []) }} {{ __('web.new_notification') }}</span>
                </div>
            </div>
            <button class="notification-popup-close-modern" onclick="closeNotificationPopup()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="notification-popup-body-modern">
            <div class="notification-popup-messages-modern" id="notificationPopupMessages">
                @if(empty($notifications) || count($notifications) == 0)
                    <div class="no-notifications-message-modern">
                        <div class="no-notifications-illustration">
                            <div class="notification-bell-empty">
                                <i class="bi bi-bell-slash"></i>
                            </div>
                            <div class="notification-waves">
                                <span class="wave wave-1"></span>
                                <span class="wave wave-2"></span>
                                <span class="wave wave-3"></span>
                            </div>
                        </div>
                        <div class="no-notifications-content">
                            <h5>{{ __('web.all_caught_up') }}</h5>
                            <p>{{ __('web.no_notifications') }}</p>
                            <small>{{ __('web.notify_important') }}</small>
                        </div>
                    </div>
                @else
                    @foreach($notifications as $notification)
                        <div class="notification-item-modern" data-id="{{ $notification->id }}">
                            <div class="notification-item-icon">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <div class="notification-item-content">
                                <div class="notification-item-text">{{ __('web.'.$notification->text) }}</div>
                                <div class="notification-item-meta">
                                    <span class="notification-time">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                    <span class="notification-date">{{ date('M d, Y H:i', strtotime($notification->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="notification-item-actions">
                                <button class="notification-action-btn mark-read" onclick="markNotificationAsRead({{ $notification->id }})" title="Mark as read">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="notification-action-btn delete" onclick="deleteNotification({{ $notification->id }})" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        
        @if(!empty($notifications) && count($notifications) > 0)
            <div class="notification-popup-footer-modern">
                <button class="notification-footer-btn mark-all-read" onclick="markAllNotificationsAsRead()">
                    <i class="bi bi-check-all me-2"></i>
                    {{ __('web.mark_all_read') }}
                </button>
                <button class="notification-footer-btn clear-all" onclick="clearAllNotifications()">
                    <i class="bi bi-trash me-2"></i>
                    {{ __('web.clear_all') }}
                </button>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/main_tp.js') }}"></script>
<script src="{{ url('assets/js/webtrader2.js?v2.0') }}"></script>

<!-- Add CSRF token and routes for JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Global variables for JavaScript
    var client_id = {{ auth()->guard('client')->user()->id }};
    var assetId = {{ $asset && $asset->id ? $asset->id : 'null' }};
    
    // Current symbol for JavaScript
    window.currentSymbol = '{{ $symbol ?? '' }}';
    
    // Routes for JavaScript
    window.updateOrderRoute = '{{ route("order.edit", ["id" => "__ORDER_ID__"]) }}';
    
    // Banks data for filtering
    window.banksData = @json($banks ?? []);
    
    // Notifications data
    window.notificationsData = @json($notifications ?? []);
    
    // Crypto wallets (example data - you should get this from your configuration)
    window.cryptoWallets = {
        'BTC': '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
        'ETH': '0x742d35Cc6634C0532925a3b8D82D5F62B5D5D',
        'USDT': 'TUDsqK9VqKmJ5KjWKVfK3aEKwKKKvKJKKv',
        'LTC': 'LTC1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4'
    };
    
    // Add route for toggling favourites
    document.body.setAttribute('data-toggle-favourite-route', '{{ route("toggle.favourite") }}');
</script>

<!-- Language Switcher JavaScript -->
<script>
    // Language Switcher Dropdown Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const languageSwitcherBtn = document.querySelector('.language-switcher-btn');
        const languageDropdown = document.querySelector('.language-dropdown');
        let dropdownOverlay = null;

        // Toggle dropdown
        function toggleDropdown() {
            const isOpen = languageDropdown.classList.contains('show');
            closeNotificationPopup();
            
            if (isOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        // Open dropdown
        function openDropdown() {
            languageDropdown.classList.add('show');
            languageSwitcherBtn.classList.add('active');
            
            // Create overlay to close dropdown when clicking outside
            dropdownOverlay = document.createElement('div');
            dropdownOverlay.className = 'language-dropdown-overlay';
            document.body.appendChild(dropdownOverlay);
            
            // Close dropdown when clicking overlay
            dropdownOverlay.addEventListener('click', closeDropdown);
            
            // Close dropdown on escape key
            document.addEventListener('keydown', handleEscapeKey);
        }

        // Close dropdown
        function closeDropdown() {
            languageDropdown.classList.remove('show');
            languageSwitcherBtn.classList.remove('active');
            
            // Remove overlay
            if (dropdownOverlay) {
                document.body.removeChild(dropdownOverlay);
                dropdownOverlay = null;
            }
            
            // Remove escape key listener
            document.removeEventListener('keydown', handleEscapeKey);
        }

        // Handle escape key
        function handleEscapeKey(event) {
            if (event.key === 'Escape') {
                closeDropdown();
            }
        }

        // Language switcher button click handler
        if (languageSwitcherBtn) {
            languageSwitcherBtn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                toggleDropdown();
            });
        }

        // Language option click handlers
        const languageOptions = document.querySelectorAll('.language-option');
        languageOptions.forEach(option => {
            option.addEventListener('click', function(event) {
                // Close dropdown after selection
                closeDropdown();
                
                // Add loading state (optional)
                languageSwitcherBtn.style.opacity = '0.7';
                
                // The actual language change will be handled by the backend
                // This is just for UX feedback
                setTimeout(() => {
                    languageSwitcherBtn.style.opacity = '1';
                }, 500);
            });
        });

        // Close dropdown if clicking outside language switcher container
        document.addEventListener('click', function(event) {
            const languageContainer = document.querySelector('.language-switcher-container');
            if (languageContainer && !languageContainer.contains(event.target)) {
                closeDropdown();
            }
        });

        // Keyboard navigation for accessibility
        if (languageSwitcherBtn) {
            languageSwitcherBtn.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggleDropdown();
                }
            });
        }

        // Arrow key navigation in dropdown
        if (languageDropdown) {
            languageDropdown.addEventListener('keydown', function(event) {
                const options = Array.from(languageOptions);
                const currentIndex = options.findIndex(option => option === document.activeElement);
                
                switch (event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        const nextIndex = currentIndex < options.length - 1 ? currentIndex + 1 : 0;
                        options[nextIndex].focus();
                        break;
                    case 'ArrowUp':
                        event.preventDefault();
                        const prevIndex = currentIndex > 0 ? currentIndex - 1 : options.length - 1;
                        options[prevIndex].focus();
                        break;
                    case 'Enter':
                        event.preventDefault();
                        if (document.activeElement && document.activeElement.classList.contains('language-option')) {
                            document.activeElement.click();
                        }
                        break;
                }
            });
        }
    });

    // Edit Order Modal Function
    function editOrder(orderId, stopLoss, takeProfit) {
        // Close any open dropdowns or overlays that might interfere (safely)
        try {
            if (typeof closeLanguageDropdown === 'function') {
                closeLanguageDropdown();
            }
        } catch (e) {
            // Silently handle missing function
        }
        
        try {
            if (typeof closeNotificationPopup === 'function') {
                closeNotificationPopup();
            }
        } catch (e) {
            // Silently handle missing function
        }
        
        // Set the order ID in the hidden input
        const orderIdInput = document.getElementById('editOrderId');
        orderIdInput.value = orderId;
        
        // Set the form action URL using the proper Laravel route
        const editForm = document.getElementById('editOrderForm');
        const actionUrl = window.updateOrderRoute.replace('__ORDER_ID__', orderId);
        editForm.action = actionUrl;
        
        // Populate the form fields with current values
        const stopLossInput = document.getElementById('edit_stop_loss');
        const takeProfitInput = document.getElementById('edit_take_profit');
        
        // Clear any previous values
        stopLossInput.value = '';
        takeProfitInput.value = '';
        
        // Set current values if they exist and are not null
        if (stopLoss && stopLoss !== 'null' && stopLoss !== '') {
            stopLossInput.value = parseFloat(stopLoss);
        }
        
        if (takeProfit && takeProfit !== 'null' && takeProfit !== '') {
            takeProfitInput.value = parseFloat(takeProfit);
        }
        
        // Enable the input fields and ensure they're interactive
        stopLossInput.disabled = false;
        takeProfitInput.disabled = false;
        stopLossInput.readOnly = false;
        takeProfitInput.readOnly = false;
        
        // Remove any interfering styles
        stopLossInput.style.pointerEvents = 'auto';
        takeProfitInput.style.pointerEvents = 'auto';
        stopLossInput.style.userSelect = 'auto';
        takeProfitInput.style.userSelect = 'auto';
        
        // Focus on the first input for better UX
        setTimeout(function() {
            stopLossInput.focus();
            stopLossInput.select();
        }, 500);
    }

    // Price tracking for color changes
    let previousPrices = {};
    let priceUpdateInterval;
    
    // Update prices with color indicators
    function updatePricesWithColors(priceData) {
        
        if (Array.isArray(priceData)) {
            priceData.forEach(function(asset) {
                updateSingleAssetPrice(asset);
            });
        } else if (priceData.assets && Array.isArray(priceData.assets)) {
            priceData.assets.forEach(function(asset) {
                updateSingleAssetPrice(asset);
            });
        } else {
            updateSingleAssetPrice(priceData);
        }
        
    }
    
    function updateSingleAssetPrice(asset) {
        if (!asset || !asset.id) {
            return;
        }
        
        const assetId = asset.id;
        const newBidPrice = parseFloat(asset.bid_price || 0);
        const newAskPrice = parseFloat(asset.ask_price || 0);
        
        
        // Get previous prices for comparison
        const prevPrices = previousPrices[assetId] || { bid: newBidPrice, ask: newAskPrice };
        
        // Update bid and ask prices in the asset list
        updatePriceElement('.bid_price[data-asset-id="' + assetId + '"]', newBidPrice, prevPrices.bid, 'bid');
        updatePriceElement('.ask_price[data-asset-id="' + assetId + '"]', newAskPrice, prevPrices.ask, 'ask');
        
        // Only update the trading buttons if this is the currently selected asset
        const selectedAssetId = $('#selectedAssetId').val();
        if (assetId == selectedAssetId) {
            // Update trading button prices for the current chart asset
            updatePriceElement('#displayBidPrice', newBidPrice, prevPrices.bid, 'bid');
            updatePriceElement('#displayAskPrice', newAskPrice, prevPrices.ask, 'ask');
            
            // Update hidden inputs for order submission
            $('#currentBidPrice').val(newBidPrice);
            $('#currentAskPrice').val(newAskPrice);
        }
        
        // Store current prices for next comparison
        previousPrices[assetId] = { bid: newBidPrice, ask: newAskPrice };
        
    }
    
    // Helper function to update trading button prices for a specific asset
    function updateTradingButtonPrices(assetId, bidPrice, askPrice) {
        const selectedAssetId = $('#selectedAssetId').val();
        if (assetId == selectedAssetId) {
            // Update trading button display prices
            $('#displayBidPrice').text(parseFloat(bidPrice).toFixed(4));
            $('#displayAskPrice').text(parseFloat(askPrice).toFixed(4));
            
            // Update hidden inputs for order submission
            $('#currentBidPrice').val(bidPrice);
            $('#currentAskPrice').val(askPrice);
            
            // Also update the selected asset info
            $('#selectedAssetId').val(assetId);
        }
    }
    
    function updatePriceElement(selector, newPrice, previousPrice, priceType) {
        const elements = $(selector);
        
        elements.each(function() {
            const element = $(this);
            const currentText = element.text().trim();
            const formattedPrice = parseFloat(newPrice).toFixed(4);
            
            
            // Update the price text
            element.text(formattedPrice);
            
            // Remove previous color classes
            element.removeClass('price-up price-down price-unchanged text-success text-danger');
            
            // Determine color based on price movement
            if (newPrice > previousPrice) {
                element.addClass('price-up');
            } else if (newPrice < previousPrice) {
                element.addClass('price-down');
            } else {
                element.addClass('price-unchanged');
            }
        });
    }
    
    function startPriceUpdates() {
        
        // Clear any existing interval
        if (priceUpdateInterval) {
            clearInterval(priceUpdateInterval);
        }
        
        // Update immediately
        updatePricesFromAPI();
        
        // Set up automatic updates every 2 seconds
        priceUpdateInterval = setInterval(function() {
            updatePricesFromAPI();
        }, 2000);
        
    }
    
    function updatePricesFromAPI() {
        $.ajax({
            url: '{{ route("api.price.data") }}',
            method: 'GET',
            dataType: 'json',
            timeout: 5000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            beforeSend: function() {
            },
            success: function(response) {
                updatePricesWithColors(response);
            },
            error: function(xhr, status, error) {
            }
        });
    }
    
    function stopPriceUpdates() {
        if (priceUpdateInterval) {
            clearInterval(priceUpdateInterval);
            priceUpdateInterval = null;
        }
    }
    
    // Start real-time price updates when page loads
    $(document).ready(function() {
        
        // Start price updates with color indicators
        startPriceUpdates();
        
        // Add modal event listeners to ensure inputs work
        $('#editOrderModal').on('shown.bs.modal', function() {
            // Ensure inputs are interactive when modal opens
            const stopLossInput = document.getElementById('edit_stop_loss');
            const takeProfitInput = document.getElementById('edit_take_profit');
            
            if (stopLossInput && takeProfitInput) {
                // Force enable interaction
                stopLossInput.style.pointerEvents = 'auto';
                takeProfitInput.style.pointerEvents = 'auto';
                stopLossInput.style.userSelect = 'auto';
                takeProfitInput.style.userSelect = 'auto';
                stopLossInput.disabled = false;
                takeProfitInput.disabled = false;
                stopLossInput.readOnly = false;
                takeProfitInput.readOnly = false;
                
                // Focus on first input
                setTimeout(() => {
                    stopLossInput.focus();
                }, 100);
            }
        });
        
        // Function to ensure $ symbol is always present in PnL displays
        function ensurePnLDollarSymbol() {
            $('.pnl-display strong').each(function() {
                const $strong = $(this);
                const text = $strong.text().trim();
                
                // If the text doesn't start with $, add it
                if (!text.startsWith('$')) {
                    $strong.html('$ <span class="pnl-value">' + text + '</span>');
                }
            });
        }
        
        // Run the function immediately
        ensurePnLDollarSymbol();
        
        // Run the function every 5 seconds to ensure $ stays
        setInterval(ensurePnLDollarSymbol, 5000);
        
        // Stop updates when user leaves the page
        $(window).on('beforeunload', function() {
            stopPriceUpdates();
        });
        
        // Pause updates when page is not visible (browser tab is inactive)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopPriceUpdates();
            } else {
                startPriceUpdates();
            }
        });
        
        // Refresh Button Functionality for Deposits and Withdrawals
        $(document).on('click', '.refresh-btn', function(e) {
            e.preventDefault();
            const $refreshBtn = $(this);
            const originalContent = $refreshBtn.html();
            
            // Show loading state
            $refreshBtn.prop('disabled', true);
            $refreshBtn.html('<i class="bi bi-arrow-clockwise me-1 fa-spin"></i>{{ __('web.refreshing') }}...');
            
            // Determine which interface we're in
            const isDepositInterface = $('#depositInterface').is(':visible');
            const isWithdrawalInterface = $('#withdrawalInterface').is(':visible');
            
            if (isDepositInterface) {
                // For deposits, try API refresh first, fallback to page reload
                refreshDepositData($refreshBtn, originalContent);
            } else if (isWithdrawalInterface) {
                // For withdrawals, just reload the page (no specific API endpoint)
                setTimeout(function() {
                    location.reload();
                }, 500);
            } else {
                // Default: refresh current page
                setTimeout(function() {
                    location.reload();
                }, 500);
            }
        });
        
        // Function to refresh deposit data
        function refreshDepositData($button, originalContent) {
            $.ajax({
                url: '{{ route("client.deposits.refresh") }}',
                method: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    if (response.success) {
                        // Show success message and reload
                        showSuccessMessage('{{ __('web.deposits_refreshed_successfully') }}');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        showErrorMessage(response.message || '{{ __('web.error_refreshing_deposits') }}');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error refreshing deposits:', error);
                    // Always fallback to page reload
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                complete: function() {
                    // Restore button state after a short delay
                    setTimeout(function() {
                        $button.prop('disabled', false);
                        $button.html(originalContent);
                    }, 500);
                }
            });
        }
        
        // Helper functions for success/error messages
        function showSuccessMessage(message) {
            // Create a temporary success alert
            const $alert = $('<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                '<i class="bi bi-check-circle me-2"></i>' + message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');
            
            $('body').append($alert);
            
            // Auto-dismiss after 3 seconds
            setTimeout(function() {
                $alert.alert('close');
            }, 3000);
        }
        
        function showErrorMessage(message) {
            // Create a temporary error alert
            const $alert = $('<div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
                '<i class="bi bi-exclamation-triangle me-2"></i>' + message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>');
            
            $('body').append($alert);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                $alert.alert('close');
            }, 5000);
        }
        
        // Withdrawal Modal Fix - Ensure proper Bootstrap modal functionality
        $(document).on('click', '.new-withdrawal-btn', function(e) {
            console.log('Withdrawal button clicked');
            
            // Clean up any existing modal state first
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('#newWithdrawalModal').removeClass('show');
            
            // Add emergency CSS override
            if (!$('#emergency-modal-css').length) {
                $('head').append(`
                    <style id="emergency-modal-css">
                        #newWithdrawalModal {
                            z-index: -1 !important;
                            position: fixed !important;
                            top: 0 !important;
                            left: 0 !important;
                            width: 100% !important;
                            height: 100% !important;
                            display: block !important;
                            background: rgba(0, 0, 0, 0.5) !important;
                        }
                        #newWithdrawalModal.show {
                            display: block !important;
                        }
                        #newWithdrawalModal .modal-dialog {
                            z-index: -1 !important;
                            position: relative !important;
                            margin: 50px auto !important;
                            max-width: 500px !important;
                        }
                        #newWithdrawalModal .modal-content {
                            z-index: -1 !important;
                            background: white !important;
                            border-radius: 8px !important;
                            padding: 20px !important;
                            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
                        }
                    </style>
                `);
            }
            
            // Ensure modal opens properly if Bootstrap auto-trigger fails
            const modal = document.getElementById('newWithdrawalModal');
            if (modal) {
                // Force show the modal directly
                setTimeout(() => {
                    $(modal).addClass('show').css({
                        'display': 'block',
                        'z-index': '-1',
                        'position': 'fixed',
                        'top': '0',
                        'left': '0',
                        'width': '100%',
                        'height': '100%',
                        'background': 'rgba(0, 0, 0, 0.5)'
                    });
                    
                    $('body').addClass('modal-open').css('overflow', 'hidden');
                    
                    console.log('Modal forced to show');
                    console.log('Modal element:', modal);
                    console.log('Modal jQuery object:', $(modal));
                    console.log('Modal classes:', $(modal).attr('class'));
                    console.log('Modal styles:', $(modal).attr('style'));
                    
                    // Try Bootstrap method as well
                    if (typeof window.bootstrap !== 'undefined' && window.bootstrap.Modal) {
                        try {
                            const bootstrapModal = new window.bootstrap.Modal(modal, {
                                backdrop: true,
                                keyboard: true,
                                focus: true
                            });
                            bootstrapModal.show();
                        } catch (error) {
                            console.log('Bootstrap modal failed:', error);
                        }
                    }
                }, 100);
            }
        });
        
        // Modal backdrop debugging and fixes - FORCE VISIBILITY
        $('#newWithdrawalModal').on('show.bs.modal', function(e) {
            console.log('Withdrawal modal about to show');
            // Remove any existing problematic backdrops
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            
            // Force modal to be visible
            const $modal = $(this);
            $modal.css({
                'z-index': '-1',
                'display': 'block',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%'
            });
        });
        
        $('#newWithdrawalModal').on('shown.bs.modal', function(e) {
            console.log('Withdrawal modal shown successfully');
            // Force everything to be visible
            const $modal = $(this);
            const $backdrop = $('.modal-backdrop');
            
            // Force backdrop styling
            if ($backdrop.length) {
                $backdrop.css({
                    'z-index': '-1',
                    'opacity': '0.5',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100vw',
                    'height': '100vh',
                    'background-color': 'rgba(0, 0, 0, 0.5)'
                });
            }
            
            // Force modal to be on top and visible
            $modal.css({
                'z-index': '99999',
                'display': 'block !important',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%'
            });
            
            // Force modal dialog to be centered and visible
            $modal.find('.modal-dialog').css({
                'z-index': '100000',
                'position': 'relative',
                'margin': '1.75rem auto',
                'max-width': '500px'
            });
            
            // Force modal content to be visible
            $modal.find('.modal-content').css({
                'z-index': '100001',
                'position': 'relative',
                'background': 'white',
                'border': '1px solid #dee2e6',
                'border-radius': '0.375rem',
                'box-shadow': '0 0.5rem 1rem rgba(0, 0, 0, 0.15)'
            });
            
            $modal.addClass('show');
            
            // Add modal-open class to body
            $('body').addClass('modal-open').css('overflow', 'hidden');
            
            console.log('Modal z-index:', $modal.css('z-index'));
            console.log('Modal display:', $modal.css('display'));
            console.log('Modal position:', $modal.css('position'));
        });
        
        $('#newWithdrawalModal').on('hide.bs.modal', function(e) {
            console.log('Withdrawal modal about to hide');
        });
        
        $('#newWithdrawalModal').on('hidden.bs.modal', function(e) {
            console.log('Withdrawal modal hidden successfully');
            // Clean up any remaining backdrop
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $(this).removeClass('show');
        });
    });
</script>

<!-- Withdrawal Functionality JavaScript -->
<script>
$(document).ready(function() {
    // Initialize withdrawal functionality
    initializeWithdrawalFunctionality();
    
    // Load withdrawal history on page load
    loadWithdrawalHistory();
});

function initializeWithdrawalFunctionality() {
    // Refresh withdrawals button
    $('#refreshWithdrawals').on('click', function() {
        refreshWithdrawals();
    });
    
    // Withdrawal form submissions
    $('#bankWithdrawalForm').on('submit', function(e) {
        e.preventDefault();
        submitWithdrawal(this, 'bank_transfer');
    });
    
    $('#cryptoWithdrawalForm').on('submit', function(e) {
        e.preventDefault();
        submitWithdrawal(this, 'cryptocurrency');
    });
    
    // Tab change handlers
    $('#withdrawalHistoryTabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        const target = $(e.target).data('bs-target');
        if (target === '#pending-withdrawals') {
            loadPendingWithdrawals();
        } else if (target === '#completed-withdrawals') {
            loadCompletedWithdrawals();
        } else if (target === '#all-withdrawals') {
            loadAllWithdrawals();
        }
    });

    $('#bank_withdrawal_amount').on('input', function() {
    const amount = parseFloat($(this).val()) || 0;

    const maxAmount = parseFloat('{{ $finance['balance'] ?? 0 }}');

    const roundedMax = Number(maxAmount.toFixed(2));

    if (amount > roundedMax) {
        $(this).val(roundedMax);
    }
});

    $('#crypto_withdrawal_amount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;

    const maxAmount = parseFloat('{{ $finance['balance'] ?? 0 }}');

    const roundedMax = Number(maxAmount.toFixed(2));

    if (amount > roundedMax) {
        $(this).val(roundedMax);
    }
    });
}

function submitWithdrawal(form, method) {
    const $form = $(form);
    const $submitBtn = $form.find('button[type="submit"]');
    const originalText = $submitBtn.html();
    
    // Show loading state
    $submitBtn.html('<i class="bi bi-arrow-clockwise spin"></i> {{ __("web.processing") }}...').prop('disabled', true);
    
    // Get form data
    const formData = new FormData(form);
    
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Show success message
                showNotification('{{ __("web.withdrawal_submitted_successfully") }}', 'success');
                
                // Reset form
                form.reset();
                
                // Refresh withdrawal history
                loadWithdrawalHistory();
                
                // Update balance if provided
                if (response.new_balance !== undefined) {
                    $('.balance-amount').text('$' + parseFloat(response.new_balance).toFixed(2));
                }
            } else {
                showNotification(response.message || '{{ __("web.withdrawal_submission_failed") }}', 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = '{{ __("web.withdrawal_submission_failed") }}';
            
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showNotification(errorMessage, 'error');
        },
        complete: function() {
            // Restore button
            $submitBtn.html(originalText).prop('disabled', false);
        }
    });
}

function refreshWithdrawals() {
    const $refreshBtn = $('#refreshWithdrawals');
    const originalText = $refreshBtn.html();
    
    // Show loading state
    $refreshBtn.html('<i class="bi bi-arrow-clockwise spin"></i> {{ __("web.refreshing") }}...').prop('disabled', true);
    
    // Reload current active tab
    const activeTab = $('#withdrawalHistoryTabs .nav-link.active').data('bs-target');
    
    setTimeout(() => {
        if (activeTab === '#pending-withdrawals') {
            loadPendingWithdrawals();
        } else if (activeTab === '#completed-withdrawals') {
            loadCompletedWithdrawals();
        } else if (activeTab === '#all-withdrawals') {
            loadAllWithdrawals();
        }
        
        // Restore button
        $refreshBtn.html(originalText).prop('disabled', false);
        
        showNotification('{{ __("web.withdrawals_refreshed_successfully") }}', 'success');
    }, 1000);
}

function loadWithdrawalHistory() {
    loadPendingWithdrawals();
}

function loadPendingWithdrawals() {
    loadWithdrawalsData('pending', '#pendingWithdrawalsTableBody', '#pendingWithdrawalsEmpty', '#pendingWithdrawalsCount');
}

function loadCompletedWithdrawals() {
    loadWithdrawalsData('completed', '#completedWithdrawalsTableBody', '#completedWithdrawalsEmpty', '#completedWithdrawalsCount');
}

function loadAllWithdrawals() {
    loadWithdrawalsData('all', '#allWithdrawalsTableBody', '#allWithdrawalsEmpty', '#allWithdrawalsCount');
}

function loadWithdrawalsData(type, tableBodySelector, emptyStateSelector, countSelector) {
    const $tableBody = $(tableBodySelector);
    const $emptyState = $(emptyStateSelector);
    const $count = $(countSelector);
    
    // Show loading
    $tableBody.html('<tr><td colspan="6" class="text-center"><i class="bi bi-arrow-clockwise spin"></i> {{ __("web.loading") }}...</td></tr>');
    $emptyState.hide();
    
    $.ajax({
        url: '{{ route("client.withdrawals.history") }}',
        method: 'GET',
        data: { type: type },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
                let html = '';
                response.data.forEach(function(withdrawal) {
                    html += buildWithdrawalRow(withdrawal, type);
                });
                $tableBody.html(html);
                $emptyState.hide();
                $count.text(response.data.length);
            } else {
                $tableBody.empty();
                $emptyState.show();
                $count.text('0');
            }
        },
        error: function() {
            $tableBody.html('<tr><td colspan="6" class="text-center text-danger">{{ __("web.error_loading_withdrawals") }}</td></tr>');
            $emptyState.hide();
            $count.text('0');
        }
    });
}

function buildWithdrawalRow(withdrawal, type) {
    const statusBadge = getStatusBadge(withdrawal.status);
    const methodIcon = getMethodIcon(withdrawal.method);
    const actions = type === 'pending' ? 
        `<button class="btn btn-sm btn-outline-danger" onclick="cancelWithdrawal(${withdrawal.id})">
            <i class="bi bi-x-circle"></i> {{ __('web.cancel') }}
         </button>` : 
        '<span class="text-muted">-</span>';
    
    const completionDate = withdrawal.completion_date ? 
        new Date(withdrawal.completion_date).toLocaleDateString() : 
        '<span class="text-muted">-</span>';
    
    return `
        <tr>
            <td>${new Date(withdrawal.created_at).toLocaleDateString()}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="${methodIcon} me-2"></i>
                    ${withdrawal.method_display}
                </div>
            </td>
            <td class="fw-bold">$${parseFloat(withdrawal.amount).toFixed(2)}</td>
            <td>${statusBadge}</td>
            <td>
                <button class="btn btn-sm btn-outline-info" onclick="showWithdrawalDetails(${withdrawal.id})">
                    <i class="bi bi-eye"></i> {{ __('web.view') }}
                </button>
            </td>
            ${type === 'pending' ? `<td>${actions}</td>` : `<td>${completionDate}</td>`}
        </tr>
    `;
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">{{ __("web.pending") }}</span>',
        'processing': '<span class="badge bg-info">{{ __("web.processing") }}</span>',
        'completed': '<span class="badge bg-success">{{ __("web.completed") }}</span>',
        'cancelled': '<span class="badge bg-danger">{{ __("web.cancelled") }}</span>',
        'rejected': '<span class="badge bg-danger">{{ __("web.rejected") }}</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">' + status + '</span>';
}

function getMethodIcon(method) {
    const icons = {
        'bank_transfer': 'bi bi-bank',
        'cryptocurrency': 'bi bi-currency-bitcoin',
        'paypal': 'bi bi-paypal'
    };
    return icons[method] || 'bi bi-credit-card';
}

function cancelWithdrawal(withdrawalId) {
    if (!confirm('{{ __("web.confirm_cancel_withdrawal") }}')) {
        return;
    }
    
    $.ajax({
        url: '{{ route("client.withdrawal.cancel") }}',
        method: 'POST',
        data: {
            withdrawal_id: withdrawalId
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('{{ __("web.withdrawal_cancelled_successfully") }}', 'success');
                loadWithdrawalHistory();
            } else {
                showNotification(response.message || '{{ __("web.withdrawal_cancellation_failed") }}', 'error');
            }
        },
        error: function() {
            showNotification('{{ __("web.withdrawal_cancellation_failed") }}', 'error');
        }
    });
}

function showWithdrawalDetails(withdrawalId) {
    // Implementation for showing withdrawal details modal
    console.log('Show withdrawal details for ID:', withdrawalId);
}

function showNotification(message, type) {
    // Create notification element
    const notification = $(`
        <div class="alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    // Add to body
    $('body').append(notification);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}

// Add CSS for spinning animation
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// ============= NOTIFICATION FUNCTIONS =============

// Mark all notifications as read
function markAllNotificationsAsRead() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove all notification items from the popup
            const notificationMessages = document.getElementById('notificationPopupMessages');
            if (notificationMessages) {
                notificationMessages.innerHTML = `
                    <div class="no-notifications-message-modern">
                        <div class="no-notifications-illustration">
                            <div class="notification-bell-empty">
                                <i class="bi bi-bell-slash"></i>
                            </div>
                            <div class="notification-waves">
                                <span class="wave wave-1"></span>
                                <span class="wave wave-2"></span>
                                <span class="wave wave-3"></span>
                            </div>
                        </div>
                        <div class="no-notifications-content">
                            <h5>{{ __('web.all_caught_up') }}</h5>
                            <p>{{ __('web.no_notifications') }}</p>
                            <small>{{ __('web.notify_important') }}</small>
                        </div>
                    </div>
                `;
            }
            
            // Update notification badge
            updateNotificationBadge(0);
            
            // Hide the footer since there are no notifications
            const footer = document.querySelector('.notification-popup-footer-modern');
            if (footer) {
                footer.style.display = 'none';
            }
            
            showNotification('All notifications marked as read', 'success');
        } else {
            showNotification(data.message || 'Error marking notifications as read', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error marking notifications as read', 'error');
    });
}

// Clear all notifications (delete all)
function clearAllNotifications() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (!confirm('Are you sure you want to clear all notifications? This action cannot be undone.')) {
        return;
    }
    
    fetch('/notifications/clear-all', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove all notification items from the popup
            const notificationMessages = document.getElementById('notificationPopupMessages');
            if (notificationMessages) {
                notificationMessages.innerHTML = `
                    <div class="no-notifications-message-modern">
                        <div class="no-notifications-illustration">
                            <div class="notification-bell-empty">
                                <i class="bi bi-bell-slash"></i>
                            </div>
                            <div class="notification-waves">
                                <span class="wave wave-1"></span>
                                <span class="wave wave-2"></span>
                                <span class="wave wave-3"></span>
                            </div>
                        </div>
                        <div class="no-notifications-content">
                            <h5>{{ __('web.all_caught_up') }}</h5>
                            <p>{{ __('web.no_notifications') }}</p>
                            <small>{{ __('web.notify_important') }}</small>
                        </div>
                    </div>
                `;
            }
            
            // Update notification badge
            updateNotificationBadge(0);
            
            // Hide the footer since there are no notifications
            const footer = document.querySelector('.notification-popup-footer-modern');
            if (footer) {
                footer.style.display = 'none';
            }
            
            showNotification('All notifications cleared', 'success');
        } else {
            showNotification(data.message || 'Error clearing notifications', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error clearing notifications', 'error');
    });
}

// Delete individual notification
function deleteNotification(notificationId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/notifications/${notificationId}/delete`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the notification item from the popup
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.remove();
            }
            
            // Update the notification badge count
            updateNotificationBadge(data.remaining_count);
            
            // If no notifications left, show the "no notifications" message
            if (data.remaining_count === 0) {
                const notificationMessages = document.getElementById('notificationPopupMessages');
                if (notificationMessages) {
                    notificationMessages.innerHTML = `
                        <div class="no-notifications-message-modern">
                            <div class="no-notifications-illustration">
                                <div class="notification-bell-empty">
                                    <i class="bi bi-bell-slash"></i>
                                </div>
                                <div class="notification-waves">
                                    <span class="wave wave-1"></span>
                                    <span class="wave wave-2"></span>
                                    <span class="wave wave-3"></span>
                                </div>
                            </div>
                            <div class="no-notifications-content">
                                <h5>{{ __('web.all_caught_up') }}</h5>
                                <p>{{ __('web.no_notifications') }}</p>
                                <small>{{ __('web.notify_important') }}</small>
                            </div>
                        </div>
                    `;
                }
                
                // Hide the footer since there are no notifications
                const footer = document.querySelector('.notification-popup-footer-modern');
                if (footer) {
                    footer.style.display = 'none';
                }
            }
            
            showNotification('Notification deleted', 'success');
        } else {
            showNotification(data.message || 'Error deleting notification', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deleting notification', 'error');
    });
}

// Update notification badge function
function updateNotificationBadge(count) {
    const notificationIcon = document.querySelector('.notification-icon');
    const existingBadge = notificationIcon.querySelector('.notification-badge');
    
    if (count > 0) {
        if (existingBadge) {
            existingBadge.textContent = count;
        } else {
            // Create new badge if it doesn't exist
            const badge = document.createElement('span');
            badge.className = 'notification-badge';
            badge.textContent = count;
            notificationIcon.appendChild(badge);
        }
    } else {
        // Remove badge if count is 0
        if (existingBadge) {
            existingBadge.remove();
        }
    }
}
</script>

<!-- Modern Document Upload JavaScript -->
<script src="{{ asset('assets/js/document-upload-modern.js') }}"></script>


</body>
</html>

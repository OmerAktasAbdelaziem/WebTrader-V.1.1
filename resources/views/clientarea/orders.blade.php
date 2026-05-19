@extends('layouts.mobile')
<link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.css?v1.599') }}">
<link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker-theme.min.css?v1.599') }}">

<style>
    /* Modern Orders Page Styling - Clean Off-white, Gray, Black Theme */
    .orders-modern-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 0;
    }

    /* Modern Tab Navigation */
    .modern-tabs {
        background: #ffffff;
        border-radius: 0 0 20px 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .modern-tabs .nav-link {
        background: transparent;
        border: none;
        border-radius: 12px;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 12px 8px;
        transition: all 0.3s ease;
        position: relative;
        margin: 0 2px;
    }

    .modern-tabs .nav-link.active {
        background: #000000;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .modern-tabs .nav-link:hover:not(.active) {
        background: #e9ecef;
        color: #495057;
    }

    /* Order Count Badge */
    .order-count {
        background: rgba(108, 117, 125, 0.2);
        border-radius: 8px;
        padding: 2px 6px;
        font-size: 0.7rem;
        margin-left: 4px;
    }

    .active .order-count {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Modern Card Design */
    .order-card {
        background: #ffffff;
        border-radius: 16px;
        margin: 0.75rem;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    /* Order Item Styling */
    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .order-item:hover {
        background: #ffffff;
        border-color: #dee2e6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .order-item.expanded {
        border-radius: 12px 12px 0 0;
        margin-bottom: 0;
        background: #ffffff;
    }

    /* Symbol and Type */
    .symbol-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .symbol-name {
        font-weight: 700;
        font-size: 1rem;
        color: #000000;
        margin-bottom: 4px;
    }

    .order-type {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        border: 1px solid;
    }

    .order-type.buy {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }

    .order-type.sell {
        background: #ffffff;
        color: #000000;
        border-color: #000000;
    }

    /* Amount and PnL */
    .amount-pnl-section {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
    }

    .order-amount {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .order-pnl {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .order-pnl.positive {
        color: #28a745;
        background: transparent;
        border: none;
    }

    .order-pnl.negative {
        color: #dc3545;
        background: transparent;
        border: none;
    }

    /* Order Details Expansion */
    .order-details {
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border-top: 1px solid #e9ecef;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.85rem;
        color: #000000;
        font-weight: 600;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-modern {
        flex: 1;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        border: 1px solid;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    /* Multi-close button specific styling */
    .multi-select-header .btn-modern {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.75rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        min-width: auto;
        flex: none;
    }

    .multi-select-header .btn-modern:hover {
        background: #343a40;
        border-color: #343a40;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    }

    .btn-edit {
        background: #ffffff;
        color: #000000;
        border-color: #000000;
    }

    .btn-edit:hover {
        background: #000000;
        color: #ffffff;
    }

    .btn-close {
        background: #6c757d;
        color: #ffffff;
        border-color: #6c757d;
    }

    .btn-close:hover {
        background: #495057;
        border-color: #495057;
    }

    .btn-delete {
        background: #000000;
        color: #ffffff;
        border-color: #000000;
    }

    .btn-delete:hover {
        background: #343a40;
        border-color: #343a40;
    }

    /* Multi-select Controls */
    .multi-select-header {
        background: #ffffff;
        border-radius: 12px;
        padding: 1rem;
        margin: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .select-all-section {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modern-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #000000;
        accent-color: #000000;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
        background: #ffffff;
        border-radius: 16px;
        margin: 0.75rem;
        border: 1px solid #e9ecef;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }

    .empty-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #000000;
    }

    .empty-description {
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Filter Controls */
    .filter-controls {
        background: #ffffff;
        border-radius: 12px;
        padding: 1rem;
        margin: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .filter-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #ffffff;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        color: #000000;
    }

    .filter-select:focus {
        outline: none;
        border-color: #000000;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    /* Balance Summary Card */
    .balance-summary {
        background: #6c757d;
        color: #ffffff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
        margin: 0.75rem 0.75rem 0 0.75rem;
        position: sticky;
        bottom: 0;
        z-index: 10;
        box-shadow: 0 -4px 20px rgba(108, 117, 125, 0.3);
    }

    .balance-item {
        text-align: center;
        padding: 0.5rem;
    }

    .balance-label {
        font-size: 0.7rem;
        color: #f8f9fa;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .balance-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: #ffffff;
    }

    .balance-value.positive {
        color: #ffffff;
    }

    .balance-value.negative {
        color: #e9ecef;
    }

    /* Scrollable Container */
    .scrollable-content {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding-bottom: 1rem;
    }

    .scrollable-content::-webkit-scrollbar {
        width: 4px;
    }

    .scrollable-content::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 2px;
    }

    .scrollable-content::-webkit-scrollbar-thumb {
        background: #6c757d;
        border-radius: 2px;
    }

    .scrollable-content::-webkit-scrollbar-thumb:hover {
        background: #495057;
    }

    /* Mobile Optimizations */
    @media (max-width: 576px) {
        .order-card {
            margin: 0.5rem;
            padding: 1rem;
            border-radius: 12px;
        }
        
        .order-item {
            padding: 0.75rem;
            border-radius: 8px;
        }
        
        .symbol-name {
            font-size: 0.9rem;
        }
        
        .order-pnl {
            font-size: 1rem;
        }
        
        .btn-modern {
            padding: 8px 12px;
            font-size: 0.75rem;
        }
        
        .balance-summary {
            padding: 1rem;
            border-radius: 12px 12px 0 0;
        }
    }

    /* Loading States */
    .loading-skeleton {
        background: linear-gradient(90deg, #f8f9fa 25%, #e9ecef 50%, #f8f9fa 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 6px;
        height: 20px;
        margin: 4px 0;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 12px 12px 0 0;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        color: #000000;
        font-weight: 600;
        margin: 0;
        flex: 1;
        padding-right: 1rem;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #6c757d;
        padding: 0.5rem;
        margin: 0;
        position: relative;
        z-index: 10;
    }

    .btn-primary {
        background: #000000;
        border-color: #000000;
        color: #ffffff;
    }

    .btn-primary:hover {
        background: #343a40;
        border-color: #343a40;
    }

    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background: #495057;
        border-color: #495057;
    }

    .form-control {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        color: #000000;
    }

    .form-control:focus {
        border-color: #000000;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        color: #000000;
        font-weight: 500;
    }

    /* Dark Theme Styles */
    [data-theme="dark"] .orders-modern-container {
        background: #121624 !important;
        color: #ffffff !important;
    }

    /* Dark Theme Tab Navigation */
    [data-theme="dark"] .modern-tabs {
        background: #1C1F26 !important;
        border-bottom: 1px solid #2a2d3a !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .modern-tabs .nav-link {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .modern-tabs .nav-link.active {
        background: #FFD700 !important;
        color: #121624 !important;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4) !important;
    }

    [data-theme="dark"] .modern-tabs .nav-link:hover:not(.active) {
        background: #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .order-count {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .active .order-count {
        background: rgba(18, 22, 36, 0.3) !important;
        color: #121624 !important;
    }

    /* Dark Theme Card Design */
    [data-theme="dark"] .order-card {
        background: #1C1F26 !important;
        border: 1px solid #2a2d3a !important;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .order-card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
    }

    /* Dark Theme Order Item Styling */
    [data-theme="dark"] .order-item {
        background: #141927 !important;
        border: 1px solid #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .order-item:hover {
        background: #1C1F26 !important;
        border-color: #3a3d4a !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .order-item.expanded {
        background: #1C1F26 !important;
    }

    [data-theme="dark"] .symbol-name {
        color: #ffffff !important;
    }

    [data-theme="dark"] .order-type.buy {
        background: #03a15b !important;
        /* color: #121624 !important;
        border-color: #FFD700 !important; */
    }

    [data-theme="dark"] .order-type.sell {
        background: #c30606 !important;
        color: #ffffff !important
        /* border-color: #FFD700 !important; */
    }

    [data-theme="dark"] .order-amount {
        color: #b0b3b8 !important;
    }

    /* Dark Theme Order Details */
    [data-theme="dark"] .order-details {
        background: #121624 !important;
        border-top: 1px solid #2a2d3a !important;
    }

    [data-theme="dark"] .detail-row {
        border-bottom: 1px solid #2a2d3a !important;
    }

    [data-theme="dark"] .detail-label {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .detail-value {
        color: #ffffff !important;
    }

    /* Dark Theme Action Buttons */
    [data-theme="dark"] .btn-modern {
        color: #ffffff !important;
    }

    [data-theme="dark"] .multi-select-header .btn-modern {
        background: #FFD700 !important;
        color: #121624 !important;
        border-color: #FFD700 !important;
    }

    [data-theme="dark"] .multi-select-header .btn-modern:hover {
        background: #e6c200 !important;
        border-color: #e6c200 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-edit {
        background: transparent !important;
        color: #FFD700 !important;
        border-color: #FFD700 !important;
    }

    [data-theme="dark"] .btn-edit:hover {
        background: #FFD700 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-close {
        background: #b0b3b8 !important;
        color: #121624 !important;
        border-color: #b0b3b8 !important;
    }

    [data-theme="dark"] .btn-close:hover {
        background: #9ca0a6 !important;
        border-color: #9ca0a6 !important;
    }

    [data-theme="dark"] .btn-delete {
        background: #FFD700 !important;
        color: #121624 !important;
        border-color: #FFD700 !important;
    }

    [data-theme="dark"] .btn-delete:hover {
        background: #e6c200 !important;
        border-color: #e6c200 !important;
    }

    /* Dark Theme Multi-select Controls */
    [data-theme="dark"] .multi-select-header {
        background: #1C1F26 !important;
        border: 1px solid #2a2d3a !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .modern-checkbox {
        border: 2px solid #FFD700 !important;
        accent-color: #FFD700 !important;
    }

    /* Dark Theme Empty State */
    [data-theme="dark"] .empty-state {
        background: #1C1F26 !important;
        border: 1px solid #2a2d3a !important;
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .empty-icon {
        color: #3a3d4a !important;
    }

    [data-theme="dark"] .empty-title {
        color: #ffffff !important;
    }

    [data-theme="dark"] .empty-description {
        color: #b0b3b8 !important;
    }

    /* Dark Theme Filter Controls */
    [data-theme="dark"] .filter-controls {
        background: #1C1F26 !important;
        border: 1px solid #2a2d3a !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
    }

    [data-theme="dark"] .filter-select {
        background: #141927 !important;
        border: 1px solid #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .filter-select:focus {
        border-color: #FFD700 !important;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.3) !important;
    }

    [data-theme="dark"] .filter-select option {
        background: #141927 !important;
        color: #ffffff !important;
    }

    /* Dark Theme Balance Summary */
    [data-theme="dark"] .balance-summary {
        background: #FFD700 !important;
        color: #121624 !important;
        box-shadow: 0 -4px 20px rgba(255, 215, 0, 0.3) !important;
    }

    [data-theme="dark"] .balance-label {
        color: #121624 !important;
    }

    [data-theme="dark"] .balance-value {
        color: #121624 !important;
    }

    [data-theme="dark"] .balance-value.positive {
        color: #121624 !important;
    }

    [data-theme="dark"] .balance-value.negative {
        color: #8b0000 !important;
    }

    /* Dark Theme Scrollbar */
    [data-theme="dark"] .scrollable-content::-webkit-scrollbar-track {
        background: #2a2d3a !important;
    }

    [data-theme="dark"] .scrollable-content::-webkit-scrollbar-thumb {
        background: #b0b3b8 !important;
    }

    [data-theme="dark"] .scrollable-content::-webkit-scrollbar-thumb:hover {
        background: #9ca0a6 !important;
    }

    /* Dark Theme Modal Styling */
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

    [data-theme="dark"] .modal .btn-close {
        color: #b0b3b8 !important;
        filter: invert(1) !important;
    }

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
        background: #b0b3b8 !important;
        border-color: #b0b3b8 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .btn-secondary:hover {
        background: #9ca0a6 !important;
        border-color: #9ca0a6 !important;
        color: #121624 !important;
    }

    [data-theme="dark"] .form-control {
        background: #141927 !important;
        border: 1px solid #2a2d3a !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control:focus {
        background: #141927 !important;
        border-color: #FFD700 !important;
        box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.3) !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .form-control::placeholder {
        color: #b0b3b8 !important;
    }

    [data-theme="dark"] .form-label {
        color: #ffffff !important;
    }

    /* Dark Theme Loading Skeleton */
    [data-theme="dark"] .loading-skeleton {
        background: linear-gradient(90deg, #121624 25%, #1C1F26 50%, #121624 75%) !important;
    }
</style>

@section('content')
<div class="orders-modern-container">
    <!-- Modern Tab Navigation -->
    <ul class="nav nav-tabs modern-tabs w-100" id="quotesTabs" role="tablist">
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(!isset($tab) || $tab == 'active' || $tab == 'fav' || (isset($tab) && !in_array($tab, ['pending', 'history']))) active @endif" 
                    id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                {{__('web.active')}}
                <span class="order-count">{{$activeOrders->count()}}</span>
            </button>
        </li>
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(($tab == 'pending' && !session('tab')) || session('tab') == 'pending') active @endif" 
                    id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                {{__('web.pending')}}
                <span class="order-count">{{$pendingOrders->count()}}</span>
            </button>
        </li>
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(($tab == 'history' && !session('tab')) || session('tab') == 'history') active @endif" 
                    id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                {{__('web.history')}}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="firstTabSetContent">
        <!-- Active Orders Tab -->
        <div class="tab-pane fade @if(!isset($tab) || $tab == 'active' || $tab == 'fav' || (isset($tab) && !in_array($tab, ['pending', 'history']))) show active @endif" id="active" role="tabpanel" aria-labelledby="active-tab">
            @if($activeOrders->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <span class="iconify" data-icon="mdi:clipboard-text-outline"></span>
                    </div>
                    <div class="empty-title">{{__('web.no_active_orders')}}</div>
                    <div class="empty-description">Start trading to see your active positions here</div>
                </div>
            @else
                <!-- Multi-select Header -->
                <div class="multi-select-header">
                    <div class="select-all-section">
                        <input class="modern-checkbox check-all-table" data-target="check-active" type="checkbox" id="selectAll">
                        <label for="selectAll" class="detail-label">Select All</label>
                    </div>
                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                        <button type="button" class="btn btn-modern" data-bs-toggle="modal" data-bs-target="#multiCloseModal">
                            {{__('web.multi_close')}}
                        </button>
                    @endif
                </div>

                <div class="scrollable-content">
                    @foreach($activeOrders as $index => $order)
                        <div class="order-card">
                            <div class="order-item" data-target="#orderDetails{{ $order->id }}" aria-expanded="false">
                                <input class="modern-checkbox check-active check-number" type="checkbox" form="multiCloseForm" name="order_id[]" value="{{$order->id}}" onclick="event.stopPropagation()">
                                
                                <div class="symbol-section">
                                    <div class="symbol-name">{{ $order->asset->name }}</div>
                                    <div class="order-type @if($order->type == 1) buy @else sell @endif">
                                        @if($order->type == 1) {{ __('web.buy') }} @else {{ __('web.sell') }} @endif
                                    </div>
                                </div>
                                
                                <div class="amount-pnl-section">
                                    <div class="order-amount">{{ number_format($order->amount, 2) }}</div>
                                    <div class="pnl" data-order-id="{{$order->id}}">
                                        <div class="order-pnl {{$order->pnl < 0 ? 'negative' : 'positive'}} active_pnl" data-order-id="{{$order->id}}">
                                            ${{ number_format($order->pnl, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="orderDetails{{ $order->id }}" class="collapse order-details">
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.id')}}</span>
                                    <span class="detail-value">{{ $order->id }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.created_at')}}</span>
                                    <span class="detail-value">{{ $order->created_at instanceof \Carbon\Carbon ? $order->created_at->format('M d, Y H:i') : $order->created_at }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.open_price')}}</span>
                                    <span class="detail-value">${{ number_format($order->open_price, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.tp')}}</span>
                                    <span class="detail-value">${{ number_format($order->s_p, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.sl')}}</span>
                                    <span class="detail-value">${{ number_format($order->s_l, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.required_margin')}}</span>
                                    <span class="detail-value">${{ number_format($order->required_margin, 2) }}</span>
                                </div>
                                
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-modern btn-edit" data-bs-toggle="modal" data-bs-target="#editOrderModal{{$order->id}}">
                                        {{__('web.edit_order')}}
                                    </button>
                                    @if(!isset(auth()->guard('client')->user()->options['cantClose']))
                                        <button type="button" class="btn btn-modern " data-bs-toggle="modal" data-bs-target="#closeOrderModal{{$order->id}}">
                                            {{__('web.close')}}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pending Orders Tab -->
        <div class="tab-pane fade @if(($tab == 'pending' && !session('tab')) || session('tab') == 'pending') show active @endif" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            @if($pendingOrders->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <span class="iconify" data-icon="mdi:clock-outline"></span>
                    </div>
                    <div class="empty-title">{{__('web.no_pending_orders')}}</div>
                    <div class="empty-description">Set limit orders to see them here</div>
                </div>
            @else
                <div class="scrollable-content">
                    @foreach($pendingOrders as $index => $order)
                        <div class="order-card">
                            <div class="order-item">
                                <div class="symbol-section">
                                    <div class="symbol-name">{{ $order->asset->name }}</div>
                                    <div class="order-type @if($order->type == 1) buy @else sell @endif">
                                        @if($order->type == 1) {{ __('web.buy') }} @else {{ __('web.sell') }} @endif
                                    </div>
                                </div>
                                
                                <div class="amount-pnl-section">
                                    <div class="order-amount">{{ number_format($order->amount, 2) }}</div>
                                    <div class="detail-value">${{ number_format($order->open_price, 4) }}</div>
                                </div>
                                
                                <form action="{{ route('order.delete', ['id'=>$order->id]) }}" class="d-none" method="POST" id="deleteOrderForm{{ $order->id }}">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="tab" value="pending">
                                </form>
                                <button type="submit" class="btn btn-modern btn-delete" form="deleteOrderForm{{ $order->id }}">
                                    {{__('web.delete')}}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- History Tab -->
        <div class="tab-pane fade @if(($tab == 'history' && !session('tab')) || session('tab') == 'history') show active @endif" id="history" role="tabpanel" aria-labelledby="history-tab">
            <!-- Filter Controls -->
            <div class="filter-controls">
                <form class="ajax-form" method="GET" data-tab="history">
                    <input type="hidden" name="tab" value="history">
                    <div class="row g-2">
                        <div class="col-6">
                            <select class="filter-select" name="type_filter" onchange="this.form.submit()">
                                <option value="general" @if ($type_filter == 'general') selected @endif>{{__('web.general_report')}}</option>
                                <option value="old_trader" @if ($type_filter == 'old_trader') selected @endif>{{__('web.old_trader')}}</option>
                                <option value="money_trx" @if ($type_filter == 'money_trx') selected @endif>{{__('web.money_trx')}}</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="filter-select" name="time_filter" onchange="this.form.submit()">
                                <option value="all" @if ($time_filter == 'all') selected @endif>{{__('web.all')}}</option>
                                <option value="today" @if ($time_filter == 'today') selected @endif>{{__('web.today')}}</option>
                                <option value="current_week" @if ($time_filter == 'current_week') selected @endif>{{__('web.current_week')}}</option>
                                <option value="current_month" @if ($time_filter == 'current_month') selected @endif>{{__('web.current_month')}}</option>
                                <option value="last_3_month" @if ($time_filter == 'last_3_month') selected @endif>{{__('web.last_3_month')}}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="scrollable-content">
                @php
                    $totalWithdraw = 0;
                    $totalBonusOut = 0;
                    $totalDeposit = 0;
                    $totalBonusIn = 0;
                    $totalPnl = 0;
                @endphp
                
                @foreach($history as $order)
                    @if (isset($order->closed_at) && $order->closed_at != null)
                        @php $totalPnl += $order->pnl; @endphp
                        <div class="order-card">
                            <div class="order-item" data-target="#historyDetails{{ $order->id }}" aria-expanded="false">
                                <div class="symbol-section">
                                    <div class="symbol-name">{{ $order->asset->name }}</div>
                                    <div class="order-type @if($order->type == 1) buy @else sell @endif">
                                        @if($order->type == 1) {{ __('web.buy') }} @else {{ __('web.sell') }} @endif
                                    </div>
                                </div>
                                
                                <div class="amount-pnl-section">
                                    <div class="order-amount">{{ number_format($order->amount, 2) }}</div>
                                    <div class="order-pnl {{$order->pnl < 0 ? 'negative' : 'positive'}}">
                                        ${{ number_format($order->pnl, 2) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div id="historyDetails{{ $order->id }}" class="collapse order-details">
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.id')}}</span>
                                    <span class="detail-value">{{ $order->id }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.created_at')}}</span>
                                    <span class="detail-value">{{ $order->created_at instanceof \Carbon\Carbon ? $order->created_at->format('M d, Y H:i') : $order->created_at }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.close_time')}}</span>
                                    <span class="detail-value">{{ $order->closed_at instanceof \Carbon\Carbon ? $order->closed_at->format('M d, Y H:i') : $order->closed_at }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.close_price')}}</span>
                                    <span class="detail-value">${{ number_format($order->close_price, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.tp')}}</span>
                                    <span class="detail-value">${{ number_format($order->s_p, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.sl')}}</span>
                                    <span class="detail-value">${{ number_format($order->s_l, 4) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">{{__('web.required_margin')}}</span>
                                    <span class="detail-value">${{ number_format($order->required_margin, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        @if ($order->type == 'deposit')
                            @php $totalDeposit += $order->amount; @endphp
                        @elseif($order->type == 'withdraw')
                            @php $totalWithdraw += $order->amount; @endphp
                        @elseif($order->type == 'bonus in')
                            @php $totalBonusIn += $order->amount; @endphp
                        @elseif($order->type == 'bonus out')
                            @php $totalBonusOut += $order->amount; @endphp
                        @endif
                        
                        <div class="order-card">
                            <div class="order-item">
                                <div class="symbol-section">
                                    <div class="symbol-name">{{ $order->id }}</div>
                                    <div class="order-type">{{ __('web.'.$order->type) }}</div>
                                </div>
                                
                                <div class="amount-pnl-section">
                                    <div class="order-amount">{{ number_format($order->amount, 2) }}</div>
                                    <div class="detail-value">${{ number_format($order->amount, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Balance Summary -->
            <div class="balance-summary">
                <div class="row g-3">
                    <div class="col-6 balance-item">
                        <div class="balance-label">{{__('web.deposit')}}</div>
                        <div class="balance-value">${{number_format($totalDeposit,'2','.',',')}}</div>
                    </div>
                    <div class="col-6 balance-item">
                        <div class="balance-label">{{__('web.withdraw')}}</div>
                        <div class="balance-value">${{number_format($totalWithdraw,'2','.',',')}}</div>
                    </div>
                    <div class="col-4 balance-item">
                        <div class="balance-label">{{__('web.bonus_in')}}</div>
                        <div class="balance-value">${{number_format($totalBonusIn,'2','.',',')}}</div>
                    </div>
                    <div class="col-4 balance-item">
                        <div class="balance-label">{{__('web.bonus_out')}}</div>
                        <div class="balance-value">${{number_format($totalBonusOut,'2','.',',')}}</div>
                    </div>
                    <div class="col-4 balance-item">
                        <div class="balance-label">{{__('web.pnl')}}</div>
                        <div class="balance-value @if ($totalPnl<0) negative @else positive @endif">${{number_format($totalPnl,'2','.',',')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="multiCloseModal" tabindex="-1" aria-labelledby="multiCloseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="multiCloseModalLabel">{{__('web.confirm_close')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('order.multiClose') }}" id="multiCloseForm" method="POST" class="d-none">
                    @csrf
                </form>
                <div>{{__('web.confirm_close_order')}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('web.close')}}</button>
                <button type="submit" class="btn btn-primary" form="multiCloseForm">{{__('web.confirm')}}</button>
            </div>
        </div>
    </div>
</div>

    @foreach($activeOrders as $index => $order)
        <div class="modal fade" id="closeOrderModal{{$order->id}}" tabindex="-1" aria-labelledby="closeOrderModal{{$order->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="closeOrderModal{{$order->id}}Label">{{__('web.confirm_close')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('order.close',$order->id) }}" id="closeForm{{$order->id}}" method="POST" class="d-none">
                            @csrf,
                            <input type="hidden" name="tab" value="active">
                        </form>
                        <div>{{__('web.confirm_close_order')}}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('web.close')}}</button>
                        <button type="submit" class="btn btn-primary" form="closeForm{{$order->id}}">{{__('web.confirm')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editOrderModal{{$order->id}}" tabindex="-1" aria-labelledby="editOrderModal{{$order->id}}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModal{{$order->id}}Label">{{__('web.edit_order')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tab" value="active">
                            <div class="row g-3 justify-content-center">
                                <div class="col-6">
                                    <div class="mt-2">
                                        <label for="s_l{{$order->id}}" class="form-label">{{__('web.set_stop_loss')}}</label>
                                        <input type="number" class="form-control" id="s_l{{$order->id}}" step="any" value="{{$order->s_l}}" name="s_l">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mt-2">
                                        <label for="s_p{{$order->id}}" class="form-label">{{__('web.set_take_profit')}}</label>
                                        <input type="number" class="form-control" id="s_p{{$order->id}}" step="any" value="{{$order->s_p}}" name="s_p">
                                    </div>
                                </div>
                                <div class="col-6 text-center">
                                    <button type="submit" class="btn btn-primary">{{ __('web.update_order') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        var client_id = {{auth()->guard('client')->user()->id}};
        var assetId = 1;
    </script>
    <script src="{{ url('assets/js/main_tp.js?v1.599') }}"></script>
    <script>
        $(document).ready(function () {
            // Modern checkbox interactions
            $('.check-all-table').on('change', function() {
                const target = $(this).data('target');
                const isChecked = $(this).is(':checked');
                $('.' + target).prop('checked', isChecked);
                updateMultiSelectUI();
            });

            $('.check-number').on('change', function() {
                updateMultiSelectUI();
            });

            function updateMultiSelectUI() {
                const checkedCount = $('.check-active:checked').length;
                const closeButton = $('.multi-select-header .btn-close');
                
                if (checkedCount > 0) {
                    closeButton.text(`{{__('web.close')}} (${checkedCount})`);
                    closeButton.removeClass('disabled');
                } else {
                    closeButton.text('{{__('web.close')}}');
                    closeButton.addClass('disabled');
                }
            }

            // Enhanced order item interactions
            $('.order-item').on('click', function(e) {
                if ($(e.target).is('input') || $(e.target).is('button')) {
                    return;
                }
                
                const $this = $(this);
                const target = $this.data('target');
                
                if (target) {
                    const $details = $(target);
                    const isCurrentlyExpanded = $details.hasClass('show');
                    
                    if (isCurrentlyExpanded) {
                        // Close the details
                        $this.removeClass('expanded');
                        $details.removeClass('show');
                        $this.attr('aria-expanded', 'false');
                    } else {
                        // Open the details
                        $this.addClass('expanded');
                        $details.addClass('show');
                        $this.attr('aria-expanded', 'true');
                    }
                }
            });

            // Smooth scroll for balance card
            $('.collabse_balance').on('click', function () {
                let $card = $(this).closest('.balance-summary');
                if ($card.length) {
                    if ($(this).hasClass('clicked')) {
                        $card.css('transform', 'translateY(0)');
                        $(this).removeClass('clicked');
                    } else {
                        const cardHeight = $card.find('.row').outerHeight();
                        $card.css('transform', `translateY(-${cardHeight}px)`);
                        $(this).addClass('clicked');
                    }
                }
            });

            // Real-time PnL updates with enhanced styling
            function updatePnL() {
                $('.active_pnl').each(function() {
                    const orderId = $(this).data('order-id');
                    // This would typically fetch real-time data
                    // For now, we'll just update the styling
                    const $pnl = $(this);
                    const value = parseFloat($pnl.text().replace('$', '').replace(',', ''));
                    
                    $pnl.removeClass('positive negative');
                    if (value >= 0) {
                        $pnl.addClass('positive');
                    } else {
                        $pnl.addClass('negative');
                    }
                });
            }

            // Auto-refresh PnL every 5 seconds
            setInterval(updatePnL, 5000);

            // Modern tab switching with animations
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const tabContent = $($(e.target).data('bs-target'));
                tabContent.addClass('fade-in');
                
                setTimeout(() => {
                    tabContent.removeClass('fade-in');
                }, 300);
            });

            // Touch-friendly interactions for mobile
            if ('ontouchstart' in window) {
                $('.order-item').on('touchstart', function() {
                    $(this).addClass('touch-active');
                }).on('touchend', function() {
                    setTimeout(() => {
                        $(this).removeClass('touch-active');
                    }, 150);
                });
            }

            // Initialize tooltips for better UX
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Loading states for forms
            $('form').on('submit', function() {
                const $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...');
            });

            // Enhanced filter animations
            $('.filter-select').on('change', function() {
                const $this = $(this);
                $this.addClass('loading');
                
                setTimeout(() => {
                    $this.removeClass('loading');
                }, 1000);
            });

            // Keyboard navigation support
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'a') {
                    e.preventDefault();
                    $('.check-all-table').trigger('click');
                }
            });

            // Add fade-in animation class
            $('<style>.fade-in { animation: fadeIn 0.3s ease-in; } @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } .touch-active { transform: scale(0.98); transition: transform 0.1s ease; }</style>').appendTo('head');
        });
    </script>
@endsection
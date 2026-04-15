@extends('layouts.mobile')
<style>
    /* Clean Monochrome Trading Platform */
    :root {
        --bg-primary: #fafafa;
        --bg-secondary: #f5f5f5;
        --bg-card: #ffffff;
        --bg-accent: #f0f0f0;
        --border-light: #e5e5e5;
        --border-medium: #cccccc;
        --border-dark: #999999;
        --card-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        --hover-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        --text-primary: #1a1a1a;
        --text-secondary: #404040;
        --text-muted: #666666;
        --text-light: #888888;
        --accent-primary: #333333;
        --accent-secondary: #555555;
        --accent-dark: #1a1a1a;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--bg-primary);
        font-family: 'Inter', 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        overflow-x: hidden;
        font-weight: 400;
        line-height: 1.5;
    }

    .container.p-0 {
        margin: 20px auto;
        max-width: 1400px;
        border-radius: 12px;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
        padding: 24px;
    }

    /* Market Status Bar */
    .market-status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-secondary);
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border: 1px solid var(--border-light);
    }

    .market-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 500;
        color: var(--text-primary);
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .status-indicator.live {
        background: #10b981;
        animation: pulse 2s infinite;
        box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
    }

    .status-indicator.closed {
        background: #ef4444;
        animation: none;
        box-shadow: 0 0 6px rgba(239, 68, 68, 0.4);
    }

    .market-time {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 400;
    }

    /* Pulse animation for live status */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
        }
        50% {
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.8);
        }
        100% {
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.4);
        }
    }

    /* Navigation Tabs */
    .nav-tabs {
        border: none;
        background: var(--bg-secondary);
        border-radius: 8px;
        padding: 4px;
        margin-bottom: 20px;
        display: flex;
        gap: 2px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .nav-tabs::-webkit-scrollbar {
        display: none;
    }

    .nav-tabs .nav-link {
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 500;
        background: transparent;
        color: var(--text-muted);
        border: none;
        border-radius: 6px;
        margin: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-tabs .nav-link i {
        font-size: 12px;
        margin-right: 6px;
    }

    .nav-tabs .nav-link.active {
        background: var(--bg-card);
        color: var(--text-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover:not(.active) {
        background: var(--bg-accent);
        color: var(--text-secondary);
    }

    .nav-item {
        flex: 1;
        min-width: 0;
    }

    /* Search Bar */
    .search {
        margin-bottom: 20px;
        position: relative;
        border-radius: 8px;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .search:focus-within {
        border-color: var(--border-medium);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .search::before {
        content: '🔍';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        z-index: 2;
        color: var(--text-muted);
    }

    .search input {
        padding: 12px 12px 12px 40px;
        border: none;
        background: transparent;
        font-size: 14px;
        font-weight: 400;
        width: 100%;
        outline: none;
        color: var(--text-primary);
    }

    .search input::placeholder {
        color: var(--text-muted);
        font-weight: 400;
    }

    /* Star Icons */
    .star-icon, .fa-star {
        cursor: pointer;
        color: var(--text-muted);
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .fa-star.text-dark, .star-icon.favorited {
        color: #FFD700 !important;
    }

    .fa-star.text-secondary {
        color: var(--text-muted) !important;
    }

    .fa-star:hover {
        color: var(--text-secondary) !important;
    }

    /* Clean Table Design */
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
    }

    table.table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        background: transparent;
    }

    thead th {
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-size: 13px;
        font-weight: 600;
        padding: 16px 12px;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: center;
        border-bottom: 1px solid var(--border-light);
    }

    .table-header {
        position: sticky;
        top: 0;
        background: var(--bg-secondary);
        z-index: 10;
    }

    th:first-child {
        text-align: left !important;
        padding-left: 20px;
    }

    th, td {
        border: none;
        padding: 14px 12px;
        font-size: 14px;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    td:first-child {
        padding-left: 20px;
    }

    /* Asset Rows */
    tr.asset-row {
        transition: all 0.2s ease;
        cursor: pointer;
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-light);
    }

    tr.asset-row:hover {
        background: var(--bg-secondary);
    }

    tr.asset-row:nth-child(even) {
        background: var(--bg-accent);
    }

    tr.asset-row:nth-child(even):hover {
        background: var(--bg-secondary);
    }

    tr.asset-row td {
        font-weight: 500;
        text-align: center;
    }

    tr.asset-row td:first-child {
        text-align: left;
    }

    .asset-row .name {
        font-weight: 600;
        color: var(--text-primary);
        margin-left: 8px;
        display: inline-block;
    }

    .asset-row:hover .name {
        color: var(--text-secondary);
    }

    /* Price Styling */
    .bid_price, .ask_price {
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: 0.5px;
        background: var(--bg-secondary);
        border-radius: 4px;
        padding: 8px 12px;
        margin: 2px;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .bid_price {
        color: #EF4444;
        border-color: rgba(239, 68, 68, 0.3);
    }

    .ask_price {
        color: #10B981;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .bid_price:hover, .ask_price:hover {
        background: var(--bg-accent);
        border-color: var(--border-medium);
    }

    /* Asset Details */
    tr.collapse.asset-details > td {
        background: var(--bg-secondary) !important;
        border-top: 1px solid var(--border-light);
        padding: 0;
    }

    .card.card-body {
        background: var(--bg-card);
        border-radius: 8px;
        border: 1px solid var(--border-light);
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin: 12px;
    }

    /* Asset Info Grid */
    .asset-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .info-item {
        background: var(--bg-secondary);
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        border: 1px solid var(--border-light);
        transition: all 0.2s ease;
    }

    .info-item:hover {
        background: var(--bg-accent);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-item .label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-item .value {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: var(--text-primary);
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
    }

    /* Action Buttons */
    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-top: 16px;
    }

    /* Buttons */
    .btn {
        border-radius: 6px !important;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 16px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-success {
        background: var(--text-primary);
        color: var(--bg-card);
        border-color: var(--text-primary);
    }

    .btn-success:hover {
        background: var(--text-secondary);
        border-color: var(--text-secondary);
        color: var(--bg-card);
    }

    .btn-danger {
        background: var(--bg-card);
        color: var(--text-primary);
        border-color: var(--border-medium);
    }

    .btn-danger:hover {
        background: var(--bg-secondary);
        border-color: var(--border-dark);
        color: var(--text-primary);
    }

    .btn-primary {
        background: var(--text-secondary);
        color: var(--bg-card);
        border-color: var(--text-secondary);
    }

    .btn-primary:hover {
        background: var(--text-primary);
        border-color: var(--text-primary);
        color: var(--bg-card);
    }

    /* Remove Bootstrap default dropdown arrow to prevent duplicates */
    .form-select {
        background-image: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
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

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 24px 28px 20px;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-accent) 100%);
        position: relative;
    }

    .modal-title {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 20px;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .modal-body {
        padding: 28px;
        background: var(--bg-card);
    }

    .btn-close {
        background: var(--bg-card);
        border: 1px solid var(--border-light);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        font-size: 14px;
        opacity: 0.8;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
    }

    .btn-close:hover {
        opacity: 1;
        background: var(--bg-secondary);
        border-color: var(--border-medium);
        color: var(--text-primary);
        transform: scale(1.05);
    }

    .btn-close:focus {
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    /* Enhanced Form Labels */
    .form-label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-size: 14px;
    }

    /* Switch Toggle Styling */
    .form-check-input {
        width: 44px;
        height: 22px;
        border-radius: 11px;
        background-color: var(--border-medium);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check-input:checked {
        background: var(--text-primary);
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    /* Table in Modals */
    .modal table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        margin-bottom: 0;
        background: var(--bg-card);
    }

    .modal table th {
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-accent) 100%);
        color: var(--text-primary);
        font-weight: 700;
        text-align: center;
        padding: 20px 16px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .modal table td {
        padding: 16px;
        border-bottom: 1px solid var(--border-light);
        text-align: center;
        font-weight: 500;
        color: var(--text-primary);
        background: var(--bg-card);
        transition: all 0.2s ease;
    }

    .modal table tbody tr:hover td {
        background: var(--bg-secondary);
        transform: scale(1.01);
    }

    .modal table tbody tr:last-child td {
        border-bottom: none;
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

    /* Responsive Modal Design */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
            min-height: calc(100vh - 1rem);
        }
        
        .modal-content {
            border-radius: 20px 20px 0 0;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            padding: 20px 24px 16px;
        }
        
        .modal-body {
            padding: 24px 20px;
        }
        
        .modal .btn-group-modern {
            flex-direction: column;
        }
        
        .modal .btn-group-modern .btn {
            min-width: 100%;
        }
        
        .modal table th,
        .modal table td {
            padding: 12px 8px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .modal-dialog {
            margin: 0;
            min-height: 100vh;
        }
        
        .modal-content {
            border-radius: 0;
            height: 100vh;
            max-height: none;
        }
        
        .modal-header {
            padding: 16px 20px;
        }
        
        .modal-body {
            padding: 20px 16px;
        }
        
        .modal .form-control,
        .modal .form-select {
            padding: 14px 16px;
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        .modal .btn {
            padding: 14px 20px;
            font-size: 16px;
        }
    }

    /* Modal Loading State */
    .modal-loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .modal-loading .modal-content {
        position: relative;
    }

    .modal-loading .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Enhanced Select Dropdown for Modals */
    .modal .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23374151' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 16px center !important;
        background-size: 16px 12px !important;
        padding-right: 48px;
    }

    .modal .form-select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    /* Form Controls for non-modal elements */
    .form-control:not(.modal .form-control), 
    .form-select:not(.modal .form-select) {
        border-radius: 6px;
        border: 1px solid var(--border-medium);
        background: var(--bg-card);
        font-size: 14px;
        font-weight: 400;
        padding: 12px 16px;
        transition: all 0.3s ease;
        color: var(--text-primary);
    }

    .form-control:not(.modal .form-control):focus, 
    .form-select:not(.modal .form-select):focus {
        border-color: var(--text-primary);
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        background: var(--bg-card);
    }

    /* Enhanced Select Dropdown for non-modal */
    .form-select:not(.modal .form-select) {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 16px 12px !important;
    }

    .form-select:not(.modal .form-select):focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23111827' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
    }

    /* Enhanced Alert Styling */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
        color: #1e40af;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fefce8 100%);
        color: #92400e;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%);
        color: #065f46;
    }

    /* Text color utilities for icons */
    .text-success {
        color: #10b981 !important;
    }

    .text-warning {
        color: #f59e0b !important;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    /* Scrollbar Styling */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: var(--bg-secondary);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--border-medium);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--text-secondary);
    }

    /* Responsive Design - Enhanced */
    @media (max-width: 768px) {
        .container.p-0 {
            margin: 16px 8px;
            padding: 24px 16px;
            border-radius: 16px;
        }

        .nav-tabs .nav-link {
            padding: 14px 10px;
            font-size: 12px;
            flex-direction: column;
            gap: 6px;
        }

        .nav-tabs .nav-link i {
            font-size: 14px;
        }

        th, td {
            padding: 16px 10px;
            font-size: 13px;
        }

        .card.card-body {
            padding: 20px;
            margin: 12px;
        }

        .btn {
            padding: 12px 18px;
            font-size: 13px;
        }

        .asset-info-grid {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .action-buttons {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }

        .info-item {
            padding: 14px;
        }

        .info-item .label {
            font-size: 11px;
        }

        .info-item .value {
            font-size: 15px;
        }

        .market-status-bar {
            padding: 14px 16px;
            flex-direction: column;
            gap: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .market-status-bar[style*="display: none"] {
            margin: 0;
            padding: 0;
            height: 0;
            overflow: hidden;
        }

        .dropdown-btn {
            padding: 8px 12px;
            font-size: 11px;
        }
    }

    @media (max-width: 480px) {
        .container.p-0 {
            margin: 8px 4px;
            padding: 20px 12px;
        }

        .nav-tabs {
            padding: 4px;
        }

        .nav-tabs .nav-link {
            padding: 12px 8px;
            font-size: 10px;
            flex-direction: column;
            gap: 4px;
        }

        .nav-tabs .nav-link i {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
            font-size: 12px;
        }

        .search input {
            padding: 16px 16px 16px 44px;
            font-size: 14px;
        }

        .search::before {
            left: 14px;
            font-size: 14px;
        }

        .asset-info-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 16px;
        }

        .action-buttons {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .info-item {
            padding: 12px;
        }

        .info-item .label {
            font-size: 10px;
        }

        .info-item .value {
            font-size: 14px;
        }

        .action-buttons .btn {
            padding: 12px 16px;
            font-size: 12px;
        }

        .market-status-bar {
            padding: 12px;
            transition: all 0.3s ease;
        }

        .market-status-bar[style*="display: none"] {
            margin: 0;
            padding: 0;
            height: 0;
            overflow: hidden;
        }

        .market-status {
            font-size: 13px;
        }

        .market-time {
            font-size: 12px;
        }

        .dropdown-btn {
            padding: 6px 10px;
            font-size: 10px;
            gap: 4px;
        }

        .asset-dropdown {
            padding: 12px;
        }

        .asset-dropdown-buttons {
            gap: 8px;
        }
    }

    /* Accessibility improvements */
    .btn:focus,
    .form-control:focus,
    .form-select:focus {
        outline: 2px solid var(--text-secondary);
        outline-offset: 2px;
    }
    
    /* Dark Theme Overrides for Quotes Page */
    [data-theme="dark"] {
        --bg-primary: #121624;
        --bg-secondary: #1C1F26;
        --bg-tertiary: #141927;
        --bg-card: #1C1F26;
        --text-primary: #FFFFFF;
        --text-secondary: #B3B3B3;
        --text-muted: #808080;
        --border-light: #2A2D35;
        --border-medium: #3A3D45;
        --accent-color: #FFD700;
        --gold-primary: #FFD700;
        --gold-secondary: #FFC107;
        --card-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    
    /* Dark theme button styling */
    [data-theme="dark"] .btn-success {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%) !important;
        border-color: var(--gold-primary) !important;
        color: #121624 !important;
        font-weight: 600;
    }
    
    [data-theme="dark"] .btn-success:hover {
        background: linear-gradient(135deg, var(--gold-secondary) 0%, #FFB300 100%) !important;
        border-color: var(--gold-secondary) !important;
        color: #121624 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.3);
    }
    
    [data-theme="dark"] .btn-danger {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%) !important;
        border-color: #EF4444 !important;
        color: #FFFFFF !important;
    }
    
    [data-theme="dark"] .btn-danger:hover {
        background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%) !important;
        border-color: #DC2626 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }
    
    [data-theme="dark"] .btn-primary {
        background: linear-gradient(135deg, var(--gold-primary) 0%, var(--gold-secondary) 100%) !important;
        border-color: var(--gold-primary) !important;
        color: #121624 !important;
        font-weight: 600;
    }
    
    [data-theme="dark"] .btn-primary:hover {
        background: linear-gradient(135deg, var(--gold-secondary) 0%, #FFB300 100%) !important;
        border-color: var(--gold-secondary) !important;
        color: #121624 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 215, 0, 0.3);
    }
    .btn-success.active{
        box-shadow: 0 0 0 .25rem rgba(23, 160, 14, 0.52);
    }
    .btn-danger.active{
        box-shadow: 0 0 0 .25rem rgba(225, 83, 97, .5);
    }
    /* Dark theme nav tabs */
    [data-theme="dark"] .nav-tabs {
        border-bottom-color: var(--border-light) !important;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link {
        background-color: var(--bg-tertiary) !important;
        color: var(--text-secondary) !important;
        border-color: var(--border-light) !important;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link.active {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
        border-color: var(--gold-primary) var(--gold-primary) var(--bg-card) !important;
        font-weight: 600;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link:hover {
        background-color: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-medium) !important;
    }
    
    /* Dark theme table styling */
    [data-theme="dark"] .table {
        --bs-table-bg: var(--bg-card);
        --bs-table-color: var(--text-primary);
        --bs-table-border-color: var(--border-light);
        --bs-table-striped-bg: var(--bg-tertiary);
        --bs-table-hover-bg: var(--bg-secondary);
    }
    
    [data-theme="dark"] .table th {
        background-color: var(--bg-tertiary) !important;
        color: var(--text-primary) !important;
        border-color: var(--border-light) !important;
    }
    
    [data-theme="dark"] .table td {
        background-color: var(--bg-card) !important;
        border-color: var(--border-light) !important;
    }
    
    [data-theme="dark"] .table tbody tr:hover td {
        background-color: var(--bg-secondary) !important;
    }
    
    /* Dark theme price colors */
    [data-theme="dark"] .bid_price {
        /*color: #EF4444 !important;*/
        border-color: rgba(239, 68, 68, 0.3) !important;
    }
    
    [data-theme="dark"] .ask_price {
        /* color: #10B981 !important; */
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    
    /* Dark theme market status */
    [data-theme="dark"] .market-status-bar {
        background-color: var(--bg-secondary) !important;
        border-bottom-color: var(--border-light) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark theme search input */
    [data-theme="dark"] .search input {
        background-color: var(--bg-tertiary) !important;
        border-color: var(--border-light) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .search input:focus {
        border-color: var(--gold-primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1) !important;
    }
    
    /* All search placeholders white in dark mode */
    [data-theme="dark"] .search input::placeholder,
    [data-theme="dark"] input[placeholder*="search"]::placeholder,
    [data-theme="dark"] input[placeholder*="Search"]::placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .search input::-webkit-input-placeholder,
    [data-theme="dark"] input[placeholder*="search"]::-webkit-input-placeholder,
    [data-theme="dark"] input[placeholder*="Search"]::-webkit-input-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .search input::-moz-placeholder,
    [data-theme="dark"] input[placeholder*="search"]::-moz-placeholder,
    [data-theme="dark"] input[placeholder*="Search"]::-moz-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .search input:-ms-input-placeholder,
    [data-theme="dark"] input[placeholder*="search"]:-ms-input-placeholder,
    [data-theme="dark"] input[placeholder*="Search"]:-ms-input-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }
    
    [data-theme="dark"] .search::before {
        color: var(--text-muted) !important;
    }
    
    /* Dark theme alerts */
    [data-theme="dark"] .alert-info {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%) !important;
        color: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
    }
    
    [data-theme="dark"] .alert-warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.05) 100%) !important;
        color: #F59E0B !important;
        border-color: #F59E0B !important;
    }
    
    [data-theme="dark"] .alert-success {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(52, 211, 153, 0.05) 100%) !important;
        color: #10B981 !important;
        border-color: #10B981 !important;
    }

    /* Dark theme star icons */
    [data-theme="dark"] .fa-star.text-dark,
    [data-theme="dark"] .star-icon.favorited {
        color: var(--gold-primary) !important;
    }

    [data-theme="dark"] .fa-star:hover {
        color: var(--gold-secondary) !important;
    }

    /* Modal form control placeholders in dark mode */
    [data-theme="dark"] .modal .form-control::placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .modal .form-control::-webkit-input-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .modal .form-control::-moz-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    [data-theme="dark"] .modal .form-control:-ms-input-placeholder {
        color: #FFFFFF !important;
        opacity: 1;
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }

    /* Loading Animation */
    .loading {
        opacity: 0.6;
    }

    /* Modal Design */
    .modal {
        backdrop-filter: blur(4px);
    }

    .modal-dialog {
        margin: 2rem auto;
        max-width: 90%;
    }

    .modal-content {
        border-radius: 8px;
        border: 1px solid var(--border-light);
        background: var(--bg-card);
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 20px 24px 16px;
        background: var(--bg-secondary);
    }

    .modal-title {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 18px;
    }

    .modal-body {
        padding: 24px;
        background: var(--bg-card);
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        opacity: 0.7;
        transition: all 0.2s ease;
        color: var(--text-secondary);
    }

    .btn-close:hover {
        opacity: 1;
        color: var(--text-primary);
    }

    /* Asset Dropdown Buttons */
    .asset-dropdown {
        padding: 16px;
        background: var(--bg-secondary);
        border-radius: 8px;
    }

    .asset-dropdown-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }

    .dropdown-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        background: var(--bg-card);
        color: var(--text-primary);
        text-decoration: none;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .dropdown-btn:hover {
        background: var(--text-primary);
        color: var(--bg-card);
        border-color: var(--text-primary);
    }

    .dropdown-btn i {
        font-size: 11px;
    }
</style>

@section('content')
<div class="container p-0">
    <div class="container-fluid px-3 py-2">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert">
                <small>{{ session('success') }}</small>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('fail'))
            <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert">
                <small>{{ session('fail') }}</small>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <!-- Market Status Indicator (Hidden for favorites tab, visible for others) -->
    <div class="market-status-bar" id="marketStatusBar" style="display: none;">
        <div class="market-status">
            <div class="status-indicator live" id="statusIndicator"></div>
            <span id="statusText">{{__('web.market_live')}}</span>
        </div>
        <div class="market-time" id="marketTime">
            <i class="fas fa-clock"></i>
            <span id="currentTime"></span>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3 w-100" id="quotesTabs" role="tablist" style="display: flex; justify-content: space-between;">
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') active @endif" id="fav-tab" data-bs-toggle="tab" data-bs-target="#fav" type="button" role="tab" aria-controls="fav" aria-selected="true">
                <i class="fas fa-star me-2"></i>{{__('web.favorites')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') active @endif" id="forex-tab" data-bs-toggle="tab" data-bs-target="#forex" type="button" role="tab" aria-controls="forex" aria-selected="false">
                <i class="fas fa-exchange-alt me-2"></i>{{__('web.forex')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') active @endif" id="cfd-tab" data-bs-toggle="tab" data-bs-target="#crypto" type="button" role="tab" aria-controls="crypto" aria-selected="false">
                <i class="fab fa-bitcoin me-2"></i>{{__('web.crypto')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') active @endif" id="ai-tab" data-bs-toggle="tab" data-bs-target="#stocks" type="button" role="tab" aria-controls="stocks" aria-selected="false">
                <i class="fas fa-chart-line me-2"></i>{{__('web.stocks')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center" style="margin-right: 2px">
            <button class="nav-link w-100 @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') active @endif" id="indices-tab" data-bs-toggle="tab" data-bs-target="#indices" type="button" role="tab" aria-controls="indices" aria-selected="false">
                <i class="fas fa-chart-bar me-2"></i>{{__('web.indices')}}
            </button>
        </li>
        <li class="nav-item flex-fill text-center">
            <button class="nav-link w-100 @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') active @endif" id="commodity-tab" data-bs-toggle="tab" data-bs-target="#commodity" type="button" role="tab" aria-controls="commodity" aria-selected="false">
                <i class="fas fa-seedling me-2"></i>{{__('web.commodity')}}
            </button>
        </li>
    </ul>
    <div class="tab-content" id="quotesTabsContent">
        <div class="tab-pane fade @if(($tab == 'fav' && !session('tab')) || session('tab') == 'fav') show active @endif" id="fav" role="tabpanel" aria-labelledby="fav-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_fav_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead class="table-header">
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="favAssets">
                        @foreach($favourite_assets as $index => $asset)
                            @if(is_object($asset))
                                <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                    <td style="text-align: left;">
                                        <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'fav'])}}" style="text-decoration: none;">
                                            <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                        </a>
                                        <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsFav{{ $asset->id }}">
                                            {{ $asset->name }}
                                        </span>
                                    </td>
                                    <td class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsFav{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                    <td class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsFav{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                                </tr>
                                <tr id="assetDetailsFav{{ $asset->id }}" class="collapse asset-details">
                                    <td colspan="3">
                                        <div class="asset-dropdown">
                                            <div class="asset-dropdown-buttons">
                                                <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                    <i class="fas fa-chart-line"></i>
                                                    {{__('web.new_chart')}}
                                                </a>
                                                @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                    <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'fav')">
                                                        <i class="fas fa-plus"></i>
                                                        {{__('web.new_order')}}
                                                    </button>
                                                    <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'fav')">
                                                        <i class="fas fa-clock"></i>
                                                        {{__('web.new_pending_order')}}
                                                    </button>
                                                @endif
                                                <button type="button" class="dropdown-btn" onclick="showFavDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                    <i class="fas fa-info-circle"></i>
                                                    {{__('web.trade_hours')}}
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade @if(($tab == 'forex' && !session('tab')) || session('tab') == 'forex') show active @endif" id="forex" role="tabpanel" aria-labelledby="forex-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_forex_assets')}}">
            <div class="table-responsive" style="max-height: 68%; width: 100%; overflow-y: auto;">
                <table class="table" style="width: 100%;">
                    <thead class="table-header">
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="forexAssets">
                        @foreach($forexAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'forex'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'forex')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsForex{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsForex{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsForex{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetailsForex{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'forex')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'forex')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showForexDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'crypto' && !session('tab')) || session('tab') == 'crypto') show active @endif" id="crypto" role="tabpanel" aria-labelledby="crypto-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_crypto_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="cryptoAssets">
                        @foreach($cryptoAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'crypto'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'crypto')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsCrypto{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsCrypto{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsCrypto{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetailsCrypto{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'crypto')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'crypto')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showCryptoDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'stocks' && !session('tab')) || session('tab') == 'stocks') show active @endif" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_stocks_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="stocksAssets">
                        @foreach($stocksAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'stocks'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'stocks')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsStocks{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsStocks{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsStocks{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetailsStocks{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'stocks')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'stocks')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showStocksDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'indices' && !session('tab')) || session('tab') == 'indices') show active @endif" id="indices" role="tabpanel" aria-labelledby="indices-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_indices_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="indicesAssets">
                        @foreach($indicesAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'indices'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'indices')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsIndices{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsIndices{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsIndices{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetailsIndices{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'indices')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'indices')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showIndicesDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="tab-pane fade @if(($tab == 'commodity' && !session('tab')) || session('tab') == 'commodity') show active @endif" id="commodity" role="tabpanel" aria-labelledby="commodity-tab">
            <input type="text" class="form-control mb-3 search" placeholder="{{__('web.search_commodity_assets')}}">
            <div class="table-responsive" style="max-height: 68%; overflow-y: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">{{__('web.instrument')}}</th>
                            <th class="text-center">{{__('web.sell')}}</th>
                            <th class="text-center">{{__('web.buy')}}</th>
                        </tr>
                    </thead>
                    <tbody id="commodityAssets">
                        @foreach($commodityAssets as $index => $asset)
                            <tr class="asset-row" data-asset-id="{{ $asset->id }}">
                                <td class="text-start" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <a href="{{route('toggle.favourite',['id' => $asset->id, 'tab' => 'commodity'])}}" style="text-decoration: none;" onclick="toggleFavorite(event, {{ $asset->id }}, 'commodity')">
                                        <i class="fas fa-star @if (in_array($asset->id, $favourite_assets_ids)) text-dark @else text-secondary @endif"></i>
                                    </a>
                                    <span class="name" data-bs-toggle="collapse" data-bs-target="#assetDetailsCommodity{{ $asset->id }}">
                                        {{ $asset->name }}
                                    </span>
                                </td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="bid_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsCommodity{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->bid_price), '0'), '.') }}</td>
                                <td @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif class="ask_price" data-asset-id="{{$asset->id}}" data-bs-toggle="collapse" data-bs-target="#assetDetailsCommodity{{ $asset->id }}">{{ rtrim(rtrim(sprintf('%f', $asset->ask_price), '0'), '.') }}</td>
                            </tr>
                            <tr id="assetDetailsCommodity{{ $asset->id }}" class="collapse asset-details">
                                <td colspan="3" @if ($index %2 == 0) style="background: rgba(0, 0, 0, 0.05);"@endif>
                                    <div class="asset-dropdown">
                                        <div class="asset-dropdown-buttons">
                                            <a href="{{route('clientarea.charts',['symbol' => $asset->symbol])}}" class="dropdown-btn">
                                                <i class="fas fa-chart-line"></i>
                                                {{__('web.new_chart')}}
                                            </a>
                                            @if(!isset(auth()->guard('client')->user()->options['cantOpen']))
                                                <button type="button" class="dropdown-btn" onclick="openNewOrderModal({{ $asset->id }}, 'commodity')">
                                                    <i class="fas fa-plus"></i>
                                                    {{__('web.new_order')}}
                                                </button>
                                                <button type="button" class="dropdown-btn" onclick="openPendingOrderModal({{ $asset->id }}, 'commodity')">
                                                    <i class="fas fa-clock"></i>
                                                    {{__('web.new_pending_order')}}
                                                </button>
                                            @endif
                                            <button type="button" class="dropdown-btn" onclick="showCommodityDetails('{{ $asset->symbol }}', {{ $asset->id }})">
                                                <i class="fas fa-info-circle"></i>
                                                {{__('web.trade_hours')}}
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@if(!isset(auth()->guard('client')->user()->options['cantOpen']))
    <!-- New Order Modal -->
    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newOrderModalLabel">
                        <i class="fas fa-chart-line me-2"></i>
                        {{__('web.new_order')}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form-grid">
                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tab" id="newTab">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="asset-select" class="form-label">
                                        <i class="fas fa-coins me-1"></i>
                                        {{__('web.item')}}
                                    </label>
                                    <select class="form-select" id="asset-select" name="currency">
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
                                
                                <div class="col-12">
                                    <label for="newAmount" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        {{__('web.lot_amount')}}
                                    </label>
                                    <input type="number" class="form-control" id="newAmount" name="amount" min="0.01" step="any" value="0.01" placeholder="0.01">
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="stopLossSwitch">
                                        <label class="form-check-label" for="stopLossSwitch">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            {{__('web.set_stop_loss')}}
                                        </label>
                                    </div>
                                    <div id="stopLossContainer" class="mt-3" style="display: none;">
                                        <input type="number" class="form-control" id="stopLossInput" step="any" name="s_l" placeholder="{{__('web.stop_loss_price')}}">
                                        <label class="text-danger d-block" id="newExpectedLossError"></label>
                                        <label class="text-danger">
                                            {{__('web.estimated_loss')}}
                                            <strong id="expectedLoss">0</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="takeProfitSwitch">
                                        <label class="form-check-label" for="takeProfitSwitch">
                                            <i class="fas fa-target me-2"></i>
                                            {{__('web.set_take_profit')}}
                                        </label>
                                    </div>
                                    <div id="takeProfitContainer" class="mt-3" style="display: none;">
                                        <input type="number" class="form-control" id="takeProfitInput" step="any" name="s_p" placeholder="{{__('web.take_profit_price')}}">
                                        <label class="text-danger d-block" id="newExpectedProfitError"></label>
                                        <label class="text-success">
                                            {{__('web.estimated_profit')}}
                                            <strong id="expectedProfit">0</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-group-modern">
                                <label>
                                    {{__('web.required_margin')}}
                                    <strong id="required_margin">-</strong>
                                </label>
                            </div>

                            <div class="btn-group-modern">
                                <button type="button" class="btn btn-success active" id="buy-btn">
                                    <i class="fas fa-arrow-up me-2"></i>
                                    {{__('web.buy')}} <strong id="buy-price">0</strong>
                                </button>
                                <button type="button" class="btn btn-danger" id="sell-btn">
                                    <i class="fas fa-arrow-down me-2"></i>
                                    {{__('web.sell')}} <strong id="sell-price">0</strong>
                                </button>
                            </div>

                            <div class="btn-group-modern">
                                <button type="submit" class="btn btn-secondary">
                                    {{__('web.submit')}}
                                </button>
                            </div>
                            
                            <input type="hidden" class="form-control" name="bid" id="bid" value="0" readonly>
                            <input type="hidden" class="form-control" name="ask" id="ask" value="0" readonly>
                            <input type="hidden" class="form-control" name="type" id="type-input" value="1" readonly>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Order Modal -->
    <div class="modal fade" id="newPendingOrderModal" tabindex="-1" aria-labelledby="newPendingOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPendingOrderModalLabel">
                        <i class="fas fa-clock me-2"></i>
                        {{__('web.new_pending_order')}}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-form-grid">
                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="currency" id="currency">
                            <input type="hidden" name="tab" id="pendingTab">
                            
                            <div class="row g-4">
                                <div class="col-12">
                                    <label for="orderType" class="form-label">
                                        <i class="fas fa-list-alt me-1"></i>
                                        {{__('web.order_type')}}
                                    </label>
                                    <select class="form-select" id="orderType" name="status">
                                        <option value="buy_limit">
                                            🟢 {{__('web.buy_limit')}}
                                        </option>
                                        <option value="sell_limit">
                                            🔴 {{__('web.sell_limit')}}
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="col-12">
                                    <label for="orderOpenAt" class="form-label">
                                        <i class="fas fa-tag me-1"></i>
                                        {{__('web.value')}}
                                    </label>
                                    <input type="number" class="form-control" id="orderOpenAt" step="any" name="open_at_price" placeholder="{{__('web.target_price')}}">
                                </div>
                                
                                <div class="col-12">
                                    <label for="orderAmount" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        {{__('web.amount')}}
                                    </label>
                                    <input type="number" class="form-control" id="orderAmount" min="0.01" name="amount" step="any" value="0.01" placeholder="0.01">
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="stopLossSwitchPending">
                                        <label class="form-check-label" for="stopLossSwitchPending">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            {{__('web.set_stop_loss')}}
                                        </label>
                                    </div>
                                    <div id="stopLossContainerPending" class="mt-3" style="display: none;">
                                        <input type="number" class="form-control" step="any" name="s_l" placeholder="{{__('web.stop_loss_price')}}">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input me-3" type="checkbox" id="takeProfitSwitchPending">
                                        <label class="form-check-label" for="takeProfitSwitchPending">
                                            <i class="fas fa-target me-2"></i>
                                            {{__('web.set_take_profit')}}
                                        </label>
                                    </div>
                                    <div id="takeProfitContainerPending" class="mt-3" style="display: none;">
                                        <input type="number" class="form-control" step="any" name="s_p" placeholder="{{__('web.take_profit_price')}}">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        {{ __('web.place_pending_order') }}
                                    </button>
                                </div>
                            </div>
                            
                            <input type="hidden" class="form-control" name="bid" value="0" readonly>
                            <input type="hidden" class="form-control" name="ask" value="0" readonly>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Trade Hours Modal Forex -->
<div class="modal fade" id="tradeHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>
                    {{__('web.trade_hours')}} - Forex
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Market Status Card -->
                <div class="alert alert-success mb-4" style="background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%); border: none; border-radius: 16px; padding: 20px;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-clock text-white fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-success">24/5 Trading Available</h6>
                            <p class="mb-0 text-success">Forex markets are open 24 hours a day, 5 days a week</p>
                        </div>
                    </div>
                </div>

                <!-- Trading Schedule -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="fas fa-calendar-week me-2 text-success"></i>
                                    Weekdays (Active)
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="trading-day mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>{{__('web.monday')}}</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">00:00 - 23:59</small>
                                    </div>
                                </div>
                                <div class="trading-day mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>{{__('web.tuesday')}}</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">00:00 - 23:59</small>
                                    </div>
                                </div>
                                <div class="trading-day mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>{{__('web.wednesday')}}</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">00:00 - 23:59</small>
                                    </div>
                                </div>
                                <div class="trading-day mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>{{__('web.thursday')}}</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">00:00 - 23:59</small>
                                    </div>
                                </div>
                                <div class="trading-day">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>{{__('web.friday')}}</span>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">00:00 - 23:59</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="fas fa-calendar-times me-2 text-danger"></i>
                                    Weekend (Closed)
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="trading-day mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>{{__('web.saturday')}}</span>
                                        <span class="badge bg-danger">{{__('web.closed')}}</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">Market Closed</small>
                                    </div>
                                </div>
                                <div class="trading-day">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-medium"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>{{__('web.sunday')}}</span>
                                        <span class="badge bg-danger">{{__('web.closed')}}</span>
                                    </div>
                                    <div class="time-range">
                                        <small class="text-muted">Market Closed</small>
                                    </div>
                                </div>
                                
                                <!-- Additional Info -->
                                <div class="mt-4 p-3" style="background: var(--bg-secondary); border-radius: 8px;">
                                    <h6 class="mb-2 fw-bold text-primary">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Trading Tips
                                    </h6>
                                    <ul class="mb-0 small text-muted">
                                        <li>Best trading hours: London-NY overlap</li>
                                        <li>High volatility: Major news releases</li>
                                        <li>Lower spreads during peak hours</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timezone Info -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-globe me-1"></i>
                        All times shown are in your local timezone (GMT+0)
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Crypto -->
<div class="modal fade" id="CryptoHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">
                    <i class="fab fa-bitcoin me-2"></i>
                    {{__('web.trade_hours')}} - Cryptocurrency
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Market Status Card -->
                <div class="alert alert-warning mb-4" style="background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%); border: none; border-radius: 16px; padding: 20px;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fab fa-bitcoin text-white fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-warning">24/7 Trading Available</h6>
                            <p class="mb-0 text-warning">Cryptocurrency markets never close - trade anytime, anywhere</p>
                        </div>
                    </div>
                </div>

                <!-- 24/7 Visual Indicator -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning" style="width: 120px; height: 120px;">
                        <div class="text-center text-white">
                            <div class="fs-1 fw-bold">24/7</div>
                            <small class="text-uppercase">Always Open</small>
                        </div>
                    </div>
                </div>

                <!-- Daily Schedule -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="fas fa-calendar-week me-2 text-warning"></i>
                                    Every Day Active
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="trading-day mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.monday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                <div class="trading-day mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.tuesday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                <div class="trading-day mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.wednesday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                <div class="trading-day">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.thursday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="fas fa-calendar-week me-2 text-warning"></i>
                                    Weekend Too!
                                </h6>
                            </div>
                            <div class="card-body pt-2">
                                <div class="trading-day mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.friday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                <div class="trading-day mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.saturday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                <div class="trading-day">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-medium"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>{{__('web.sunday')}}</span>
                                        <span class="badge bg-warning">24h</span>
                                    </div>
                                </div>
                                
                                <!-- Crypto Trading Features -->
                                <div class="mt-4 p-3" style="background: var(--bg-secondary); border-radius: 8px;">
                                    <h6 class="mb-2 fw-bold text-warning">
                                        <i class="fas fa-rocket me-2"></i>
                                        Crypto Advantages
                                    </h6>
                                    <ul class="mb-0 small text-muted">
                                        <li>No market closing hours</li>
                                        <li>High volatility opportunities</li>
                                        <li>Global decentralized trading</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Trading Times -->
                <div class="mt-4 p-3" style="background: linear-gradient(135deg, #fef3c7 0%, #fef9e7 100%); border-radius: 12px;">
                    <h6 class="mb-3 fw-bold text-warning">
                        <i class="fas fa-chart-line me-2"></i>
                        Popular Trading Times (High Volume)
                    </h6>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="text-center">
                                <div class="fw-bold">08:00 - 12:00</div>
                                <small class="text-muted">Asian Session</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-center">
                                <div class="fw-bold">14:00 - 18:00</div>
                                <small class="text-muted">European Session</small>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-center">
                                <div class="fw-bold">20:00 - 24:00</div>
                                <small class="text-muted">American Session</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timezone Info -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-globe me-1"></i>
                        Times shown are in your local timezone - Crypto markets never sleep!
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Stocks -->
<div class="modal fade" id="StocksHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">
                    <i class="fas fa-chart-line me-2"></i>
                    {{__('web.trade_hours')}} - Stock Markets
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Market Overview -->
                <div class="alert alert-info mb-4" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border: none; border-radius: 16px; padding: 20px;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas fa-globe text-white fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-primary">Global Stock Markets</h6>
                            <p class="mb-0 text-primary">Trading hours vary by region - choose your market wisely</p>
                        </div>
                    </div>
                </div>

                <!-- Markets Grid -->
                <div class="row g-4">
                    <!-- American Market -->
                    <div class="col-lg-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">🇺🇸</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-0 fw-bold">{{__('web.american_market')}}</h6>
                                        <small class="text-muted">NYSE, NASDAQ</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row g-2">
                                    <div class="col-4"><small class="text-muted fw-bold">Day</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Open</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Close</small></div>
                                </div>
                                <hr class="my-2">
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.monday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.tuesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.wednesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.thursday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.friday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.saturday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.sunday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- British Market -->
                    <div class="col-lg-6">
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.wednesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.thursday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.friday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">1:30 PM</small></div>
                                    <div class="col-4"><small class="fw-medium">8:00 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.saturday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.sunday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- British Market -->
                    <div class="col-lg-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.wednesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">11:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">7:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.thursday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">11:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">7:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.friday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">11:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">7:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.saturday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.sunday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- German Market -->
                    <div class="col-lg-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">🇩🇪</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-0 fw-bold">{{__('web.german_market')}}</h6>
                                        <small class="text-muted">XETRA, Frankfurt</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row g-2">
                                    <div class="col-4"><small class="text-muted fw-bold">Day</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Open</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Close</small></div>
                                </div>
                                <hr class="my-2">
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.monday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">10:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">6:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.tuesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">10:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">6:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.wednesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">10:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">6:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.thursday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">10:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">6:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.friday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">10:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">6:30 PM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.saturday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.sunday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Japanese Market -->
                    <div class="col-lg-6">
                        <div class="card h-100" style="border: 1px solid var(--border-light); border-radius: 12px; background: var(--bg-card);">
                            <div class="card-header bg-transparent border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">🇯🇵</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-0 fw-bold">{{__('web.japanese_market')}}</h6>
                                        <small class="text-muted">TSE, Nikkei</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div class="row g-2">
                                    <div class="col-4"><small class="text-muted fw-bold">Day</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Open</small></div>
                                    <div class="col-4"><small class="text-muted fw-bold">Close</small></div>
                                </div>
                                <hr class="my-2">
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.monday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">3:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">9:00 AM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.tuesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">3:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">9:00 AM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.wednesday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">3:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">9:00 AM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.thursday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">3:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">9:00 AM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>{{__('web.friday')}}</small></div>
                                    <div class="col-4"><small class="fw-medium">3:00 AM</small></div>
                                    <div class="col-4"><small class="fw-medium">9:00 AM</small></div>
                                </div>
                                <div class="row g-2 mb-1">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.saturday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4"><small><i class="fas fa-circle text-danger me-1" style="font-size: 6px;"></i>{{__('web.sunday')}}</small></div>
                                    <div class="col-8"><small class="text-danger fw-medium">{{__('web.closed')}}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trading Tips -->
                <div class="mt-4 p-3" style="background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); border-radius: 12px;">
                    <h6 class="mb-3 fw-bold text-primary">
                        <i class="fas fa-lightbulb me-2"></i>
                        Stock Trading Tips
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-2">🌅</div>
                                <div class="fw-bold small">Market Opening</div>
                                <small class="text-muted">Higher volatility at open</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-2">📊</div>
                                <div class="fw-bold small">Earnings Season</div>
                                <small class="text-muted">Increased price movements</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="mb-2">🌙</div>
                                <div class="fw-bold small">Market Closing</div>
                                <small class="text-muted">Last minute trades</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timezone Info -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-globe me-1"></i>
                        All times shown are in your local timezone
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Trade Hours Modal Indices -->
<div class="modal fade" id="IndicesHoursModal" tabindex="-1" aria-labelledby="tradeHoursModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeHoursModalLabel">{{__('web.trade_hours')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-center">Dow Jones</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">Nasdaq</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>1:30 PM</td><td>8:00 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">Nikkei</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>   
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>12:00 AM</td><td>6:00 AM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>

                <h6 class="text-center">FTSE 100</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{__('web.day_of_week')}}</th>
                            <th class="text-center">{{__('web.open_time')}}</th>
                            <th class="text-center">{{__('web.close_time')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>{{__('web.monday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.tuesday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.wednesday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.thursday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.friday')}}</td><td>8:00 AM</td><td>4:30 PM</td></tr>
                        <tr><td>{{__('web.saturday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                        <tr><td>{{__('web.sunday')}}</td><td>12:00 AM</td><td>12:00 AM</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to open new order modal
    window.openNewOrderModal = function(assetId, tab) {
        // Set the tab value
        document.getElementById('newTab').value = tab;
        
        // Set the selected asset in the dropdown
        const assetSelect = document.getElementById('asset-select');
        assetSelect.value = assetId;

        stopLossInput.value = '';
        takeProfitInput.value = '';
        newAmount.value = 0;
        stopLossSwitch.checked = false;
        takeProfitSwitch.checked = false;
        stopLossContainer.style.display = 'none';
        takeProfitContainer.style.display = 'none';

        // Update prices based on selected asset
        updatePrices();
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('newOrderModal'));
        modal.show();
    };

    // Function to open pending order modal
    window.openPendingOrderModal = function(assetId, tab) {
        // Set the tab value
        document.getElementById('pendingTab').value = tab;
        
        // Set the currency (asset ID)
        document.getElementById('currency').value = assetId;
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('newPendingOrderModal'));
        modal.show();
    };

    // Function to show Forex trade hours
    window.showForexDetails = function(symbol, assetId) {
        const modal = new bootstrap.Modal(document.getElementById('tradeHoursModal'));
        modal.show();
    };

    // Function to show Crypto trade hours
    window.showCryptoDetails = function(symbol, assetId) {
        const modal = new bootstrap.Modal(document.getElementById('CryptoHoursModal'));
        modal.show();
    };

    // Function to show Stocks trade hours
    window.showStocksDetails = function(symbol, assetId) {
        const modal = new bootstrap.Modal(document.getElementById('StocksHoursModal'));
        modal.show();
    };

    // Function to show Indices trade hours
    window.showIndicesDetails = function(symbol, assetId) {
        const modal = new bootstrap.Modal(document.getElementById('IndicesHoursModal'));
        modal.show();
    };

    // Function to show Commodity trade hours (uses Forex modal for now)
    window.showCommodityDetails = function(symbol, assetId) {
        const modal = new bootstrap.Modal(document.getElementById('tradeHoursModal'));
        modal.show();
    };

    // Function to show Favorites trade hours (determines modal based on asset type)
    window.showFavDetails = function(symbol, assetId) {
        // For favorites, we'll use the general Forex modal
        // You can enhance this to detect asset type and show appropriate modal
        const modal = new bootstrap.Modal(document.getElementById('tradeHoursModal'));
        modal.show();
    };

    const stopLossInput = document.getElementById('stopLossInput');
    const takeProfitInput = document.getElementById('takeProfitInput');
    const buyBtn = document.getElementById('buy-btn');
    const sellBtn = document.getElementById('sell-btn');
    const typeInput = document.getElementById('type-input');
    const newAmount = document.getElementById('newAmount');
    const requiredMargin = document.getElementById('required_margin');
    const expectedLossError = document.getElementById('newExpectedLossError');
    const expectedProfitError = document.getElementById('newExpectedProfitError');
    const clientId = {{auth()->guard('client')->user()->id}};

    function calcExpectedLoss() {
        let openPrice;

        if(!document.getElementById('newAmount').value || !stopLossInput.value){
            expectedLoss.textContent = parseFloat(0).toFixed(4);
            return;
        }
        let assetId = document.getElementById('asset-select').value;

        
        if(typeInput.value == 1){
            openPrice = document.getElementById('ask').value;
            //let loss = (openPrice - stopLossInput.value) / document.getElementById('newAmount').value;
            //loss = Math.abs(loss);
            //expectedLoss.textContent = parseFloat(loss).toFixed(4);
        }else{
            openPrice = document.getElementById('bid').value;
            //let loss = (stopLossInput.value - openPrice) / document.getElementById('newAmount').value;
            //loss = Math.abs(loss);
            //expectedLoss.textContent = parseFloat(loss).toFixed(4);
        }

        $.ajax({
            //url: `{{config('services.crm_api.url')}}/api/calculatePnlWithoutOrder/${clientId}/${assetId}/${typeInput.value}/${openPrice}/${stopLossInput.value}/${document.getElementById('newAmount').value}`,
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
                expectedLoss.textContent = - parseFloat(loss).toFixed(4);
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
            //let profit = (takeProfitInput.value - openPrice) * document.getElementById('newAmount').value;
            //profit = Math.abs(profit);
            //expectedProfit.textContent = parseFloat(profit).toFixed(4);
        }else{
            openPrice = document.getElementById('bid').value;
            //let profit = (openPrice - takeProfitInput.value) * document.getElementById('newAmount').value;
            //profit = Math.abs(profit);
            //expectedProfit.textContent = parseFloat(profit).toFixed(4);
        }

        $.ajax({
            //url: `{{config('services.crm_api.url')}}/api/calculatePnlWithoutOrder/${clientId}/${assetId}/${typeInput.value}/${openPrice}/${stopLossInput.value}/${document.getElementById('newAmount').value}`,
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

    // Function to update prices in the new order modal
    function updatePrices() {
        const assetSelect = document.getElementById('asset-select');
        const selectedOption = assetSelect.options[assetSelect.selectedIndex];
        
        if (selectedOption) {
            const bidPrice = selectedOption.getAttribute('data-bid');
            const askPrice = selectedOption.getAttribute('data-ask');
            
            // Update display prices
            document.getElementById('sell-price').textContent = bidPrice;
            document.getElementById('buy-price').textContent = askPrice;
            
            // Update hidden inputs
            document.getElementById('bid').value = bidPrice;
            document.getElementById('ask').value = askPrice;

            calcExpectedLoss();
            calcExpectedProfit();
            getRequiredMarginFromApi();
        }
    }

    // Update prices when asset selection changes
    const assetSelect = document.getElementById('asset-select');
    if (assetSelect) {
        assetSelect.addEventListener('change', updatePrices);
        // Initialize prices on page load
        updatePrices();
    }

    // Handle stop loss and take profit toggles for new order modal
    const stopLossSwitch = document.getElementById('stopLossSwitch');
    const stopLossContainer = document.getElementById('stopLossContainer');
    const takeProfitSwitch = document.getElementById('takeProfitSwitch');
    const takeProfitContainer = document.getElementById('takeProfitContainer');

    if (stopLossSwitch) {
        stopLossSwitch.addEventListener('change', function() {
            stopLossContainer.style.display = this.checked ? 'block' : 'none';
            stopLossInput.value = '';
            expectedLoss.textContent = 0;
            expectedLossError.textContent = '';
        });
    }

    if (takeProfitSwitch) {
        takeProfitSwitch.addEventListener('change', function() {
            takeProfitContainer.style.display = this.checked ? 'block' : 'none';
            takeProfitInput.value = '';
            expectedProfit.textContent = 0;
            expectedProfitError.textContent = '';
        });
    }

    if (stopLossInput) {
        stopLossInput.addEventListener('change', function(e) {
            const ask = parseFloat(document.getElementById('ask').value);
            const bid = parseFloat(document.getElementById('bid').value);
            const val = parseFloat(this.value);

            if(typeInput.value == 1){
                if(val >= ask){
                    this.value = '';
                }
            }else{
                if(val <= bid){
                    this.value = '';
                }
            }

            calcExpectedLoss()
        });
        stopLossInput.addEventListener('input', function(e) {
            const ask = parseFloat(document.getElementById('ask').value);
            const bid = parseFloat(document.getElementById('bid').value);
            const val = parseFloat(this.value);

            if(typeInput.value == 1){
                if(val >= ask){
                    expectedLossError.textContent = "{{__('web.stop_loss_should_be_less_than_bid')}}";
                    return;
                }
            }else{
                if(val <= bid){
                    expectedLossError.textContent = "{{__('web.stop_loss_should_be_more_than_ask')}}";
                    return;
                }
            }
            expectedLossError.textContent = '';
        });
    }

    if (takeProfitInput) {
        takeProfitInput.addEventListener('change', function() {
            const ask = parseFloat(document.getElementById('ask').value);
            const bid = parseFloat(document.getElementById('bid').value);
            const val = parseFloat(this.value);
            if(typeInput.value == 1){
                if(val <= ask){
                    this.value = '';
                }
            }else{
                if(val >= bid){
                    this.value = '';
                }
            }     
            calcExpectedProfit()
        });
        takeProfitInput.addEventListener('input', function() {
            const ask = parseFloat(document.getElementById('ask').value);
            const bid = parseFloat(document.getElementById('bid').value);
            const val = parseFloat(this.value);
            if(typeInput.value == 1){
                if(val <= ask){
                    expectedProfitError.textContent = "{{__('web.take_profit_should_be_more_than_ask')}}";
                    return;
                }
            }else{
                if(val >= bid){
                    expectedProfitError.textContent = "{{__('web.take_profit_should_be_less_than_bid')}}";
                    return;
                }
            }
            expectedProfitError.textContent = '';
        });
    }

    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === "attributes" && mutation.attributeName === "value") {
                calcExpectedLoss()
                calcExpectedProfit()
            }
        });
    });

    observer.observe(document.getElementById('bid'), {
        attributes: true // Listen for attribute changes
    });


    if (buyBtn) {
        buyBtn.addEventListener('click', function() {
            typeInput.value = 1;
            const event = new Event('change', { bubbles: true });
            typeInput.dispatchEvent(event);

            sellBtn.classList.remove('active');
            this.classList.add('active');
            takeProfitInput.value = '';
            stopLossInput.value = '';

        });
    }
    
    if (sellBtn) {
        sellBtn.addEventListener('click', function() {
            typeInput.value = 2;
            const event = new Event('change', { bubbles: true });
            typeInput.dispatchEvent(event);

            buyBtn.classList.remove('active');
            this.classList.add('active');  
            takeProfitInput.value = '';
            stopLossInput.value = '';
        });
    }

    if (typeInput) {
        typeInput.addEventListener('change', function() {
            calcExpectedLoss();
            calcExpectedProfit();
        });
    }
    
    if (typeInput) {
        newAmount.addEventListener('change', function() {
            calcExpectedLoss();
            calcExpectedProfit();
            getRequiredMarginFromApi();
        });
    }
    
    function getRequiredMarginFromApi() {
        const assetSelect = document.getElementById('asset-select');

        const assetId = assetSelect.value;
        const amount = newAmount.value;
        const open_price = typeInput.value == 1 ? document.getElementById('ask').value : document.getElementById('bid').value;
        const assetGroupId = {{auth()->guard('client')->user()->asset_group_id}};
        if(!assetId || !amount || !open_price){
            return;
        }

        $.ajax({
            url: `{{config('services.crm_api.url')}}/api/getRequiredMargin/${assetId}?amount=${amount}&open_price=${open_price}&asset_group_id=${assetGroupId}`,
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

    // Handle stop loss and take profit toggles for pending order modal
    const stopLossSwitchPending = document.getElementById('stopLossSwitchPending');
    const stopLossContainerPending = document.getElementById('stopLossContainerPending');
    const takeProfitSwitchPending = document.getElementById('takeProfitSwitchPending');
    const takeProfitContainerPending = document.getElementById('takeProfitContainerPending');

    if (stopLossSwitchPending) {
        stopLossSwitchPending.addEventListener('change', function() {
            stopLossContainerPending.style.display = this.checked ? 'block' : 'none';
        });
    }

    if (takeProfitSwitchPending) {
        takeProfitSwitchPending.addEventListener('change', function() {
            takeProfitContainerPending.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Function to toggle favorite (if needed)
    window.toggleFavorite = function(event, assetId, tab) {
        event.preventDefault();
        // This function is called by some star buttons - you can customize behavior here
        window.location.href = event.currentTarget.href;
    };

    // Search functionality for asset tables
    const searchInputs = document.querySelectorAll('.search input');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.tab-pane').querySelector('table tbody');
            const rows = table.querySelectorAll('tr.asset-row');
            
            rows.forEach(row => {
                const assetName = row.querySelector('.name').textContent.toLowerCase();
                const shouldShow = assetName.includes(searchTerm);
                
                // Toggle both the asset row and its details row
                row.style.display = shouldShow ? '' : 'none';
                const detailsRow = row.nextElementSibling;
                if (detailsRow && detailsRow.classList.contains('asset-details')) {
                    detailsRow.style.display = shouldShow ? '' : 'none';
                }
            });
        });
    });

    // Market status and time management system
    function updateMarketStatusAndTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString();
        const currentTimeElement = document.getElementById('currentTime');
        
        if (currentTimeElement) {
            currentTimeElement.textContent = timeString;
        }

        // Get active tab
        const activeTab = document.querySelector('.nav-tabs .nav-link.active');
        const activeTabId = activeTab ? activeTab.getAttribute('aria-controls') : 'fav';
        
        // Update market status based on active tab
        updateMarketStatus(activeTabId, now);
    }

    function updateMarketStatus(tabId, now) {
        const statusBar = document.getElementById('marketStatusBar');
        const statusIndicator = document.getElementById('statusIndicator');
        const statusText = document.getElementById('statusText');
        
        if (!statusBar || !statusIndicator || !statusText) return;

        // Hide status bar for favorites tab
        if (tabId === 'fav') {
            statusBar.style.display = 'none';
            return;
        }

        // Show status bar for other tabs
        statusBar.style.display = 'flex';

        // Determine market status based on tab type
        const isMarketOpen = isMarketOpenForTab(tabId, now);
        const marketInfo = getMarketInfo(tabId);

        // Update status indicator and text
        if (isMarketOpen) {
            statusIndicator.className = 'status-indicator live';
            statusText.textContent = marketInfo.openText;
        } else {
            statusIndicator.className = 'status-indicator closed';
            statusText.textContent = marketInfo.closedText;
        }
    }

    function isMarketOpenForTab(tabId, now) {
        const day = now.getDay(); // 0 = Sunday, 6 = Saturday
        const hour = now.getHours();
        const minute = now.getMinutes();
        const timeInMinutes = hour * 60 + minute;

        switch (tabId) {
            case 'forex':
                // Forex: 24/5 - Closed Saturday 22:00 to Sunday 22:00 (GMT)
                if (day === 6 && hour >= 22) return false; // Saturday 22:00+
                if (day === 0 && hour < 22) return false;  // Sunday before 22:00
                return true;

            case 'crypto':
                // Crypto: 24/7
                return true;

            case 'stocks':
                // US Stocks: Monday-Friday 9:30-16:00 EST
                if (day === 0 || day === 6) return false; // Weekend
                // Simplified: 9:30-16:00 (930 minutes to 960 minutes from midnight)
                return timeInMinutes >= 570 && timeInMinutes < 960; // 9:30 AM to 4:00 PM

            case 'indices':
                // Indices follow stock market hours generally
                if (day === 0 || day === 6) return false; // Weekend
                return timeInMinutes >= 570 && timeInMinutes < 960; // 9:30 AM to 4:00 PM

            case 'commodity':
                // Commodities: Similar to forex but with some variations
                if (day === 0 || day === 6) return false; // Weekend for simplicity
                return timeInMinutes >= 360 && timeInMinutes < 1200; // 6:00 AM to 8:00 PM

            default:
                return false;
        }
    }

    function getMarketInfo(tabId) {
        const marketTexts = {
            forex: {
                openText: '{{__("web.market_live")}} - Forex 24/5',
                closedText: '{{__("web.market_closed")}} - Forex'
            },
            crypto: {
                openText: '{{__("web.market_live")}} - Crypto 24/7',
                closedText: '{{__("web.market_live")}} - Crypto 24/7' // Crypto never closes
            },
            stocks: {
                openText: '{{__("web.market_live")}} - Stocks',
                closedText: '{{__("web.market_closed")}} - Stocks'
            },
            indices: {
                openText: '{{__("web.market_live")}} - Indices',
                closedText: '{{__("web.market_closed")}} - Indices'
            },
            commodity: {
                openText: '{{__("web.market_live")}} - Commodities',
                closedText: '{{__("web.market_closed")}} - Commodities'
            }
        };

        return marketTexts[tabId] || {
            openText: '{{__("web.market_live")}}',
            closedText: '{{__("web.market_closed")}}'
        };
    }

    // Tab change event listeners
    const tabButtons = document.querySelectorAll('.nav-tabs .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Small delay to ensure tab switch completes
            setTimeout(() => {
                updateMarketStatusAndTime();
            }, 100);
        });
    });

    // Initialize market status on page load
    // Check for default active tab
    setTimeout(() => {
        updateMarketStatusAndTime();
    }, 500);

    // Update every second
    setInterval(updateMarketStatusAndTime, 1000);

    // Also update when page becomes visible (in case user switched tabs in browser)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateMarketStatusAndTime();
        }
    });
});
</script>

@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebTrader Mobile')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
    <link href="{{ url('assets/plugins/select2/css/select2.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/select2/css/select2-bootstrap4.min.css?v1.0') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="{{ url('assets/images/favicon-32x32.png') }}" type="image/png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ url('assets/css/icons.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('assets/css/bootstrap.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('assets/plugins/metismenu/css/metisMenu.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css?v1.0') }}" rel="stylesheet" />
    <link href="{{ url('assets/css/app.min.css?v1.0') }}" rel="stylesheet">
    <link href="{{ url('css/mobile-style.css?v1.0') }}" rel="stylesheet">
    <script src="{{ url('assets/js/new.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/metismenu/js/metisMenu.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/scrollbar.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js?v1.599') }}"></script>
    <script src="{{ url('assets/js/form-select2.min.js?v1.599') }}"></script>
    
    <!-- Theme Initialization Script - Must be in head to prevent FOUC -->
    <script>
        (function() {
            // Get theme from localStorage immediately - default to dark
            const savedTheme = localStorage.getItem('theme') || 'dark';
            // Apply theme to html element before page renders
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

<style>
    .btn {
        font-size: 11px;
        padding: 6px 10px;
    }
    
    /* Theme Switcher Styles */
    .theme-switcher {
        position: relative;
        background: transparent;
        border: none;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.3s ease;
        color: #424242;
    }
    
    .theme-switcher:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: scale(1.05);
    }
    
    .theme-switcher .iconify {
        font-size: 22px;
        transition: all 0.3s ease;
    }
    
    /* Dark Theme Variables and Styles */
    [data-theme="dark"] {
        --bg-primary: #121624;
        --bg-secondary: #1C1F26;
        --bg-tertiary: #141927;
        --bg-card: #1C1F26;
        --text-primary: #FFFFFF;
        --text-secondary: #B3B3B3;
        --text-muted: #808080;
        --border-color: #2A2D35;
        --accent-color: #FFD700;
        --success-color: #10B981;
        --danger-color: #EF4444;
        --warning-color: #F59E0B;
        --shadow: 0 4px 20px rgba(0,0,0,0.4);
        --gold-primary: #FFD700;
        --gold-secondary: #FFC107;
        --gold-tertiary: #FFEB3B;
    }
    
    [data-theme="light"] {
        --bg-primary: #FAFAFA;
        --bg-secondary: #FFFFFF;
        --bg-tertiary: #F5F5F5;
        --bg-card: #FFFFFF;
        --text-primary: #212121;
        --text-secondary: #424242;
        --text-muted: #757575;
        --border-color: #E5E5E5;
        --accent-color: #424242;
        --success-color: #4CAF50;
        --danger-color: #F44336;
        --warning-color: #FF9800;
        --shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    /* Dark Mode Topbar */
    [data-theme="dark"] .topbar {
        background-color: var(--bg-secondary) !important;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    
    [data-theme="dark"] .topbar .btn {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .topbar .btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    [data-theme="dark"] .hamburger-btn {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .hamburger-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .theme-switcher {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .theme-switcher:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    /* Dark Mode Dropdown */
    [data-theme="dark"] .topbar .dropdown-menu {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
    }
    
    [data-theme="dark"] .topbar .dropdown-item {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .topbar .dropdown-item:hover {
        background: var(--bg-tertiary);
    }
    
    [data-theme="dark"] .dropdown-menu .dropdown-item:hover {
        background: var(--bg-tertiary) !important;
        border-color: var(--border-color) !important;
    }
    
    /* Dark Mode Notifications */
    [data-theme="dark"] .dropdown-header {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
    }
    
    [data-theme="dark"] .notification-item {
        background-color: var(--bg-card) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .notification-item:hover {
        background-color: var(--bg-tertiary) !important;
    }
    
    [data-theme="dark"] .notification-dot {
        background-color: var(--gold-primary) !important;
    }
    
    /* Dark Mode Bottom Navigation */
    [data-theme="dark"] .bottom-nav {
        background: var(--bg-secondary) !important;
        border-top: 1px solid var(--border-color) !important;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.3);
    }
    
    [data-theme="dark"] .bottom-nav .nav-link {
        color: var(--text-secondary) !important;
    }
    
    [data-theme="dark"] .bottom-nav .nav-link.active,
    [data-theme="dark"] .bottom-nav .nav-link:active,
    [data-theme="dark"] .bottom-nav .nav-link:focus {
        background: var(--gold-primary) !important;
        color: #121624 !important;
        box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
    }
    
    /* Dark Mode Sidebar */
    [data-theme="dark"] .sidebar-nav {
        background: var(--bg-primary);
        box-shadow: 2px 0 10px rgba(0,0,0,0.5);
    }
    
    [data-theme="dark"] .sidebar-header {
        background: var(--bg-tertiary);
        border-bottom: 1px solid var(--border-color);
    }
    
    [data-theme="dark"] .sidebar-menu-item {
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .sidebar-menu-item:hover {
        background: var(--bg-tertiary);
        color: var(--text-primary);
    }
    
    /* Dark Mode Balance Dropdown */
    [data-theme="dark"] #balanceDropdownBar {
        background: var(--bg-secondary) !important;
        color: var(--text-primary) !important;
        border-top: 1px solid var(--border-color);
        box-shadow: 0 -2px 12px rgba(0,0,0,0.3);
    }

    /* Dark mode balance dropdown text and icons - override inline styles */
    [data-theme="dark"] #balanceDropdownBar .fw-semibold {
        color: #FFFFFF !important;
    }

    [data-theme="dark"] #balanceDropdownBar .iconify {
        color: #FFFFFF !important;
    }

    [data-theme="dark"] #balanceDropdownBar .badge {
        background: var(--gold-primary) !important;
        color: #121624 !important;
    }

    [data-theme="dark"] #balanceDropdownChevron {
        color: #FFFFFF !important;
    }
    
    [data-theme="dark"] .balance-dropdown-content {
        background: var(--bg-primary) !important;
        border-top: 1px solid var(--border-color);
        box-shadow: 0 -4px 24px rgba(0,0,0,0.4) !important;
    }
    
    /* Dark Mode Modals */
    [data-theme="dark"] .modal-content {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
    }
    
    [data-theme="dark"] .modal-body {
        background-color: var(--bg-card) !important;
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .modal-header {
        background-color: var(--bg-tertiary) !important;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .form-control,
    [data-theme="dark"] .form-select {
        background-color: var(--bg-tertiary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .form-control:focus,
    [data-theme="dark"] .form-select:focus {
        background-color: var(--bg-tertiary) !important;
        border-color: var(--gold-primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }
    
    [data-theme="dark"] .form-label {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .form-check-label {
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Reset Password Modal - All text white */
    [data-theme="dark"] #resetPasswordModal .modal-content {
        background-color: var(--bg-card) !important;
        border: 1px solid var(--border-color) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .modal-header {
        background-color: var(--bg-tertiary) !important;
        border-bottom: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .modal-title {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .modal-body {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .form-label,
    [data-theme="dark"] #resetPasswordModal label {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .form-control {
        background-color: var(--bg-tertiary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .form-control:focus {
        background-color: var(--bg-tertiary) !important;
        border-color: var(--gold-primary) !important;
        color: var(--text-primary) !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }
    
    [data-theme="dark"] #resetPasswordModal .btn {
        background-color: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
        color: #121624 !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .btn:hover {
        background-color: var(--gold-secondary) !important;
        border-color: var(--gold-secondary) !important;
        color: #121624 !important;
    }
    
    [data-theme="dark"] #resetPasswordModal .btn-close {
        filter: invert(1);
    }
    
    /* All text elements in reset password modal to be white */
    [data-theme="dark"] #resetPasswordModal * {
        color: var(--text-primary) !important;
    }
    
    /* Override specific inline styles that might conflict */
    [data-theme="dark"] #resetPasswordModal .modal-header .text-white {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] #resetPasswordModal [style*="color"] {
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Buttons */
    [data-theme="dark"] .btn-primary,
    [data-theme="dark"] .btn[style*="background-color:#424242"] {
        /* background-color: var(--gold-primary) !important; */
        /* border-color: var(--gold-primary) !important; */
        color: #121624 !important;
        font-weight: 600;
    }
    
    [data-theme="dark"] .btn-primary:hover,
    [data-theme="dark"] .btn[style*="background-color:#424242"]:hover {
        background-color: var(--gold-secondary) !important;
        border-color: var(--gold-secondary) !important;
        color: #121624 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }
    
    [data-theme="dark"] .btn-secondary,
    [data-theme="dark"] .btn[style*="background-color:#757575"] {
        background-color: var(--bg-tertiary) !important;
        border-color: var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Body */
    [data-theme="dark"] body {
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Alerts */
    [data-theme="dark"] .alert-success {
        background-color: rgba(3, 218, 198, 0.1) !important;
        border-color: var(--success-color) !important;
        color: var(--success-color) !important;
    }
    
    [data-theme="dark"] .alert-danger {
        background-color: rgba(207, 102, 121, 0.1) !important;
        border-color: var(--danger-color) !important;
        color: var(--danger-color) !important;
    }
    
    /* Dark Mode Badge */
    [data-theme="dark"] .badge {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
        font-weight: 600;
    }
    
    /* Dark Mode Container */
    [data-theme="dark"] .container-fluid {
        background-color: var(--bg-primary);
    }
    
    /* Dark Mode Main Container */
    [data-theme="dark"] .main-container {
        background-color: var(--bg-primary);
    }
    
    /* Dark Mode Cards and Content */
    [data-theme="dark"] .card {
        background-color: var(--bg-card) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-header {
        background-color: var(--bg-tertiary) !important;
        border-bottom: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .card-body {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark mode table responsive containers */
    [data-theme="dark"] .table-responsive {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark mode tab content */
    [data-theme="dark"] .tab-content {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .tab-pane {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Text Colors */
    [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
    [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 {
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] p, [data-theme="dark"] span, [data-theme="dark"] div {
        color: var(--text-primary);
    }
    
    [data-theme="dark"] .text-muted {
        color: var(--text-muted) !important;
    }
    
    /* Dark mode text in tables and cards */
    [data-theme="dark"] .card-body .text-muted,
    [data-theme="dark"] .table .text-muted {
        color: var(--text-muted) !important;
    }
    
    /* Dark mode for small text elements */
    [data-theme="dark"] small,
    [data-theme="dark"] .small {
        color: var(--text-secondary) !important;
    }
    
    /* Dark mode for text center elements */
    [data-theme="dark"] .text-center {
        color: var(--text-primary);
    }
    
    /* Dark Mode Links */
    [data-theme="dark"] a {
        color: var(--gold-primary) !important;
    }
    
    [data-theme="dark"] a:hover {
        color: var(--gold-secondary) !important;
    }
    
    /* Dark Mode Tables */
    [data-theme="dark"] .table {
        --bs-table-bg: var(--bg-card);
        --bs-table-color: var(--text-primary);
        --bs-table-border-color: var(--border-color);
        --bs-table-striped-bg: var(--bg-tertiary);
        --bs-table-hover-bg: var(--bg-tertiary);
    }
    
    /* Ensure all table text is properly colored in dark mode */
    [data-theme="dark"] .table th,
    [data-theme="dark"] .table td,
    [data-theme="dark"] .table thead th,
    [data-theme="dark"] .table tbody td,
    [data-theme="dark"] .table-striped > tbody > tr > td,
    [data-theme="dark"] .table-striped > tbody > tr > th {
        /*color: var(--text-primary) !important;*/
        background-color: inherit;
    }
    
    /* Dark mode table headers */
    [data-theme="dark"] .table thead th {
        border-bottom-color: var(--border-color) !important;
        background-color: var(--bg-tertiary) !important;
        /* color: var(--text-primary) !important; */
    }
    
    /* Dark mode table borders */
    [data-theme="dark"] .table td,
    [data-theme="dark"] .table th {
        border-top-color: var(--border-color) !important;
        border-color: var(--border-color) !important;
    }
    
    /* Dark mode striped rows */
    [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td,
    [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > th {
        background-color: var(--bg-tertiary) !important;
        /*color: var(--text-primary) !important;*/
    }
    
    /* Dark mode table hover effects */
    [data-theme="dark"] .table-hover > tbody > tr:hover > td,
    [data-theme="dark"] .table-hover > tbody > tr:hover > th {
        background-color: rgba(255, 255, 255, 0.05) !important;
        /*color: var(--text-primary) !important;*/
    }
    
    /* Dark Mode Select2 (if used) */
    [data-theme="dark"] .select2-container .select2-selection {
        background-color: var(--bg-tertiary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .select2-dropdown {
        background-color: var(--bg-card) !important;
        border: 1px solid var(--border-color) !important;
    }
    
    [data-theme="dark"] .select2-results__option {
        background-color: var(--bg-card) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .select2-results__option--highlighted {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
    }
    
    /* Dark Mode Input Groups */
    [data-theme="dark"] .input-group-text {
        background-color: var(--bg-tertiary) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    /* Dark Mode Progress Bars */
    [data-theme="dark"] .progress {
        background-color: var(--bg-tertiary) !important;
    }
    
    [data-theme="dark"] .progress-bar {
        background-color: var(--gold-primary) !important;
    }
    
    /* Dark Mode List Groups */
    [data-theme="dark"] .list-group-item {
        background-color: var(--bg-card) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-primary) !important;
    }
    
    [data-theme="dark"] .list-group-item:hover {
        background-color: var(--bg-tertiary) !important;
    }
    
    /* Dark Mode Borders */
    [data-theme="dark"] .border {
        border-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .border-top {
        border-top-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .border-bottom {
        border-bottom-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .border-left {
        border-left-color: var(--border-color) !important;
    }
    
    [data-theme="dark"] .border-right {
        border-right-color: var(--border-color) !important;
    }
    
    /* Enhanced theme switcher animation */
    .theme-switcher {
        overflow: hidden;
    }
    
    .theme-switcher .iconify {
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
    }
    
    [data-theme="dark"] .theme-switcher:hover .theme-icon-dark {
        transform: rotate(20deg) scale(1.1);
        color: var(--gold-primary);
    }
    
    [data-theme="light"] .theme-switcher:hover .theme-icon-light {
        transform: rotate(-20deg) scale(1.1);
    }
    
    /* Gold accent highlights for dark theme */
    [data-theme="dark"] .topbar .badge {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
    }
    
    [data-theme="dark"] .btn-close:hover {
        color: var(--gold-primary) !important;
    }
    
    [data-theme="dark"] .form-check-input:checked {
        background-color: var(--gold-primary) !important;
        border-color: var(--gold-primary) !important;
    }
    
    [data-theme="dark"] .nav-tabs .nav-link.active {
        background-color: var(--gold-primary) !important;
        color: #121624 !important;
        border-color: var(--gold-primary) !important;
    }
    
    [data-theme="dark"] .alert-success {
        background-color: rgba(255, 215, 0, 0.1) !important;
        border-color: var(--gold-primary) !important;
        color: var(--gold-primary) !important;
    }
    
    /* Enhanced border styling for dark theme */
    [data-theme="dark"] .card {
        border-color: #2A2D35 !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
    }
    
    [data-theme="dark"] .modal-content {
        box-shadow: 0 8px 32px rgba(0,0,0,0.4) !important;
    }
    
    /* Smooth theme transition */
    * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
    }
    
    /* Enhanced Header Styles */
    .topbar {
        background-color: #FAFAFA !important;
        border-bottom: 1px solid #E5E5E5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        padding: 12px 16px !important;
        min-height: 64px;
    }
    
    .topbar .btn {
        padding: 6px 10px;
        border-radius: 8px;
        background: transparent;
        border: none;
        transition: background 0.2s;
        color: #424242;
    }
    
    .topbar .btn:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    
    .hamburger-btn {
        background: none;
        border: none;
        color: #424242;
        font-size: 24px;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .hamburger-btn:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #212121;
    }
    
    .topbar .iconify {
        font-size: 24px;
    }
    
    .topbar .dropdown-menu {
        border-radius: 12px;
        border: 1px solid #E5E5E5;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 8px;
        background-color: #FFFFFF;
    }
    
    .topbar .dropdown-item {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        transition: background 0.2s;
        color: #424242;
    }
    
    .topbar .dropdown-item:hover {
        background: #F5F5F5;
    }
    
    /* Notification dropdown specific styles */
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA !important;
        border-color: #E5E5E5 !important;
    }
    
    /* Center notification dropdown */
    #notificationDropdown + .dropdown-menu {
        left: -70% !important;
        transform: translateX(-50%) translateY(10px) !important;
        right: auto !important;
        top: 100% !important;
        margin-top: 8px !important;
    }
    
    .dropdown-menu::-webkit-scrollbar {
        width: 4px;
    }
    
    .dropdown-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .dropdown-menu::-webkit-scrollbar-thumb {
        background: rgba(66, 66, 66, 0.3);
        border-radius: 2px;
    }
    
    .dropdown-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(66, 66, 66, 0.5);
    }
    
    .topbar .badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: #424242;
        color: #FFFFFF;
    }
    
    /* Compact Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: #FFFFFF !important;
        border-top: 1px solid #E5E5E5 !important;
        box-shadow: 0 -2px 12px rgba(0,0,0,0.08);
        padding: 8px 0 max(8px, env(safe-area-inset-bottom));
        z-index: 1000;
        height: 60px;
    }
    
    .bottom-nav .nav-link {
        color: #757575 !important;
        font-size: 11px;
        border-radius: 10px;
        transition: all 0.2s ease;
        background: transparent;
        position: relative;
        min-width: 0;
        outline: none;
        padding: 6px 8px;
        margin: 0 2px;
    }
    
    .bottom-nav .nav-link.active,
    .bottom-nav .nav-link:active,
    .bottom-nav .nav-link:focus {
        background: #212121 !important;
        color: #FFFFFF !important;
        font-weight: 600;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .bottom-nav .iconify {
        font-size: 18px;
        color: inherit;
        transition: all 0.2s;
        margin-bottom: 2px;
    }
    
    .bottom-nav .nav-label {
        font-size: 10px;
        color: inherit;
        font-weight: 500;
        letter-spacing: 0.02em;
        line-height: 1;
    }
    
    .bottom-nav .nav-link::after {
        display: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .topbar {
            padding: 10px 12px !important;
            min-height: 58px;
        }
        
        .topbar .iconify {
            font-size: 22px;
        }
        
        .hamburger-btn {
            font-size: 22px;
            padding: 6px;
        }
        
        .bottom-nav {
            height: 56px;
            padding: 6px 0 max(6px, env(safe-area-inset-bottom));
        }
        
        .bottom-nav .nav-link {
            font-size: 10px;
            padding: 4px 6px;
            margin: 0 1px;
        }
        
        .bottom-nav .iconify {
            font-size: 16px;
        }
        
        .bottom-nav .nav-label {
            font-size: 9px;
        }
    }
    
    @media (max-width: 400px) {
        .topbar {
            padding: 8px 10px !important;
            min-height: 54px;
        }
        
        .bottom-nav .nav-label {
            font-size: 8px;
        }
    }
    
    /* Main container adjustments */
    .main-container {
        padding-bottom: 70px !important;
        padding-top: 74px !important;
    }
    
    @media (max-width: 576px) {
        .main-container {
            padding-bottom: 66px !important;
            padding-top: 68px !important;
        }
    }
    
    @media (max-width: 400px) {
        .main-container {
            padding-bottom: 64px !important;
            padding-top: 64px !important;
        }
    }
    
    .iconify {
        font-size: 16px;
    }
    
    /* Sidebar Navigation Styles */
    .sidebar-nav {
        position: fixed;
        top: 0;
        left: -300px;
        width: 300px;
        height: 100vh;
        background: #424242;
        backdrop-filter: blur(20px);
        transition: left 0.3s ease;
        z-index: 2000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.3);
    }
    
    .sidebar-nav.active {
        left: 0;
    }
    
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1999;
    }
    
    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .sidebar-header {
        padding: 20px;
        background: #616161;
        border-bottom: 1px solid #757575;
        color: white;
    }
    
    .sidebar-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        float: right;
        cursor: pointer;
    }
    
    .sidebar-menu {
        padding: 20px 0;
    }
    
    .sidebar-menu-item {
        display: block;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        border-bottom: 1px solid #616161;
        transition: all 0.3s ease;
    }
    
    .sidebar-menu-item:hover {
        background: #616161;
        color: white;
        text-decoration: none;
    }
    
    .sidebar-menu-item .iconify {
        margin-right: 10px;
        font-size: 20px;
    }

    /* Balance Dropdown Content Production Styles */
    .balance-dropdown-content {
        position: fixed !important;
        left: 0 !important;
        right: 0 !important;
        /* bottom is controlled by JS, do NOT force it here! */
        z-index: 1041 !important;
        background: #FAFAFA !important;
        border-radius: 16px 16px 0 0 !important;
        transition: bottom 0.12s cubic-bezier(.4,0,.2,1), opacity 0.12s cubic-bezier(.4,0,.2,1), visibility 0.12s cubic-bezier(.4,0,.2,1) !important;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.12) !important;
        padding: 0 0 16px 0 !important;
        max-width: 100vw !important;
        min-height: 120px !important;
        opacity: 0;
        visibility: hidden;
        border-top: 1px solid #E5E5E5;
    }
    .balance-dropdown-content .p-3 {
        /* No debug outline */
    }
    #balanceDropdownBar {
        /* No debug outline */
    }
    
    /* Price Color Change Styles for Mobile */
    .price-up {
        color: #10B981 !important; /* Green for price increase */
        font-weight: bold;
        transition: color 0.3s ease;
    }
    
    .price-down {
        color: #EF4444 !important; /* Red for price decrease */
        font-weight: bold;
        transition: color 0.3s ease;
    }
    
    .price-unchanged {
        color: #6B7280 !important; /* Gray for no change */
        transition: color 0.3s ease;
    }
    
    /* Dark theme price colors */
    [data-theme="dark"] .price-up {
        color: #34D399 !important; /* Brighter green for dark mode */
    }
    
    [data-theme="dark"] .price-down {
        color: #F87171 !important; /* Brighter red for dark mode */
    }
    
    [data-theme="dark"] .price-unchanged {
        color: #9CA3AF !important; /* Lighter gray for dark mode */
    }
    
    /* Mobile price display enhancements */
    #sell-price, #buy-price {
        transition: all 0.3s ease;
        display: inline-block;
        padding: 2px 4px;
        border-radius: 4px;
    }
</style>

</head>

<div class="container-fluid topbar p-0">
    <div class="row align-items-center justify-content-between mx-0">
        <div class="col-auto">
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Open navigation menu">
                <span class="iconify" data-icon="material-symbols:menu" data-inline="false"></span>
            </button>
        </div>
        
        <div class="col-auto">
            <div class="d-flex align-items-center gap-1">
                <!-- Theme Switcher -->
                <button class="btn theme-switcher" id="themeSwitcher" aria-label="Toggle theme">
                    <span class="iconify theme-icon-light" data-icon="material-symbols:light-mode" data-inline="false"></span>
                    <span class="iconify theme-icon-dark" data-icon="material-symbols:dark-mode" data-inline="false" style="display: none;"></span>
                </button>
                
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="iconify" data-icon="line-md:bell-loop" data-inline="false"></span>
                        @if (isset($notifications) && $notifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color:#424242;color:#FFFFFF;font-size:9px;padding:2px 6px;">
                                @if ($notifications->count() > 9) 9+ @else {{$notifications->count()}} @endif
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="notificationDropdown" style="min-width: 280px;max-width:85vw;border-radius:10px;border:1px solid #E5E5E5;box-shadow:0 3px 15px rgba(0,0,0,0.08);background-color:#FAFAFA;">
                        @if (!isset($notifications) || $notifications->count() == 0)
                            <li class="dropdown-header" style="background-color:#424242;color:#FFFFFF;padding:8px 12px;margin:-8px -8px 6px -8px;border-radius:10px 10px 0 0;font-weight:600;font-size:0.85rem;">
                                <span class="iconify me-1" data-icon="material-symbols:notifications" style="font-size:1rem;"></span>
                                {{__('web.notifications')}}
                            </li>
                            <li style="padding:16px 12px;text-align:center;">
                                <div style="color:#9E9E9E;margin-bottom:6px;">
                                    <span class="iconify" data-icon="material-symbols:notifications-off" style="font-size:1.5rem;opacity:0.5;"></span>
                                </div>
                                <div style="color:#757575;font-size:0.85rem;font-weight:500;">{{__('web.no_notification')}}</div>
                                <div style="color:#9E9E9E;font-size:0.75rem;margin-top:3px;">{{__('web.check_back_later')}}</div>
                            </li>
                        @else
                            <li class="dropdown-header" style="background-color:#ffffff;color:#FFFFFF;padding:8px 12px;margin:-8px -8px 6px -8px;border-radius:10px 10px 0 0;font-weight:600;font-size:0.85rem;display:flex;align-items:center;justify-content:space-between;">
                                <span>
                                    <span class="iconify me-1" data-icon="material-symbols:notifications" style="font-size:1rem;"></span>
                                    {{__('web.notifications')}}
                                </span>
                                <span id="notificationCounter" style="background-color:rgba(255,255,255,0.2);color:#FFFFFF;font-size:0.7rem;padding:2px 6px;border-radius:8px;font-weight:500;">
                                    {{isset($notifications) ? $notifications->count() : 0}}
                                </span>
                            </li>
                            <div style="max-height:200px;overflow-y:auto;">
                                @foreach ($notifications as $notification)
                                    <li style="margin:0 6px;">
                                        <div class="dropdown-item notification-item" data-notification-id="{{$notification->id}}" style="border-radius:6px;padding:8px 10px;margin-bottom:3px;background-color:#FFFFFF;border:1px solid #F0F0F0;transition:all 0.2s;position:relative;cursor:pointer;">
                                            <div style="display:flex;align-items:flex-start;gap:8px;">
                                                <div class="notification-dot" style="flex-shrink:0;width:6px;height:6px;background-color:#424242;border-radius:50%;margin-top:4px;"></div>
                                                <div style="flex:1;">
                                                    <div style="font-weight:600;color:#212121;font-size:0.8rem;margin-bottom:3px;line-height:1.2;">
                                                        {{__('web.'.$notification->text)}}
                                                    </div>
                                                    <div style="color:#757575;font-size:0.7rem;display:flex;align-items:center;gap:3px;">
                                                        <span class="iconify" data-icon="material-symbols:schedule" style="font-size:0.8rem;"></span>
                                                        {{date('d/m H:i', strtotime($notification->created_at))}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </div>
                            <li style="margin:6px 6px 0 6px;">
                                <div style="border-top:1px solid #F0F0F0;padding:6px 0;">
                                    <div style="text-align:center;padding:4px;">
                                        <span id="notificationTotal" style="color:#757575;font-size:0.75rem;">{{isset($notifications) ? $notifications->count() : 0}} notification(s) total</span>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Language Selector -->
                <div class="dropdown">
                    <button class="btn d-flex align-items-center" data-bs-toggle="dropdown" aria-label="Language selector">
                        <img src="{{ config('app.flagIconUrlForLocale.' . app()->getLocale()) }}" width="18" height="12" alt="flag icon" class="rounded">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach(['en'=>'English','ar'=>'العربية'] as $language => $name)
                            <li>
                                <a class="dropdown-item" href="{{ switchUrlLocaleTo($language) }}">
                                    <img src="{{ config('app.flagIconUrlForLocale.' . $language) }}" width="16" height="11" alt="flag icon" class="me-2 rounded">
                                    <span>{{ $name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Chat -->
                <a href="{{route('chat.index')}}" class="btn position-relative" aria-label="Chat">
                    <span class="iconify" data-icon="mynaui:message" data-inline="false"></span>
                    @if (isset($totalChat) && $totalChat > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                            @if ($totalChat > 99) +99 @else {{$totalChat}} @endif
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>

                <!-- New Order -->
                @if (!request()->routeIs('clientarea.quotes') && !isset(auth()->guard('client')->user()->options['cantOpen']))
                    <button class="btn" data-bs-toggle="modal" data-bs-target="#newOrderModal" aria-label="New order">
                        <span class="iconify" data-icon="gridicons:add-outline" data-inline="false"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

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

@if (request()->routeIs('clientarea.quotes'))
<!-- Balance Dropdown (Simple, Modern, Responsive) -->
<div id="balanceDropdownBar" class="d-flex align-items-center justify-content-between px-3 py-2 shadow-sm"
    style="position:fixed;left:0;right:0;bottom:60px;z-index:1040;background:#FAFAFA;color:#212121;cursor:pointer;border-radius:18px 18px 0 0;min-height:48px;box-shadow:0 -2px 12px rgba(0,0,0,0.07);border-top:1px solid #E5E5E5;">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="fw-semibold d-flex align-items-center" style="font-size:1rem;color:#212121;">
            <span class="iconify me-2" data-icon="mdi:wallet-outline" style="font-size:1.4rem;color:#424242;"></span>
            {{ __('web.balance') }}
        </span>
        <span class="badge ms-2" style="background:#424242;color:#FFFFFF;font-size:0.9rem;padding:0.35em 0.9em;border-radius:12px;font-weight:500;">
            $ {{ number_format(isset($finance['balance']) ? $finance['balance'] : 0, 2, '.', ',') }}
        </span>
    </div>
    <span id="balanceDropdownChevron" class="ms-2 d-flex align-items-center justify-content-center"
        style="font-size:1.3rem;transition:transform 0.2s;color:#757575;">
        <i class="fas fa-chevron-down"></i>
    </span>
</div>
<div id="balanceDropdownContent" class="balance-dropdown-content shadow-lg"
    style="padding-top: 12px; padding-bottom: 24px;">
    <div class="container">
       <div class="row justify-content-center">
          <div class="col-12 col-md-10 col-lg-8">
             @include('clientarea.balance-card', ['finance' => $finance ?? [], 'locale' => $locale ?? app()->getLocale()])
          </div>
       </div>
    </div>
</div>
@endif

<div class="container p-0 main-container">
    @yield('content')
</div>

<!-- Sidebar Navigation -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<nav class="sidebar-nav" id="sidebarNav" aria-label="Main navigation menu">
    <div class="sidebar-header position-relative" style="padding-top: 8px;">
        <button class="sidebar-close position-absolute" id="sidebarClose" aria-label="Close navigation menu" style="top: 4px; right: 8px; z-index: 10;">
            <span class="iconify" data-icon="material-symbols:close" data-inline="false"></span>
        </button>
        <h5 style="color: #fff; margin: 0; padding-top: 8px;">{{ __('web.menu') }}</h5>
    </div>
    <div class="sidebar-menu">
        <a href="#" class="sidebar-menu-item" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
            <span class="iconify" data-icon="solar:key-broken" data-inline="false"></span>
            {{__('web.reset_password')}}
        </a>
        <a href="{{route('clientarea.account')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:account-circle" data-inline="false"></span>
            {{__('web.account')}}
        </a>
        <a href="{{route('client.deposit')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:arrow-upward" data-inline="false"></span>
            {{__('web.deposit')}}
        </a>
        @if(isset(auth()->guard('client')->user()->options['enableWithdrawalRequest']) && auth()->guard('client')->user()->options['enableWithdrawalRequest'] == 1)
            <a href="{{route('client.withdraw')}}" class="sidebar-menu-item">
                <span class="iconify" data-icon="material-symbols:arrow-downward" data-inline="false"></span>
                {{__('web.withdraw')}}
            </a>
        @endif
        <a href="{{route('clientarea.orders')}}" class="sidebar-menu-item">
            <span class="iconify" data-icon="material-symbols:receipt-long" data-inline="false"></span>
            {{__('web.orders')}}
        </a>
        <a href="{{route('client.logout')}}" class="sidebar-menu-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="iconify" data-icon="material-symbols:logout" data-inline="false"></span>
            {{__('web.logout')}}
        </a>
    </div>
</nav>

<!-- Hidden logout form -->
<form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<nav class="bottom-nav navbar navbar-expand navbar-light shadow-sm" aria-label="Bottom navigation">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.quotes')) active @endif"
               href="{{ route('clientarea.quotes') }}"
               data-nav="quotes"
               aria-label="{{__('web.quotes')}}">
                <span class="iconify mb-1" data-icon="flowbite:arrow-up-down-outline"></span>
                <span class="small nav-label">{{__('web.quotes')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.charts')) active @endif"
               href="{{ route('clientarea.charts') }}"
               data-nav="charts"
               aria-label="{{__('web.charts')}}">
                <span class="iconify mb-1" data-icon="material-symbols:candlestick-chart-rounded"></span>
                <span class="small nav-label">{{__('web.charts')}}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex flex-column align-items-center py-2 @if (request()->routeIs('clientarea.orders')) active @endif"
               href="{{ route('clientarea.orders') }}"
               data-nav="orders"
               aria-label="{{__('web.orders')}}">
                <span class="iconify mb-1" data-icon="material-symbols:add-box"></span>
                <span class="small nav-label">{{__('web.orders')}}</span>
            </a>
        </li>
    </ul>
</nav>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-lg shadow-lg border-0">
            <div class="modal-header" style="background-color:#424242;color:#FFFFFF;border-bottom:1px solid #616161;">
                <h5 class="modal-title font-semibold text-white" id="resetPasswordModalLabel">{{__('web.reset_password')}}</h5>
                <button type="button" class="text-white bg-transparent border-0" data-dismiss="modal" aria-label="Close" style="color:#FFFFFF;">
                    <span aria-hidden="true" class="text-2xl">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" style="background-color:#FAFAFA;">
                <form action="{{ route('client.reset.password') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.current_password')}}</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.new_password')}}</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2" style="color:#424242;">{{__('web.confirm_new_password')}}</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control rounded-md" style="border:1px solid #E5E5E5;background-color:#FFFFFF;" required>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="btn-outline-dark btn-xs me-2" style="background-color:#424242;color:#FFFFFF;border:1px solid #424242;">{{__('web.reset_password')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- @if (!request()->routeIs('clientarea.quotes') && !isset(auth()->guard('client')->user()->options['cantOpen'])) --}}
    <div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="background-color:#FAFAFA;">
                <!-- New Market Order Tab -->
                    <div class="tab-content mt-3" id="orderTabsContent">
                        <!-- New Market Order -->
                        <div class="tab-pane fade show active" id="marketOrder" role="tabpanel">
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tab" id="newTab">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="asset-select" class="form-label" style="color:#424242;">{{__('web.item')}}</label>
                                        <select class="single-select form-select inside-modal me-2" id="asset-select" name="currency" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                            @if(isset($forexAssets))
                                                @foreach ($forexAssets as $item)
                                                    <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                            @if(isset($cryptoAssets))
                                                @foreach ($cryptoAssets as $item)
                                                    <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                            @if(isset($stocksAssets))
                                                @foreach ($stocksAssets as $item)
                                                    <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                            @if(isset($indicesAssets))
                                                @foreach ($indicesAssets as $item)
                                                    <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                            @if(isset($commodityAssets))
                                                @foreach ($commodityAssets as $item)
                                                    <option value="{{$item->id}}" data-bid="{{$item->bid_price}}" data-ask="{{$item->ask_price}}">{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="newAmount" class="form-label" style="color:#424242;">{{__('web.amount')}}</label>
                                        <input type="number" class="form-control" id="newAmount" name="amount" min="0.01" step="any" value="0.01" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="stopLossSwitch">
                                            <label class="form-check-label" for="stopLossSwitch" style="color:#424242;">{{__('web.set_stop_loss')}}</label>
                                        </div>
                                        <div id="stopLossContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="stopLossInput" step="any" name="s_l" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                        </div>
                                    </div>
                                
                                    <!-- Set Take Profit -->
                                    <div class="col-6">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input me-2" type="checkbox" id="takeProfitSwitch">
                                            <label class="form-check-label" for="takeProfitSwitch" style="color:#424242;">{{__('web.set_take_profit')}}</label>
                                        </div>
                                        <div id="takeProfitContainer" class="mt-2" style="display: none;">
                                            <input type="number" class="form-control" id="takeProfitInput" step="any" name="s_p" style="border:1px solid #E5E5E5;background-color:#FFFFFF;">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="submit" class="btn btn-md me-2" formaction="{{route('order.store',['type' => 2])}}" style="background-color:#757575;color:#FFFFFF;border:1px solid #757575;">
                                        <span>{{__('web.sell')}} <strong id="sell-price"> 0</strong></span>
                                    </button>
                                    <button type="submit" class="btn btn-md ms-2" formaction="{{route('order.store',['type' => 1])}}" style="background-color:#424242;color:#FFFFFF;border:1px solid #424242;">
                                        <span>{{__('web.buy')}} <strong id="buy-price">0 </strong></span>
                                    </button>
                                </div>
                                <input type="hidden" class="form-control" name="bid" id="bid" value="0" readonly>
                                <input type="hidden" class="form-control" name="ask" id="ask" value="0" readonly>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @endif --}}

<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script>
    var client_id = {{auth()->guard('client')->user()->id}};
    var assetId = 1;
</script>
<script>
    $('#asset-select').on('change', function() {
        const selectedOption = $(this).find(':selected');
        
        const bidPrice = selectedOption.data('bid');
        const askPrice = selectedOption.data('ask');
        
        $('#bid').val(bidPrice);
        $('#ask').val(askPrice);

        $('#sell-price').text(bidPrice);
        $('#buy-price').text(askPrice);
    });
    document.getElementById('stopLossSwitch').addEventListener('change', function() {
        document.getElementById('stopLossContainer').style.display = this.checked ? 'block' : 'none';
    });

    // Take Profit Toggle
    document.getElementById('takeProfitSwitch').addEventListener('change', function() {
        document.getElementById('takeProfitContainer').style.display = this.checked ? 'block' : 'none';
    });
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            el.classList.add('d-none');
        });
    }, 3000);
    
    // Sidebar Navigation JavaScript
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const sidebarNav = document.getElementById('sidebarNav');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    
    function openSidebar() {
        sidebarNav.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
        sidebarNav.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    hamburgerBtn.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);
    
    // Close sidebar when clicking on menu items (except reset password)
    document.querySelectorAll('.sidebar-menu-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!this.hasAttribute('data-bs-toggle')) {
                closeSidebar();
            }
        });
    });

    // Mark all notifications as read when dropdown is opened
    document.getElementById('notificationDropdown').addEventListener('click', function() {
        // Small delay to ensure dropdown is opened
        setTimeout(function() {
            markAllNotificationsAsRead();
        }, 100);
    });

    // Theme Switcher Functionality
    const themeSwitcher = document.getElementById('themeSwitcher');
    const lightIcon = themeSwitcher.querySelector('.theme-icon-light');
    const darkIcon = themeSwitcher.querySelector('.theme-icon-dark');
    const htmlElement = document.documentElement;
    
    // Get current theme from localStorage or default to dark
    let currentTheme = localStorage.getItem('theme') || 'dark';
    
    // Apply theme function (for UI updates only, theme already applied in head)
    function updateThemeUI(theme) {
        if (theme === 'dark') {
            lightIcon.style.display = 'none';
            darkIcon.style.display = 'inline';
            themeSwitcher.setAttribute('aria-label', 'Switch to light mode');
        } else {
            lightIcon.style.display = 'inline';
            darkIcon.style.display = 'none';
            themeSwitcher.setAttribute('aria-label', 'Switch to dark mode');
        }
        
        // Add a gentle glow effect during transition
        themeSwitcher.style.boxShadow = theme === 'dark'
            ? '0 0 15px rgba(255, 215, 0, 0.4)'
            : '0 0 15px rgba(66, 66, 66, 0.2)';
        
        setTimeout(() => {
            themeSwitcher.style.boxShadow = 'none';
        }, 500);
    }
    
    // Initialize theme UI (theme is already applied to HTML in head)
    updateThemeUI(currentTheme);
    
    // Theme switcher click handler
    themeSwitcher.addEventListener('click', function() {
        // Add click animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1.05)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
        }, 100);
        
        // Toggle theme
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', currentTheme);
        
        // Apply theme to HTML element
        htmlElement.setAttribute('data-theme', currentTheme);
        // Update UI elements
        updateThemeUI(currentTheme);
        
        // Optional: Add a subtle vibration on mobile devices
        if ('vibrate' in navigator) {
            navigator.vibrate(50);
        }
        
    });

    // Also mark as read when dropdown is shown via Bootstrap event
    document.getElementById('notificationDropdown').addEventListener('shown.bs.dropdown', function() {
        markAllNotificationsAsRead();
    });

    // Notification click handler to mark as read
    document.addEventListener('click', function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            const notificationId = notificationItem.getAttribute('data-notification-id');
            markNotificationAsRead(notificationId, notificationItem);
        }
    });

    function markAllNotificationsAsRead() {
        // Hide notification badge immediately
        const badge = document.querySelector('#notificationDropdown .badge');
        if (badge) {
            badge.style.display = 'none';
        }

        // Mark all notification items as read visually
        document.querySelectorAll('.notification-item').forEach(function(item) {
            item.style.opacity = '0.6';
            item.style.backgroundColor = '#F8F9FA';
            const dot = item.querySelector('.notification-dot');
            if (dot) {
                dot.style.backgroundColor = '#E0E0E0';
            }
        });

        // Update counters to 0
        const counterInDropdown = document.getElementById('notificationCounter');
        const totalCounter = document.getElementById('notificationTotal');
        
        if (counterInDropdown) {
            counterInDropdown.textContent = '0';
        }
        
        if (totalCounter) {
            totalCounter.textContent = '0 notification(s) total';
        }

        // Send AJAX request to mark all notifications as read on server
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            }
        })
        .catch(error => {
        });
    }

    function markNotificationAsRead(notificationId, notificationElement) {
        // Mark notification as read visually
        notificationElement.style.opacity = '0.6';
        notificationElement.style.backgroundColor = '#F8F9FA';
        const dot = notificationElement.querySelector('.notification-dot');
        if (dot) {
            dot.style.backgroundColor = '#E0E0E0';
        }

        // Update counters
        updateNotificationCount();

        // Send AJAX request to mark as read on server
        fetch('/notifications/' + notificationId + '/read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                notification_id: notificationId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Notification marked as read successfully
            }
        })
        .catch(error => {
        });
    }

    function updateNotificationCount() {
        const unreadNotifications = document.querySelectorAll('.notification-item:not([style*="opacity: 0.6"])');
        const count = unreadNotifications.length;
        
        // Update badge count
        const badge = document.querySelector('#notificationDropdown .badge');
        const counterInDropdown = document.getElementById('notificationCounter');
        const totalCounter = document.getElementById('notificationTotal');
        
        if (count === 0) {
            if (badge) badge.style.display = 'none';
        } else {
            if (badge) {
                badge.style.display = 'block';
                badge.textContent = count > 9 ? '9+' : count;
            }
        }
        
        if (counterInDropdown) {
            counterInDropdown.textContent = count;
        }
        
        if (totalCounter) {
            totalCounter.textContent = count + ' notification(s) total';
        }
    }
</script>

<script src="{{ url('assets/js/main_tp.js') }}"></script>
<script src="{{ url('js/mobile-style.js?v1.0') }}"></script>

<script>
    // Balance Dropdown Slide Up/Down (toggle on bar click)
    $(function() {
        var $balanceBar = $('#balanceDropdownBar');
        var $balanceContent = $('#balanceDropdownContent');
        var $chevron = $('#balanceDropdownChevron');
        var balanceOpen = false;

        // Initialize chevron to down position
        $chevron.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

        function openBalanceDropdown() {
            $balanceContent[0].style.setProperty('bottom', '60px', 'important');
            $balanceContent[0].style.setProperty('visibility', 'visible', 'important');
            $balanceContent[0].style.setProperty('opacity', '1', 'important');
            $chevron.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            balanceOpen = true;
        }
        function closeBalanceDropdown() {
            $balanceContent[0].style.setProperty('bottom', '-400px', 'important');
            $balanceContent[0].style.setProperty('visibility', 'hidden', 'important');
            $balanceContent[0].style.setProperty('opacity', '0', 'important');
            $chevron.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            balanceOpen = false;
        }

        // Always reset dropdown on resize/orientationchange
        $(window).on('resize orientationchange', function() {
            closeBalanceDropdown();
        });

        // Close dropdown if user taps outside
        $(document).on('click', function(e) {
            if (balanceOpen && !$(e.target).closest('#balanceDropdownBar, #balanceDropdownContent').length) {
                closeBalanceDropdown();
            }
        });

        // Close button handler for balance card
        $(document).on('click', '#closeBalanceDropdown', function(e) {
            e.stopPropagation();
            closeBalanceDropdown();
        });

        // Hide balance dropdown when any modal is opened
        $(document).on('show.bs.modal', '.modal', function() {
            if (balanceOpen) {
                closeBalanceDropdown();
            }
        });

        // Main click handler for balance dropdown toggle
        $balanceBar.on('click', function(e) {
            e.stopPropagation();
            // Check if any modal is currently visible
            if ($('.modal.show').length > 0) {
                return; // Don't toggle if modal is open
            }
            
            // Toggle the dropdown
            if (balanceOpen) {
                closeBalanceDropdown();
            } else {
                openBalanceDropdown();
            }
        });
    });
</script>

<script>
    // Mobile Price Update System with Color Indicators
    let mobilePreviousPrices = {};
    let mobilePriceUpdateInterval;
    
    // Update mobile prices with color indicators
    function updateMobilePricesWithColors(priceData) {
        
        if (Array.isArray(priceData)) {
            priceData.forEach(function(asset) {
                if(asset.id == $('#asset-select').find(':selected').val()){
                    updateMobileSingleAssetPrice(asset);
                }
            });
        } else if (priceData.assets && Array.isArray(priceData.assets)) {
            priceData.assets.forEach(function(asset) {
                if(asset.id == $('#asset-select').find(':selected').val()){
                    updateMobileSingleAssetPrice(asset);
                }
            });
        } else {
            updateMobileSingleAssetPrice(priceData);
        }
        
    }
    
    function updateMobileSingleAssetPrice(asset) {
        if (!asset || !asset.id) {
            return;
        }
        
        const assetId = asset.id;
        const newBidPrice = parseFloat(asset.bid_price || 0);
        const newAskPrice = parseFloat(asset.ask_price || 0);
        
        
        // Get previous prices for comparison
        const prevPrices = mobilePreviousPrices[assetId] || { bid: newBidPrice, ask: newAskPrice };
        
        // Update bid price (sell button)
        updateMobilePriceElement('#sell-price', newBidPrice, prevPrices.bid, 'bid');
        
        // Update ask price (buy button)
        updateMobilePriceElement('#buy-price', newAskPrice, prevPrices.ask, 'ask');
        
        // Update hidden inputs
        $('#bid').val(newBidPrice);
        $('#ask').val(newAskPrice);
        
        // Store current prices for next comparison
        mobilePreviousPrices[assetId] = { bid: newBidPrice, ask: newAskPrice };
        
    }
    
    function updateMobilePriceElement(selector, newPrice, previousPrice, priceType) {
        const element = $(selector);
        
        if (element.length === 0) {
            return;
        }
        
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
    }
    
    function startMobilePriceUpdates() {
        
        // Clear any existing interval
        if (mobilePriceUpdateInterval) {
            clearInterval(mobilePriceUpdateInterval);
        }
        
        // Update immediately
        updateMobilePricesFromAPI();
        
        // Set up automatic updates every 2 seconds
        mobilePriceUpdateInterval = setInterval(function() {
            updateMobilePricesFromAPI();
        }, 2000);
        
    }
    
    function updateMobilePricesFromAPI() {
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
                updateMobilePricesWithColors(response);
            },
            error: function(xhr, status, error) {
            }
        });
    }
    
    function stopMobilePriceUpdates() {
        if (mobilePriceUpdateInterval) {
            clearInterval(mobilePriceUpdateInterval);
            mobilePriceUpdateInterval = null;
        }
    }
    
    // Start mobile price updates when page loads
    $(document).ready(function() {
        startMobilePriceUpdates();
        
        // Stop updates when user leaves the page
        $(window).on('beforeunload', function() {
            stopMobilePriceUpdates();
        });
        
        // Pause updates when page is not visible (mobile browser tab switching)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopMobilePriceUpdates();
            } else {
                startMobilePriceUpdates();
            }
        });
    });
</script>

<script>
    // Interactive hover/focus effect for nav bar (mobile-friendly)
    document.querySelectorAll('.bottom-nav .nav-link').forEach(link => {
        link.addEventListener('touchstart', function() {
            this.classList.add('active');
        });
        link.addEventListener('touchend', function() {
            if (!this.href.endsWith(window.location.pathname)) {
                this.classList.remove('active');
            }
        });
        link.addEventListener('mouseenter', function() {
            this.classList.add('hovered');
        });
        link.addEventListener('mouseleave', function() {
            this.classList.remove('hovered');
        });
    });
</script>
</html>

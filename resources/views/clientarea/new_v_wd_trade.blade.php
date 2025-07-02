<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WebTrader - Trading Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ url('css/webtrader.css') }}" rel="stylesheet" />
    <style>
        body {
            background: #181d23;
            color: #e0e0e0;
            font-family: 'Segoe UI', 'Inter', Arial, sans-serif;
        }
        #assetGrid::-webkit-scrollbar {
            width: 4px;
        }
        #assetGrid::-webkit-scrollbar-thumb {
            background-color: #4f8cff;
            border-radius: 10px;
        }
        #assetGrid {
            overflow-x: hidden !important; 
            overflow-y: auto !important;   
            max-width: 100%;               
        }
        #customContextMenu {
            position: absolute;
            z-index: 1050;
            background: #23272f;
            color: #e0e0e0;
            border-radius: 7px;
            box-shadow: 0 4px 16px #0006;
            display: none;
            padding: 0.2rem 0;
            border: 1px solid #353b48;
            font-family: 'Segoe UI', 'Inter', Arial, sans-serif;
        }
        #customContextMenu button {
            background: none;
            border: none;
            color: #e0e0e0;
            width: 100%;
            padding: 6px 12px;
            text-align: left;
            cursor: pointer;
            font-size: 0.92rem;
            transition: background 0.18s, color 0.18s;
            border-radius: 0;
        }
        #customContextMenu button:first-child {
            border-top-left-radius: 7px;
            border-top-right-radius: 7px;
        }
        #customContextMenu button:last-child {
            border-bottom-left-radius: 7px;
            border-bottom-right-radius: 7px;
        }
        #customContextMenu button:hover, #customContextMenu button:focus {
            background: linear-gradient(90deg, #4f8cff22 0%, #23272f 100%);
            color: #4f8cff;
            outline: none;
        }
        .star-icon {
            color: #4f8cff;
            margin-left: 6px;
            font-size: 1.1rem;
        }
        .sidebar {
            background: #181d23;
            min-height: 100vh;
            width: 50px;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 0 1rem 0;
            border-right: 1px solid #23272f;
            z-index: 100;
            box-shadow: 2px 0 16px #0003;
        }
        .sidebar img {
            width: 48px;
            margin-bottom: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px #0004;
        }
        .sidebar .nav-icon {
            color: #7a8599;
            font-size: 2rem;
            margin: 1.5rem 0;
            cursor: pointer;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
            padding: 0.7rem;
            border-radius: 10px;
        }
        .sidebar .nav-icon.active,
        .sidebar .nav-icon:hover {
            color: #fff;
            background: #23272f;
            box-shadow: 0 2px 8px #0002;
        }
        .sidebar .logout {
            margin-top: auto;
            color: #ff4d4f;
        }
        .main-content {
            padding: 2.5rem 2vw 2rem 2vw;
            margin-left: 50px;
        }
        
        .interface-container {
            margin-left: 50px;
            min-height: 100vh;
            padding: 2rem;
            background: #181d23;
        }
        .panel {
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 2rem 1.5rem 2rem;
            margin-left: 10px;
        }
        .details-panel {
            height: 34vh;
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 2rem 1.5rem 2rem;
            margin-left: 20px;
        }
        .assets{
            max-height: 371px;
            overflow-y: auto;
        }
        .right-side-panel{
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            padding: 2rem 0.01rem 1rem 1rem;
            margin-left: -20px;
            margin-right: -35px;
        }
        .order-form {
            background: #1c1f26;
            border-radius: 15px;
            box-shadow: 0 4px 24px #0003;
            margin-left: -20px;
            margin-right: -35px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .order-form .d-flex {
            justify-content: center !important;
            align-items: center !important;
            width: 100%;
        }
        .btnorder {
            width: 180px;
            height: 50px;
            font-size: 0.7rem;
            border-radius: 7px;
            border: none;
            box-shadow: 0 2px 8px #0002;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-left: -1px;
            margin-right: -1px;
        }
        .btnorder.btn-danger {
            background: linear-gradient(90deg, #ff4d4f 60%, #c82333 100%);
            color: #fff;
        }
        .btnorder.btn-success {
            background: linear-gradient(90deg, #05ab18 60%, #218838 100%);
            color: #fff;
        }
        .btnorder:hover, .btnorder:focus {
            box-shadow: 0 4px 16px #4f8cff33;
            opacity: 0.95;
            outline: none;
        }
        .amount, .btnminus, .btnplus {
            width: 100px;
            height: 50px;
            background: #23272f;
            color: #b0b8c1;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: 600;
            transition: background 0.2s, color 0.2s;
        }
        
        /* Improved input group styling */
        .input-group {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .input-group .amount {
            border-radius: 0;
            border-left: 1px solid #353b48;
            border-right: 1px solid #353b48;
            width: 80px;
        }
        
        .input-group .btnminus {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            width: 40px;
        }
        
        .input-group .btnplus {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            width: 40px;
        }
        
        /* Asset button improvements */
        .asset-button {
            background: #23272f;
            border: 1px solid #353b48;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            text-align: left;
            width: 100%;
            display: flex !important; /* Ensure Bootstrap row display is maintained */
        }
        
        /* Hidden asset override */
        .asset-button.d-none,
        .asset-button.hidden,
        .asset-button[style*="display: none"] {
            display: none !important;
        }
        
        .asset-button:hover {
            background: #2a2f38;
            border-color: #4f8cff;
            transform: translateY(-1px);
        }
        
        .asset-button .col-3 {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            text-align: center;
        }
        .sellPrice, .buyPrice {
            font-size: 0.9rem;
            color: #fff;
        }
        .btnminus, .btnplus {
            width: 50px;
        }
        .input-group {
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .tv-widget-container {
            height: 55vh;
            min-height: 340px;
        }
        .nav-tabs {
            gap: 0.5rem;
        }
        .nav-tabs .nav-link {
            color: #b0b8c1;
            border: none;
            background: transparent;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-tabs .nav-link.active {
            color: #fff;
            background: #1c1f26;
        }
        .account-summary-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 2.2rem;
            align-items: center;
        }
        .account-summary-inline div {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .account-summary-inline .text-secondary {
            color: #b0b8c1 !important;
        }
        .table-dark {
            --bs-table-bg: #1c1f26;
            --bs-table-striped-bg: #20242b;
            --bs-table-hover-bg: #23272f;
        }
        .table-dark th, .table-dark td {
            color: #e0e0e0;
            vertical-align: middle;
        }
        .table-dark th {
            font-weight: 600;
            font-size: 1.01rem;
            border-bottom: 2px solid #23272f;
        }
        .table-dark td {
            font-size: 0.98rem;
        }
        .nav-tabs .nav-item {
            flex: 1;
            text-align: center;
        }
        .nav-tabs .nav-link {
            padding: 0.5rem 0.5rem !important;
            font-size: 0.9rem;
        }
        .ask_price {
            margin-left: 30px;
        }
        .market-assets{
            background: #1c1f26;
            color: #e0e0e0;
            border: 10px;
            transition: background 0.2s, color 0.2s;
        }
        .market-assets:hover,
        .market-assets.active {
            background: linear-gradient(90deg, #23272f 0%, #1c1f26 100%);
            color: #4f8cff;
            box-shadow: 0 2px 12px #4f8cff22;
            border-left: 3px solid #05ab1875;
            transition: background 0.1s, color 0.2s, box-shadow 0.2s, border-left 0.2s;
        }
        .searchbar{
            width: 20vh;
            background: #23272f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        .filtercategory{
            width: 20vh;
            background: #23272f;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
            padding: 0.3rem 0.5rem;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }
        
        /* Additional styles for withdrawal and account interfaces */
        .summary-card {
            background: #1c1f26;
            border: 1px solid #23272f;
            border-radius: 15px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.3s;
        }
        
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(79, 140, 255, 0.15);
        }
        
        .summary-card-body {
            padding: 1.5rem;
        }
        
        .summary-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .balance-icon {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
        }
        
        .pending-icon {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            color: #1a1a1a;
        }
        
        .completed-icon {
            background: linear-gradient(135deg, #05ab18, #28a745);
            color: white;
        }
        
        .btn-gradient-primary {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #3a7cff, #5b93ff);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 140, 255, 0.3);
        }
        
        .btn-gradient-danger {
            background: linear-gradient(135deg, #ff4757, #ff6b7a);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-gradient-danger:hover {
            background: linear-gradient(135deg, #ff3742, #ff5a6d);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 71, 87, 0.3);
        }
        
        .btn-gradient-warning {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            border: none;
            color: #1a1a1a;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-gradient-warning:hover {
            background: linear-gradient(135deg, #e6b800, #ffcc02);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 204, 2, 0.3);
        }
        
        /* Account interface styles */
        .modern-interface-container {
            padding: 2rem;
            min-height: 100vh;
        }
        
        .interface-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #23272f;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .interface-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 2rem;
            box-shadow: 0 8px 32px rgba(79, 140, 255, 0.3);
        }
        
        .interface-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .header-text h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #4f8cff, #9f69ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header-text p {
            color: #b0b8c1;
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #4f8cff, #6ba3ff);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(79, 140, 255, 0.15);
            border-color: #4f8cff;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }
        
        .balance-card .stat-icon {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
        }
        
        .equity-card .stat-icon {
            background: linear-gradient(135deg, #05ab18, #28a745);
            color: white;
        }
        
        .margin-card .stat-icon {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            color: #1a1a1a;
        }
        
        .pnl-card .stat-icon {
            background: linear-gradient(135deg, #ff4757, #ff6b7a);
            color: white;
        }
        
        .stat-content h3 {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-content p {
            color: #b0b8c1;
            font-size: 1rem;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .stat-trend {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-trend.positive {
            background: rgba(5, 171, 24, 0.1);
            color: #05ab18;
        }
        
        .stat-trend.negative {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
        }
        
        .stat-trend.neutral {
            background: rgba(176, 184, 193, 0.1);
            color: #b0b8c1;
        }
        
        .info-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .info-card {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(79, 140, 255, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #23272f 0%, #2c323a 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #353b48;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h3 {
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .card-header i {
            color: #4f8cff;
            margin-right: 0.5rem;
        }
        
        .edit-profile-btn {
            background: transparent;
            border: 1px solid #4f8cff;
            color: #4f8cff;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .edit-profile-btn:hover {
            background: #4f8cff;
            color: white;
        }
        
        .card-content {
            padding: 2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #353b48;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-row .label {
            color: #b0b8c1;
            font-weight: 500;
        }
        
        .info-row .value {
            color: white;
            font-weight: 600;
        }
        
        .info-row .value.enhanced {
            font-size: 1.1rem;
            font-weight: 700;
            color: #e0e0e0;
        }
        
        .info-row .label {
            color: #b0b8c1;
            font-weight: 500;
            font-size: 1rem;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #353b48;
        }
        
        .stats-grid-2x2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 1.5rem;
            justify-items: center;
            align-items: center;
            min-height: 200px;
        }
        
        .stats-grid-3x2 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 1.5rem;
            justify-items: center;
            align-items: center;
            min-height: 200px;
        }
        
        .badge-premium {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .stat-item {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .stat-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .stat-circle.deposit {
            background: linear-gradient(135deg, #05ab18, #28a745);
            color: white;
        }
        
        .stat-circle.withdrawal {
            background: linear-gradient(135deg, #ff4757, #ff6b7a);
            color: white;
        }
        
        .stat-circle.bonus {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            color: #1a1a1a;
        }
        
        .stat-circle.credit {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
        }
        
        .stat-circle.total-orders {
            background: linear-gradient(135deg, #9c88ff, #8b5fbf);
            color: white;
        }
        
        .stat-circle.active-orders {
            background: linear-gradient(135deg, #00d2ff, #3a8ffe);
            color: white;
        }
        
        .stat-circle.closed-orders {
            background: linear-gradient(135deg, #7209b7, #a663cc);
            color: white;
        }
        
        .stat-circle.profit {
            background: linear-gradient(135deg, #05ab18, #28a745);
            color: white;
        }
        
        .stat-circle.loss {
            background: linear-gradient(135deg, #ff4757, #ff6b7a);
            color: white;
        }
        
        .stat-circle.win-orders {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .stat-circle.lose-orders {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            color: white;
        }
        
        .quick-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .action-btn {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 2px solid #4f8cff;
            color: white;
            padding: 1.2rem 2rem;
            border-radius: 15px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 160px;
        }
        
        .action-btn:hover {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 140, 255, 0.3);
        }
        
        .action-btn i {
            font-size: 1.8rem;
        }
        
        .deposit-action:hover {
            border-color: #05ab18;
            background: linear-gradient(135deg, #05ab18, #28a745);
        }
        
        .withdrawal-action:hover {
            border-color: #ff4757;
            background: linear-gradient(135deg, #ff4757, #ff6b7a);
        }
        
        /* Deposit Interface Styles */
        .balance-display-card {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }
        
        .balance-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }
        
        .deposit-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .deposit-method-card {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }
        
        .deposit-method-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(79, 140, 255, 0.15);
        }
        
        .method-header {
            padding: 2rem;
            border-bottom: 1px solid #353b48;
            display: flex;
            align-items: center;
        }
        
        .method-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-right: 1.5rem;
        }
        
        .bank-icon {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
        }
        
        .crypto-icon {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            color: #1a1a1a;
        }

        .credit-card-icon {
            background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
            color: white;
        }
        
        .method-info h4 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .method-info p {
            color: #b0b8c1;
            margin-bottom: 1rem;
        }
        
        .method-features {
            display: flex;
            gap: 0.5rem;
        }
        
        .feature-badge {
            background: rgba(79, 140, 255, 0.1);
            color: #4f8cff;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .feature-badge.crypto {
            background: rgba(255, 204, 2, 0.1);
            color: #ffcc02;
        }

        .feature-badge.credit-card {
            background: rgba(66, 133, 244, 0.1);
            color: #4285f4;
        }
        
        .method-form {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            color: #e0e0e0;
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: block;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #4f8cff;
            font-size: 1.1rem;
        }
        
        .form-control-modern {
            background: #23272f;
            border: 2px solid #353b48;
            border-radius: 12px;
            color: #e0e0e0;
            padding: 1rem 1rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-control-modern:focus {
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.1);
            background: #2a303a;
        }
        
        .form-select-modern {
            background: #23272f;
            border: 2px solid #353b48;
            border-radius: 12px;
            color: #e0e0e0;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
        }
        
        .form-select-modern:focus {
            border-color: #4f8cff;
            box-shadow: 0 0 0 0.2rem rgba(79, 140, 255, 0.1);
            background: #2a303a;
        }
        
        .form-text {
            color: #b0b8c1;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }
        
        .btn-deposit-submit {
            width: 100%;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .bank-submit {
            background: linear-gradient(135deg, #4f8cff, #6ba3ff);
            color: white;
        }
        
        .bank-submit:hover {
            background: linear-gradient(135deg, #3a7cff, #5b93ff);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 140, 255, 0.3);
        }
        
        .crypto-submit {
            background: linear-gradient(135deg, #ffcc02, #ffd633);
            color: #1a1a1a;
        }
        
        .crypto-submit:hover {
            background: linear-gradient(135deg, #e6b800, #ffcc02);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 204, 2, 0.3);
        }

        .credit-card-submit {
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
        }

        .credit-card-submit:hover {
            background: linear-gradient(135deg, #3367d6, #0f9d58);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(66, 133, 244, 0.3);
        }
        
        .recent-transactions-section {
            background: linear-gradient(135deg, #1c1f26 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 20px;
            overflow: hidden;
        }
        
        .section-header {
            padding: 2rem;
            border-bottom: 1px solid #353b48;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .refresh-btn {
            border: 1px solid #4f8cff;
            color: #4f8cff;
            background: transparent;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s;
        }
        
        .refresh-btn:hover {
            background: #4f8cff;
            color: white;
        }
        
        .transactions-table-container {
            padding: 2rem;
        }
        
        /* Deposit Tabs Styles */
        .transaction-tabs {
            margin-top: 0;
        }
        
        .nav-tabs-dark {
            border-bottom: 1px solid #353b48;
            background: rgba(0,0,0,0.2);
            padding: 0 2rem;
        }
        
        .nav-tabs-dark .nav-link {
            border: none;
            border-radius: 0;
            color: #b0b8c1;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            background: transparent;
        }
        
        .nav-tabs-dark .nav-link:hover {
            border: none;
            color: #4f8cff;
            background: rgba(79, 140, 255, 0.1);
        }
        
        .nav-tabs-dark .nav-link.active {
            border: none;
            background: transparent;
            color: #4f8cff;
            border-bottom: 3px solid #4f8cff;
        }
        
        .nav-tabs-dark .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #4f8cff, #6fa8ff);
        }
        
        .tab-content {
            background: transparent;
        }
        
        .tab-pane .transactions-table-container {
            padding: 2rem;
            border-top: none;
        }
          .table-modern {
            background: transparent !important;
            color: #e0e0e0;
        }
        
        .table-modern tbody,
        .table-modern thead,
        .table-modern tfoot,
        .table-modern tr,
        .table-modern td,
        .table-modern th {
            background: transparent !important;
            color: #e0e0e0 !important;
        }

        .table-modern th {
            color: #b0b8c1 !important;
            font-weight: 600;
            border-bottom: 2px solid #353b48;
            padding: 1rem;
        }

        .table-modern td {
            padding: 1rem;
            border-bottom: 1px solid #353b48;
            color: #e0e0e0 !important;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
        }
        
        .empty-state i {
            color: #4f8cff;
            opacity: 0.3;
        }
        
        .empty-state p {
            color: #e0e0e0 !important;
        }
        
        .empty-state small {
            color: #b0b8c1 !important;
        }
        
        .btn-modern {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Bank Details Card Styles */
        .bank-details-card {
            background: linear-gradient(135deg, #2a303a 0%, #23272f 100%);
            border: 1px solid #4f8cff;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .details-grid::-webkit-scrollbar {
            width: 6px;
        }

        .details-grid::-webkit-scrollbar-track {
            background: #23272f;
            border-radius: 3px;
        }

        .details-grid::-webkit-scrollbar-thumb {
            background: #4f8cff;
            border-radius: 3px;
        }

        /* Responsive: single column on smaller screens */
        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            background: rgba(79, 140, 255, 0.05);
            border-radius: 8px;
        }

        .detail-label {
            color: #b0b8c1;
            font-weight: 500;
        }

        .detail-value {
            color: #e0e0e0;
            font-weight: 600;
        }

        .detail-value-copy {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .copy-btn {
            background: #4f8cff;
            border: none;
            border-radius: 6px;
            padding: 0.3rem 0.6rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .copy-btn:hover {
            background: #3a7cff;
            transform: scale(1.05);
        }

        /* Crypto Address Card Styles */
        .crypto-address-card {
            background: linear-gradient(135deg, #2a303a 0%, #23272f 100%);
            border: 1px solid #ffcc02;
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .address-display {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .address-text-section {
            flex: 1;
        }

        .address-value-copy {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0.5rem 0;
        }

        .address-value {
            background: rgba(255, 204, 2, 0.1);
            padding: 0.8rem;
            border-radius: 8px;
            color: #ffcc02;
            font-family: monospace;
            font-weight: 600;
            word-break: break-all;
            flex: 1;
        }

        .qr-section {
            flex-shrink: 0;
        }

        .qr-code-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-code-image {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            background: white;
            padding: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: rgba(255, 204, 2, 0.1);
            border: 2px dashed #ffcc02;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffcc02;
        }

        .qr-placeholder i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* Modern USDT Deposit Design */
        .usdt-deposit-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, #1e2329 0%, #252a34 100%);
            border-radius: 20px;
            border: 2px solid #ffcc02;
            position: relative;
            overflow: hidden;
        }

        .usdt-deposit-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ffcc02 0%, #ffa500 50%, #ffcc02 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .qr-section-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            text-align: center;
            width: 100%;
        }

        .qr-code-frame {
            background: linear-gradient(145deg, #2c323a 0%, #23272f 100%);
            padding: 1.5rem;
            border-radius: 20px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid #353b48;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .qr-code-frame::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #ffcc02, #ffa500, #ffcc02);
            border-radius: 20px;
            z-index: -1;
            opacity: 0.8;
        }

        .qr-code-image-large {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            background: white;
            padding: 8px;
            box-shadow: 0 4px 20px rgba(255, 204, 2, 0.3);
            display: block;
        }

        .qr-label {
            margin-top: 1rem;
            color: #ffcc02;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .address-section-bottom {
            width: 100%;
            max-width: 500px;
        }

        .address-frame {
            background: linear-gradient(145deg, #2c323a 0%, #23272f 100%);
            border: 1px solid #353b48;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .address-label {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e0e0e0;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .address-input-container {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .address-value-modern {
            background: linear-gradient(135deg, #1a1d23 0%, #252a34 100%);
            border: 2px solid #ffcc02;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            color: #ffcc02;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.9rem;
            word-break: break-all;
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .address-value-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 204, 2, 0.1), transparent);
            animation: scan 3s infinite;
        }

        @keyframes scan {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .copy-btn-modern {
            background: linear-gradient(135deg, #ffcc02 0%, #ffa500 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            color: #1a1d23;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(255, 204, 2, 0.3);
        }

        .copy-btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 204, 2, 0.4);
            background: linear-gradient(135deg, #ffd700 0%, #ffb347 100%);
        }

        .copy-btn-modern:active {
            transform: translateY(0);
        }

        .address-warning {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 152, 0, 0.1);
            border: 1px solid rgba(255, 152, 0, 0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #ff9800;
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
        }

        .address-warning i {
            color: #ff9800;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .usdt-deposit-container {
                padding: 1.5rem;
                gap: 1.5rem;
            }

            .qr-code-image-large {
                width: 160px;
                height: 160px;
            }

            .qr-code-frame {
                padding: 1rem;
            }

            .address-input-container {
                flex-direction: column;
                align-items: stretch;
            }

            .copy-btn-modern {
                justify-content: center;
            }
        }

        /* File Upload Styles */
        .file-upload-area {
            position: relative;
            border: 2px dashed #4f8cff;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: rgba(79, 140, 255, 0.05);
            transition: all 0.3s;
        }

        .file-upload-area:hover {
            border-color: #3a7cff;
            background: rgba(79, 140, 255, 0.1);
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-content {
            pointer-events: none;
        }

        .file-upload-content i {
            font-size: 2rem;
            color: #4f8cff;
            margin-bottom: 0.5rem;
        }

        .file-upload-content p {
            color: #e0e0e0;
            margin-bottom: 0.3rem;
        }

        .file-upload-content small {
            color: #b0b8c1;
        }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <i class="bi bi-bar-chart nav-icon markets-icon active" title="Markets" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-person nav-icon account-icon" title="Account" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-arrow-up-circle nav-icon deposit-icon" title="Deposit" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-arrow-down-circle nav-icon withdrawal-icon" title="Withdrawal" style="font-size:1.2rem; padding:0.3rem;"></i>
    <i class="bi bi-box-arrow-right nav-icon logout-icon" title="Logout" style="font-size:1.2rem; padding:0.3rem;"></i>
</div>

<!-- Custom Context Menu -->
<div id="customContextMenu" class="shadow-lg p-2">
    <button id="goToAssetBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-arrow-right-circle fs-5"></i>
        <span>Go to Asset</span>
    </button>
    <button id="addToFavouriteBtn" data-asset-id="{{ $asset && $asset->id ? $asset->id : '' }}" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star fs-5 text-primary"></i>
        <span>Add to Favourites</span>
    </button>
    <button id="removeFromFavouriteBtn" class="dropdown-item d-flex align-items-center gap-2">
        <i class="bi bi-star-fill fs-5 text-warning"></i>
        <span>Remove from Favourites</span>
    </button>
</div>

<!-- Main Trading Interface -->
<div id="mainContent" class="main-content">
    <div class="row align-items-start" style="margin-top: -40px;">

        <!-- Chart & Tabs -->
        <div class="col-lg-8">
            <div class="panel mb-2" style="margin-left: -30px;">
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
            <div class="right-side-panel mb-3" style="margin-left: -20px;">
                <!-- Asset Search & Filters -->
                <div class="mb-3 d-flex gap-2 align-items-center">
                    <div class="flex-grow-1">
                        <input type="text" id="assetSearch" class="searchbar form-control-sm w-100" placeholder="Search symbols..." style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem;">
                    </div>
                    <div>
                        <select id="categoryFilter" class="filtercategory form-select-sm" style="background: #23272f; border: 1px solid #353b48; color: #e0e0e0; border-radius: 6px; padding: 0.5rem; min-width: 100px;">
                            <option value="">All</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" id="showFavouritesBtn" class="btn btn-sm" title="Show Favourites" style="background:#23272f; color:#4f8cff; border:1px solid #353b48; padding: 0.5rem 0.75rem; border-radius: 6px;">
                            <i class="bi bi-star-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="assets d-grid gap-2" id="assetGrid">
                    <div class="row fw-bold text-secondary mb-2" style="font-size: 1rem;">
                        <div class="col-6">Market</div>
                        <div class="col-3 text-center">Bid</div>
                        <div class="col-3 text-center">Ask</div>
                    </div>
                    @foreach($assetsPrices as $asset)
                        <button type="button" class="row align-items-center asset-button asset-item market-assets mb-2"
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
                                <span class="bid_price text-danger" data-asset-id="{{ $asset->id }}">
                                    {{ number_format($asset->bid_price, 4) }}
                                </span>
                            </div>

                            <div class="col-3 text-center">
                                <span class="ask_price text-success" data-asset-id="{{ $asset->id }}">
                                    {{ number_format($asset->ask_price, 4) }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
                
                <!-- Spacing between assets and order form -->
                <div style="margin-bottom: 2rem;"></div>
                
                <!-- Order Form -->
                <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="orderType" name="type" value="1">
                    <input type="hidden" id="selectedAssetId" name="currency" value="{{ $asset && $asset->id ? $asset->id : '' }}">
                    <input type="hidden" id="selectedAssetSymbol" name="asset_symbol" value="{{ $symbol ?? '' }}">
                    <input type="hidden" id="currentBidPrice" name="bid" value="{{ $asset && $asset->bid_price ? $asset->bid_price : '0' }}">
                    <input type="hidden" id="currentAskPrice" name="ask" value="{{ $asset && $asset->ask_price ? $asset->ask_price : '0' }}">
                    <input type="hidden" id="currentChartSymbol" value="{{ $symbol ?? '' }}">
                    

                    <!-- Amount and Trade Buttons -->
                    <div class="d-flex gap-2 mb-3 align-items-center justify-content-center">
                        <button type="button" id="sellBtn" class="btnorder btn-danger">
                            <span class="d-flex flex-column align-items-center">
                                <strong class="sellPrice" id="displayBidPrice">{{ $asset && $asset->bid_price ? number_format($asset->bid_price, 4) : '0.0000' }}</strong>
                                <span>{{ __('web.sell') }}</span>
                            </span>
                        </button>
                        <div class="input-group" style="max-width: 140px;">
                            <button type="button" class="btnminus" onclick="changeAmount(-0.01)">−</button>
                            <input type="number" id="amount" name="amount" min="0.01" step="0.01" value="0.01" class="amount text-center" readonly/>
                            <button type="button" class="btnplus" onclick="changeAmount(0.01)">+</button>
                        </div>
                        <button type="button" id="buyBtn" class="btnorder btn-success">
                            <span class="d-flex flex-column align-items-center">
                                <strong class="buyPrice" id="displayAskPrice">{{ $asset && $asset->ask_price ? number_format($asset->ask_price, 4) : '0.0000' }}</strong>
                                <span>{{ __('web.buy') }}</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Tabs and Account Summary Row -->
        <div class="details-panel">
            <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-center">
                <ul class="nav nav-tabs border-0 mb-0" id="tradeTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link {{ $tab == 'openedOrder' ? 'active' : '' }}" data-bs-toggle="tab" href="#openOrders" role="tab">Orders</a></li>
                    <li class="nav-item"><a class="nav-link {{ $tab == 'summary' ? 'active' : '' }}" data-bs-toggle="tab" href="#summary" role="tab">Pending</a></li>
                    <li class="nav-item"><a class="nav-link {{ $tab == 'history' ? 'active' : '' }}" data-bs-toggle="tab" href="#history" role="tab">History</a></li>
                </ul>
                <div class="account-summary-inline d-flex flex-wrap">
                    <div><span class="text-secondary">Balance:</span> <span class="text-light">${{ number_format($finance['balance'], 2) }}</span></div>
                    <div><span class="text-secondary">Margin:</span> <span class="text-light">${{ number_format($finance['freeMargin'], 2) }}</span></div>
                    <div><span class="text-secondary">Equity:</span> <span class="text-light">${{ number_format($finance['equity'], 2) }}</span></div>
                    <div><span class="text-secondary">Credit:</span> <span class="text-light">${{ number_format($finance['credit'], 2) }}</span></div>
                    <div><span class="text-secondary">Bonus:</span> <span class="text-light">${{ number_format($finance['bonus'], 2) }}</span></div>
                </div>
            </div>
            <div class="tab-content">
                <!-- Open Orders Tab -->
                <div class="tab-pane fade {{ $tab == 'openedOrder' ? 'show active' : '' }}" id="openOrders" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Entry Price</th>
                                <th>Current Price</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Created at</th>
                                <th>Profit &amp; Loss</th>
                                <th>Actions</th>
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
                                        <td class="pnl active_pnl {{ $order->pnl < 0 ? 'text-danger' : 'text-success' }}" data-order-id="{{ $order->id }}">
                                            ${{ number_format($order->pnl, 2) }}
                                        </td>
                                        <td>
                                            <form action="{{ route('order.close', ['id'=>$order->id]) }}" class="d-inline" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to close this order?')">
                                                    Close
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                Edit
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
                <div class="tab-pane fade {{ $tab == 'history' ? 'show active' : '' }}" id="history" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Instrument</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Open Price</th>
                                <th>Close Price</th>
                                <th>Stop Loss</th>
                                <th>Take Profit</th>
                                <th>Opened</th>
                                <th>Closed</th>
                                <th>Profit &amp; Loss</th>
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
                                        <td class="pnl {{ $order->pnl < 0 ? 'text-danger' : 'text-success' }}">
                                            ${{ number_format($order->pnl, 2) }}
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
                <div class="tab-pane fade {{ $tab == 'summary' ? 'show active' : '' }}" id="summary" role="tabpanel">
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
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this pending order?')">
                                                    Cancel
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-warning btn-sm ms-1" onclick="editOrder({{ $order->id }}, '{{ $order->s_l }}', '{{ $order->s_p }}')" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                                                Edit
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
                    <h1>Account Dashboard</h1>
                    <p>Overview of your trading account and statistics</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Trading</span>
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
                    <h3>${{ number_format($finance['balance'], 2) }}</h3>
                    <p>Account Balance</p>
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
                    <h3>${{ number_format($finance['equity'], 2) }}</h3>
                    <p>Total Equity</p>
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
                    <h3>${{ number_format($finance['freeMargin'], 2) }}</h3>
                    <p>Free Margin</p>
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
                    <h3 class="{{ $finance['currentPL'] >= 0 ? 'text-success' : 'text-danger' }}">${{ number_format($finance['currentPL'], 2) }}</h3>
                    <p>Current P&L</p>
                </div>
                <div class="stat-trend {{ $finance['currentPL'] >= 0 ? 'positive' : 'negative' }}">
                    <i class="bi bi-{{ $finance['currentPL'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                </div>
            </div>
        </div>

        <!-- Account Info Cards -->
        <div class="info-cards-grid">
            <div class="info-card personal-info">
                <div class="card-header">
                    <h3><i class="bi bi-person-lines-fill"></i> Personal Information</h3>
                    <button class="btn btn-sm btn-outline-primary edit-profile-btn" id="editProfileBtn">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                </div>
                <div class="card-content">
                    <form id="profileForm" action="{{ route('client.update.profile') }}" method="POST" style="display: none;">
                        @csrf
                        @method('PUT')
                        <div class="info-row">
                            <span class="label">Name</span>
                            <input type="text" name="name" class="form-control-modern" value="{{ auth()->guard('client')->user()->name }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">Email</span>
                            <input type="email" name="email" class="form-control-modern" value="{{ auth()->guard('client')->user()->email }}" required>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone</span>
                            <input type="text" name="phone" class="form-control-modern" value="{{ auth()->guard('client')->user()->phone ?? '' }}" placeholder="Enter your phone number">
                        </div>
                        <div class="info-row">
                            <span class="label">Country</span>
                            <input type="text" name="country" class="form-control-modern" value="{{ auth()->guard('client')->user()->country ?? '' }}" placeholder="Enter your country">
                        </div>
                        <div class="info-row">
                            <span class="label">Account Type</span>
                            <span class="value badge-premium">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                            <small class="text-muted">Account type cannot be changed</small>
                        </div>
                        <div class="form-actions mt-3">
                            <button type="submit" class="btn btn-gradient-primary me-2">
                                <i class="bi bi-check-lg"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary cancel-edit-btn">
                                <i class="bi bi-x-lg"></i> Cancel
                            </button>
                        </div>
                    </form>
                    
                    <div id="profileDisplay" class="profile-display">
                        <div class="info-row">
                            <span class="label">Name</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Email</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Phone</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Country</span>
                            <span class="value enhanced">{{ auth()->guard('client')->user()->country ?? 'Not provided' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Account Type</span>
                            <span class="value badge-premium enhanced">{{ auth()->guard('client')->user()->account_type ?? 'Standard' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-card trading-stats">
                <div class="card-header">
                    <h3><i class="bi bi-graph-up-arrow"></i> Trading Statistics</h3>
                </div>
                <div class="card-content">
                    <div class="stats-grid-3x2">
                        <div class="stat-item">
                            <div class="stat-circle total-orders">
                                <span>{{ number_format($finance['totalOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Total Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle active-orders">
                                <span>{{ number_format($finance['activeOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Active Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle closed-orders">
                                <span>{{ number_format($finance['closedOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Closed Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle win-orders">
                                <span>{{ number_format($finance['winOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Win Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle lose-orders">
                                <span>{{ number_format($finance['loseOrders'] ?? 0, 0) }}</span>
                            </div>
                            <p>Lose Orders</p>
                        </div>
                        <div class="stat-item">
                            <div class="stat-circle total-pnl {{ ($finance['totalPnL'] ?? 0) >= 0 ? 'profit' : 'loss' }}">
                                <span>${{ number_format($finance['totalPnL'] ?? 0, 2) }}</span>
                            </div>
                            <p>Total PnL</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="action-btn deposit-action" onclick="showDepositInterface()">
                <i class="bi bi-arrow-up-circle"></i>
                <span>Make Deposit</span>
            </button>
            <button class="action-btn withdrawal-action" onclick="showWithdrawalInterface()">
                <i class="bi bi-arrow-down-circle"></i>
                <span>Withdraw Funds</span>
            </button>
            <button class="action-btn trading-action" onclick="showMainContent()">
                <i class="bi bi-graph-up"></i>
                <span>Start Trading</span>
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
                    <h1>Deposit Funds</h1>
                    <p>Fund your account quickly and securely</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-modern btn-secondary back-to-trading-btn">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Trading</span>
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
                        <h6 class="text-white mb-1">Current Balance</h6>
                        <h3 class="text-white mb-0">${{ number_format($finance['balance'], 2) }}</h3>
                    </div>
                </div>
                <div class="text-end">
                    <small class="text-white">Available for withdrawal</small>
                    <div class="text-success font-weight-bold">${{ number_format($finance['balance'], 2) }}</div>
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
                        <h4>Bank Transfer</h4>
                        <p>Secure bank-to-bank transfer</p>
                        <div class="method-features">
                            <span class="feature-badge">No Fees</span>
                            <span class="feature-badge">1-3 Business Days</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="bankDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="bank_transfer">
                        
                        <div class="form-group">
                            <label for="bank_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="bank_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="country_select" class="form-label">Select Country</label>
                            <select name="country" id="country_select" class="form-select-modern" required>
                                <option value="">Choose your country...</option>
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
                        
                        <div class="form-group">
                            <label for="bank_select" class="form-label">Select Bank</label>
                            <select name="bank_id" id="bank_select" class="form-select-modern" required disabled>
                                <option value="">First select a country...</option>
                            </select>
                        </div>
                        
                        <!-- Bank Details Display -->
                        <div id="bankDetailsDisplay" class="bank-details-card" style="display: none;">
                            <h5 class="text-white mb-3"><i class="bi bi-bank me-2"></i>Bank Transfer Details</h5>
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Bank Name:</span>
                                    <span class="detail-value" id="displayBankName">-</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Beneficiary Name:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAccountName">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAccountName').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Account Number:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAccountNumber">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAccountNumber').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="ibanRow" style="display: none;">
                                    <span class="detail-label">IBAN:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayIban">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayIban').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="swiftCodeRow" style="display: none;">
                                    <span class="detail-label">SWIFT Code:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displaySwiftCode">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displaySwiftCode').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="abaRoutingRow" style="display: none;">
                                    <span class="detail-label">ABA Routing Number:</span>
                                    <div class="detail-value-copy">
                                        <span class="detail-value" id="displayAbaRouting">-</span>
                                        <button type="button" class="copy-btn" onclick="copyToClipboard(document.getElementById('displayAbaRouting').textContent)">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="detail-item" id="beneficiaryAddressRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Address:</span>
                                    <span class="detail-value" id="displayBeneficiaryAddress">-</span>
                                </div>
                                <div class="detail-item" id="beneficiaryCountryRow" style="display: none;">
                                    <span class="detail-label">Beneficiary Country:</span>
                                    <span class="detail-value" id="displayBeneficiaryCountry">-</span>
                                </div>
                                <div class="detail-item" id="bankAddressRow" style="display: none;">
                                    <span class="detail-label">Bank Address:</span>
                                    <span class="detail-value" id="displayBankAddress">-</span>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Please use these details to make your bank transfer and upload the receipt below.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bank_receipt" class="form-label">Upload Receipt</label>
                            <div class="file-upload-area">
                                <input type="file" name="receipt" id="bank_receipt" class="file-input" 
                                       accept=".pdf,.png,.jpg,.jpeg" required>
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
                        <h4>Cryptocurrency</h4>
                        <p>Fast and secure crypto deposits</p>
                        <div class="method-features">
                            <span class="feature-badge crypto">Low Fees</span>
                            <span class="feature-badge crypto">Instant</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="cryptoDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="cryptocurrency">
                        
                        <div class="form-group">
                            <label for="crypto_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="crypto_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="crypto_type_select" class="form-label">Cryptocurrency</label>
                            <select name="crypto_type" id="crypto_type_select" class="form-select-modern" required>
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
                                        $usdtAddress = is_string($client->usdt) ? $client->usdt : (is_array($client->usdt) ? reset($client->usdt) : null);
                                    }
                                    // If client usdt is null/empty, get USDT from the pipeline this client belongs to
                                    elseif (!empty($client->pipeline_id)) {
                                        // Get the pipeline by pipeline_id
                                        $pipeline = \App\Models\Pipeline::find($client->pipeline_id);
                                        
                                        if ($pipeline && !empty($pipeline->usdt)) {
                                            $pipelineUsdt = $pipeline->usdt;
                                            
                                            // Handle different data types for pipeline usdt
                                            if (is_array($pipelineUsdt) && !empty($pipelineUsdt)) {
                                                $usdtAddress = reset($pipelineUsdt);
                                            } elseif (is_string($pipelineUsdt) && trim($pipelineUsdt) !== '') {
                                                $usdtAddress = trim($pipelineUsdt);
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
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($usdtAddress) }}&format=png&bgcolor=FFFFFF&color=000000"
                                                     alt="USDT Address QR Code"
                                                     class="qr-code-image-large">
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
                                                <div class="address-input-container">
                                                    <div class="address-value-modern" id="usdtAddress">{{ $usdtAddress }}</div>
                                                    <input type="hidden" id="usdtAddressValue" value="{{ $usdtAddress }}">
                                                    <button type="button" class="copy-btn-modern" onclick="copyToClipboard(document.getElementById('usdtAddress').textContent)">
                                                        <i class="bi bi-copy"></i>
                                                        <span>Copy</span>
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
                                <input type="file" name="receipt" id="crypto_receipt" class="file-input" 
                                       accept=".pdf,.png,.jpg,.jpeg" required>
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
                        <h4>Credit Card</h4>
                        <p>Fast and secure card payments</p>
                        <div class="method-features">
                            <span class="feature-badge credit-card">Instant</span>
                            <span class="feature-badge credit-card">Secure</span>
                        </div>
                    </div>
                </div>
                
                <div class="method-form">
                    <form id="creditCardDepositForm" action="{{ route('client.deposit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="payment_method" value="credit_card">
                        
                        <div class="form-group">
                            <label for="credit_card_deposit_amount" class="form-label">Amount (USD)</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" name="amount" id="credit_card_deposit_amount" class="form-control-modern" 
                                       step="0.01" min="10" placeholder="10.00" required>
                            </div>
                            <small class="form-text">Minimum deposit: $10</small>
                        </div>

                        <!-- Credit Card Details -->
                        <div class="credit-card-details">
                            <h6 class="text-white mb-3"><i class="bi bi-credit-card me-2"></i>Card Information</h6>
                            
                            <div class="form-group">
                                <label for="card_number" class="form-label">Card Number</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <input type="text" name="card_number" id="card_number" class="form-control-modern" 
                                           placeholder="1234 5678 9012 3456" maxlength="19" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="card_expiry" class="form-label">Expiry Date</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-calendar"></i>
                                            <input type="text" name="card_expiry" id="card_expiry" class="form-control-modern" 
                                                   placeholder="MM/YY" maxlength="5" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <div class="input-with-icon">
                                            <i class="bi bi-shield-lock"></i>
                                            <input type="text" name="card_cvv" id="card_cvv" class="form-control-modern" 
                                                   placeholder="123" maxlength="4" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="card_holder_name" class="form-label">Cardholder Name</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="card_holder_name" id="card_holder_name" class="form-control-modern" 
                                           placeholder="John Doe" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="billing_address" class="form-label">Billing Address</label>
                                <div class="input-with-icon">
                                    <i class="bi bi-geo-alt"></i>
                                    <textarea name="billing_address" id="billing_address" class="form-control-modern" 
                                              rows="3" placeholder="123 Main St, City, State, ZIP" required></textarea>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="bi bi-shield-check me-2"></i>
                                Your card information is encrypted and secure. We use industry-standard security measures.
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
                <h3><i class="bi bi-clock-history me-2"></i>Recent Deposits</h3>
                <button class="btn btn-outline-primary btn-sm refresh-btn">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
            
            <!-- Deposit Tabs -->
            <div class="transaction-tabs">
                <nav class="nav nav-tabs nav-tabs-dark" id="depositTabs" role="tablist">
                    <button class="nav-link active" id="all-deposits-tab" data-bs-toggle="tab" data-bs-target="#all-deposits" type="button" role="tab" aria-controls="all-deposits" aria-selected="true">
                        <i class="bi bi-list-ul me-1"></i>All
                    </button>
                    <button class="nav-link" id="pending-deposits-tab" data-bs-toggle="tab" data-bs-target="#pending-deposits" type="button" role="tab" aria-controls="pending-deposits" aria-selected="false">
                        <i class="bi bi-clock me-1"></i>Pending
                    </button>
                    <button class="nav-link" id="accepted-deposits-tab" data-bs-toggle="tab" data-bs-target="#accepted-deposits" type="button" role="tab" aria-controls="accepted-deposits" aria-selected="false">
                        <i class="bi bi-check-circle me-1"></i>Accepted
                    </button>
                    <button class="nav-link" id="rejected-deposits-tab" data-bs-toggle="tab" data-bs-target="#rejected-deposits" type="button" role="tab" aria-controls="rejected-deposits" aria-selected="false">
                        <i class="bi bi-x-circle me-1"></i>Rejected
                    </button>
                </nav>
                
                <div class="tab-content" id="depositTabContent">
                    <!-- All Deposits Tab -->
                    <div class="tab-pane fade show active" id="all-deposits" role="tabpanel" aria-labelledby="all-deposits-tab">
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
                                                    USDT
                                                @elseif($deposit->credit_card_details)
                                                    Credit Card
                                                @else
                                                    Bank Transfer
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
                                                    USDT
                                                @elseif($deposit->credit_card_details)
                                                    Credit Card
                                                @else
                                                    Bank Transfer
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ ucfirst($deposit->status ?? 'Pending') }}</span>
                                            </td>
                                            <td class="text-light">{{ $deposit->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                    <p class="text-light">No pending deposits found</p>
                                                    <small class="text-light opacity-75">Pending deposits will appear here</small>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="text-white mb-2">
                                <i class="bi bi-arrow-down-circle me-3" style="color: #ffcc02;"></i>
                                Withdrawal Management
                            </h2>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-gradient-primary new-withdrawal-btn" data-bs-toggle="modal" data-bs-target="#newWithdrawalModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                New Withdrawal
                            </button>
                            <button class="btn btn-gradient-primary back-to-trading-btn">
                                <i class="bi bi-arrow-left me-2"></i>
                                Back to Trading
                            </button>
                        </div>
                    </div>

                    <!-- Balance Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="summary-card">
                                <div class="summary-card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="summary-icon balance-icon me-3">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white mb-1">Available Balance</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['balance'], 2) }}</h4>
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
                                            <h6 class="text-white mb-1">Pending Withdrawals</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['pendingWithdrawal'], 2) }}</h4>
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
                                            <h6 class="text-white mb-1">Total Withdrawn</h6>
                                            <h4 class="text-white mb-0">${{ number_format($finance['totalWithdrawal'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Withdrawals Section -->
                    <div class="recent-transactions-section">
                        <div class="section-header">
                            <h3><i class="bi bi-clock-history me-2"></i>Recent Withdrawals</h3>
                            <button class="btn btn-outline-primary btn-sm refresh-btn">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                        
                        <!-- Withdrawal Tabs -->
                        <div class="transaction-tabs">
                            <nav class="nav nav-tabs nav-tabs-dark" id="withdrawalTabs" role="tablist">
                                <button class="nav-link active" id="all-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#all-withdrawals" type="button" role="tab" aria-controls="all-withdrawals" aria-selected="true">
                                    <i class="bi bi-list-ul me-1"></i>All
                                </button>
                                <button class="nav-link" id="pending-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#pending-withdrawals" type="button" role="tab" aria-controls="pending-withdrawals" aria-selected="false">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </button>
                                <button class="nav-link" id="accepted-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#accepted-withdrawals" type="button" role="tab" aria-controls="accepted-withdrawals" aria-selected="false">
                                    <i class="bi bi-check-circle me-1"></i>Accepted
                                </button>
                                <button class="nav-link" id="rejected-withdrawals-tab" data-bs-toggle="tab" data-bs-target="#rejected-withdrawals" type="button" role="tab" aria-controls="rejected-withdrawals" aria-selected="false">
                                    <i class="bi bi-x-circle me-1"></i>Rejected
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
                                                                USDT
                                                            @else
                                                                Bank Transfer
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning">{{ ucfirst($withdrawal->status ?? 'Pending') }}</span>
                                                        </td>
                                                        <td class="text-light">{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <i class="bi bi-clock display-4 mb-3 text-light"></i>
                                                                <p class="text-light">No pending withdrawals found</p>
                                                                <small class="text-light opacity-75">Pending withdrawals will appear here</small>
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

<!-- New Withdrawal Modal -->
<div class="modal fade" id="newWithdrawalModal" tabindex="-1" aria-labelledby="newWithdrawalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h4 class="modal-title text-white" id="newWithdrawalModalLabel">
                    <i class="bi bi-arrow-down-circle me-2" style="color: #ffcc02;"></i>
                    Request New Withdrawal
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Withdrawal Method Tabs -->
                <ul class="nav nav-pills mb-4" id="withdrawalMethodTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="font-size: 1.05rem;" id="bank-tab" data-bs-toggle="pill" data-bs-target="#bank-transfer" type="button" role="tab">
                            <i class="bi bi-bank me-2"></i>Bank Transfer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" style="font-size: 1.05rem;" id="crypto-tab" data-bs-toggle="pill" data-bs-target="#cryptocurrency" type="button" role="tab">
                            <i class="bi bi-currency-bitcoin me-2"></i>Cryptocurrency
                        </button>
                    </li>
                </ul>

                <!-- Available Balance Alert -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong style="font-size: 1.1rem;">Available Balance:</strong> <span style="font-size: 1.1rem;">${{ number_format($finance['balance'], 2) }}</span>
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
                                        <label for="bank_amount" class="form-label text-white" style="font-size: 1.05rem;">Amount (USD)</label>
                                        <input type="number" id="bank_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;"
                                               min="1" max="{{ $finance['balance'] }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">Minimum: $1.00 | Maximum: ${{ number_format($finance['balance'], 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_holder" class="form-label text-white" style="font-size: 1.05rem;">Account Holder Name</label>
                                        <input type="text" name="account_holder" id="account_holder" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label text-white" style="font-size: 1.05rem;">Bank Name</label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="account_number" class="form-label text-white" style="font-size: 1.05rem;">Account Number</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="swift_code" class="form-label text-white" style="font-size: 1.05rem;">SWIFT/Routing Code</label>
                                <input type="text" name="swift_code" id="swift_code" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-gradient-danger" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>Submit Withdrawal Request
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
                                        <label for="crypto_amount" class="form-label text-white" style="font-size: 1.05rem;">Amount (USD)</label>
                                        <input type="number" id="crypto_amount" name="amount" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;"
                                               min="1" max="{{ $finance['balance'] }}" step="0.01" required>
                                        <small class="text-white" style="font-size: 0.95rem;">Minimum: $1.00 | Maximum: ${{ number_format($finance['balance'], 2) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="crypto_type_withdrawal" class="form-label text-white" style="font-size: 1.05rem;">Cryptocurrency</label>
                                        <select name="crypto_type" id="crypto_type_withdrawal" class="form-select bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                            <option value="">Select Cryptocurrency</option>
                                            <option value="BTC">Bitcoin (BTC)</option>
                                            <option value="ETH">Ethereum (ETH)</option>
                                            <option value="USDT">Tether (USDT)</option>
                                            <option value="LTC">Litecoin (LTC)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="wallet_address" class="form-label text-white" style="font-size: 1.05rem;">Wallet Address</label>
                                <input type="text" name="wallet_address" id="wallet_address" class="form-control bg-dark text-white border-secondary" style="font-size: 1.05rem;" required>
                                <small class="text-white" style="font-size: 0.95rem;">Enter your cryptocurrency wallet address. Double-check this address as transactions cannot be reversed.</small>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" style="font-size: 1.05rem;" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-gradient-warning" style="font-size: 1.05rem;">
                                    <i class="bi bi-send me-2"></i>Submit Withdrawal Request
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
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="editOrderModalLabel">
                    <i class="bi bi-pencil-square me-2" style="color: #4f8cff;"></i>
                    Edit Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editOrderForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editOrderId" name="order_id">
                    
                    <div class="mb-3">
                        <label for="edit_stop_loss" class="form-label text-white">Stop Loss</label>
                        <input type="number" id="edit_stop_loss" name="stop_loss" class="form-control bg-dark text-white border-secondary" 
                               step="0.00001" placeholder="Enter stop loss price">
                        <small class="text-muted">Optional: Set a stop loss price to limit losses</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_take_profit" class="form-label text-white">Take Profit</label>
                        <input type="number" id="edit_take_profit" name="take_profit" class="form-control bg-dark text-white border-secondary" 
                               step="0.00001" placeholder="Enter take profit price">
                        <small class="text-muted">Optional: Set a take profit price to secure gains</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="bi bi-check-circle me-2"></i>Update Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden logout form -->
<form id="logoutForm" action="{{ route('client.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('assets/plugins/material-date-range-picker/dist/duDatepicker.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/form-date-time-pickers.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/main_tp.min.js?v1.599') }}"></script>
<script src="{{ url('assets/js/webtrader.js') }}"></script>

<!-- Add CSRF token and routes for JavaScript -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Global variables for JavaScript
    var client_id = {{ auth()->guard('client')->user()->id }};
    var assetId = {{ $asset && $asset->id ? $asset->id : 'null' }};
    
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

<script>
    // Amount change function
    function changeAmount(amount) {
        const input = document.getElementById('amount');
        let current = parseFloat(input.value) || 0;
        current = Math.max(0.01, (current + amount).toFixed(2));
        input.value = current;
    }

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function () {
            showNotification('Copied to clipboard!', 'success');
        }).catch(function () {
            showNotification('Failed to copy to clipboard', 'error');
        });
    }

    // Show notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show notification-toast`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;

        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Interface navigation functions
    function showMainContent() {
        document.getElementById('mainContent').style.display = 'block';
        document.getElementById('accountInterface').style.display = 'none';
        document.getElementById('depositInterface').style.display = 'none';
        document.getElementById('withdrawalInterface').style.display = 'none';
    }

    function showAccountInterface() {
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('accountInterface').style.display = 'block';
        document.getElementById('depositInterface').style.display = 'none';
        document.getElementById('withdrawalInterface').style.display = 'none';
    }

    function showDepositInterface() {
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('accountInterface').style.display = 'none';
        document.getElementById('depositInterface').style.display = 'block';
        document.getElementById('withdrawalInterface').style.display = 'none';
    }

    function showWithdrawalInterface() {
        document.getElementById('mainContent').style.display = 'none';
        document.getElementById('accountInterface').style.display = 'none';
        document.getElementById('depositInterface').style.display = 'none';
        document.getElementById('withdrawalInterface').style.display = 'block';
    }

    // Add event listeners when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Country and Bank selection functionality
        const countrySelect = document.getElementById('country_select');
        const bankSelect = document.getElementById('bank_select');
        const bankDetailsDisplay = document.getElementById('bankDetailsDisplay');
        
        // Banks data for filtering
        const banksData = @json($banks ?? []);
        
        if (countrySelect && bankSelect) {
            countrySelect.addEventListener('change', function() {
                const selectedCountry = this.value;
                
                // Clear and reset bank select
                bankSelect.innerHTML = '<option value="">Choose a bank...</option>';
                bankSelect.disabled = !selectedCountry;
                bankDetailsDisplay.style.display = 'none';
                
                if (selectedCountry) {
                    // Filter banks by selected country
                    const countryBanks = banksData.filter(bank => bank.country === selectedCountry);
                    
                    countryBanks.forEach(bank => {
                        const option = document.createElement('option');
                        option.value = bank.id;
                        option.textContent = bank.bank_name || bank.name;
                        
                        // Set all data attributes for bank details
                        option.setAttribute('data-bank-name', bank.bank_name || bank.name || '');
                        option.setAttribute('data-account-name', bank.account_name || bank.beneficiary_name || '');
                        option.setAttribute('data-account-number', bank.account_number || '');
                        option.setAttribute('data-iban', bank.iban || '');
                        option.setAttribute('data-swift-code', bank.swift_code || '');
                        option.setAttribute('data-aba-routing', bank.aba_routing_number || bank.routing_number || '');
                        option.setAttribute('data-beneficiary-address', bank.beneficiary_address || bank.address || '');
                        option.setAttribute('data-beneficiary-country', bank.beneficiary_country || bank.country || '');
                        option.setAttribute('data-bank-address', bank.bank_address || bank.address || '');
                        
                        bankSelect.appendChild(option);
                    });
                }
            });
            
            bankSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                
                if (selectedOption.value) {
                    // Get bank details from data attributes
                    const bankName = selectedOption.getAttribute('data-bank-name');
                    const accountName = selectedOption.getAttribute('data-account-name');
                    const accountNumber = selectedOption.getAttribute('data-account-number');
                    const iban = selectedOption.getAttribute('data-iban');
                    const swiftCode = selectedOption.getAttribute('data-swift-code');
                    const abaRouting = selectedOption.getAttribute('data-aba-routing');
                    const beneficiaryAddress = selectedOption.getAttribute('data-beneficiary-address');
                    const beneficiaryCountry = selectedOption.getAttribute('data-beneficiary-country');
                    const bankAddress = selectedOption.getAttribute('data-bank-address');
                    
                    // Update display elements
                    document.getElementById('displayBankName').textContent = bankName || 'N/A';
                    document.getElementById('displayAccountName').textContent = accountName || 'N/A';
                    document.getElementById('displayAccountNumber').textContent = accountNumber || 'N/A';
                    document.getElementById('displayIban').textContent = iban || 'N/A';
                    document.getElementById('displaySwiftCode').textContent = swiftCode || 'N/A';
                    document.getElementById('displayAbaRouting').textContent = abaRouting || 'N/A';
                    document.getElementById('displayBeneficiaryAddress').textContent = beneficiaryAddress || 'N/A';
                    document.getElementById('displayBeneficiaryCountry').textContent = beneficiaryCountry || 'N/A';
                    document.getElementById('displayBankAddress').textContent = bankAddress || 'N/A';
                    
                    // Show/hide rows based on data availability
                    const toggleRow = (rowId, value) => {
                        const row = document.getElementById(rowId);
                        if (row) {
                            row.style.display = (value && value.trim() !== '' && value !== 'N/A') ? 'flex' : 'none';
                        }
                    };
                    
                    toggleRow('ibanRow', iban);
                    toggleRow('swiftCodeRow', swiftCode);
                    toggleRow('abaRoutingRow', abaRouting);
                    toggleRow('beneficiaryAddressRow', beneficiaryAddress);
                    toggleRow('beneficiaryCountryRow', beneficiaryCountry);
                    toggleRow('bankAddressRow', bankAddress);
                    
                    // Show the bank details card
                    bankDetailsDisplay.style.display = 'block';
                } else {
                    // Hide the bank details card
                    bankDetailsDisplay.style.display = 'none';
                }
            });
        }
        
        // Crypto selection functionality
        const cryptoSelect = document.getElementById('crypto_type_select');
        const usdtAddressDisplay = document.getElementById('usdtAddressDisplay');
        
        if (cryptoSelect) {
            cryptoSelect.addEventListener('change', function() {
                if (this.value === 'USDT') {
                    usdtAddressDisplay.style.display = 'block';
                } else {
                    usdtAddressDisplay.style.display = 'none';
                }
            });
        }

        // File upload preview functionality
        const fileInputs = document.querySelectorAll('.file-input');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const uploadContent = this.nextElementSibling;
                
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    
                    uploadContent.innerHTML = `
                        <i class="bi bi-file-earmark-check" style="color: #28a745;"></i>
                        <p style="color: #28a745;">File Selected: ${fileName}</p>
                        <small>Size: ${fileSize} MB</small>
                    `;
                } else {
                    // Reset to original content
                    uploadContent.innerHTML = `
                        <i class="bi bi-cloud-upload"></i>
                        <p>Click to upload or drag and drop</p>
                        <small>PDF, PNG, JPG, JPEG (Max 5MB)</small>
                    `;
                }
            });
        });

        // Sidebar navigation
        document.querySelector('.markets-icon').addEventListener('click', showMainContent);
        document.querySelector('.account-icon').addEventListener('click', showAccountInterface);
        document.querySelector('.deposit-icon').addEventListener('click', showDepositInterface);
        document.querySelector('.withdrawal-icon').addEventListener('click', showWithdrawalInterface);

        // Back to trading buttons
        document.querySelectorAll('.back-to-trading-btn').forEach(btn => {
            btn.addEventListener('click', showMainContent);
        });

        // Logout functionality
        document.querySelector('.logout-icon').addEventListener('click', function() {
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logoutForm').submit();
            }
        });

        // Update sidebar active states
        function updateSidebarActive(activeClass) {
            document.querySelectorAll('.nav-icon').forEach(icon => {
                icon.classList.remove('active');
            });
            document.querySelector(activeClass).classList.add('active');
        }

        document.querySelector('.markets-icon').addEventListener('click', () => updateSidebarActive('.markets-icon'));
        document.querySelector('.account-icon').addEventListener('click', () => updateSidebarActive('.account-icon'));
        document.querySelector('.deposit-icon').addEventListener('click', () => updateSidebarActive('.deposit-icon'));
        document.querySelector('.withdrawal-icon').addEventListener('click', () => updateSidebarActive('.withdrawal-icon'));

        // Asset search functionality
        document.getElementById('assetSearch').addEventListener('input', function() {
            console.log('Search input changed:', this.value);
            filterAssets();
        });

        // Category filter functionality
        document.getElementById('categoryFilter').addEventListener('change', function() {
            console.log('Category changed:', this.value);
            filterAssets();
        });

        // Also listen for keyup events on search for better responsiveness
        document.getElementById('assetSearch').addEventListener('keyup', function() {
            console.log('Search keyup:', this.value);
            filterAssets();
        });

        // Favorites functionality
        document.getElementById('showFavouritesBtn').addEventListener('click', function() {
            const btn = this;
            if (btn.classList.contains('active')) {
                btn.classList.remove('active');
                btn.style.backgroundColor = '#23272f';
                showAllAssets();
            } else {
                btn.classList.add('active');
                btn.style.backgroundColor = '#4f8cff';
                showOnlyFavorites();
            }
        });

        // Context menu for favorites
        document.getElementById('addToFavouriteBtn').addEventListener('click', function() {
            const assetId = this.getAttribute('data-asset-id');
            toggleFavorite(assetId, 'add');
        });

        document.getElementById('removeFromFavouriteBtn').addEventListener('click', function() {
            const assetId = this.getAttribute('data-asset-id');
            toggleFavorite(assetId, 'remove');
        });

        // Buy and Sell button functionality
        document.getElementById('buyBtn').addEventListener('click', function() {
            const assetId = document.getElementById('selectedAssetId').value;
            if (!assetId || assetId === 'null' || assetId === '') {
                showNotification('Please select a valid asset first', 'error');
                return;
            }
            document.getElementById('orderType').value = '1'; // 1 = buy
            document.getElementById('orderForm').submit();
        });

        document.getElementById('sellBtn').addEventListener('click', function() {
            const assetId = document.getElementById('selectedAssetId').value;
            if (!assetId || assetId === 'null' || assetId === '') {
                showNotification('Please select a valid asset first', 'error');
                return;
            }
            document.getElementById('orderType').value = '2'; // 2 = sell
            document.getElementById('orderForm').submit();
        });
    });

    // Filter assets function
    function filterAssets() {
        const searchTerm = document.getElementById('assetSearch').value.toLowerCase().trim();
        const selectedCategory = document.getElementById('categoryFilter').value.trim();
        const assetButtons = document.querySelectorAll('.asset-button');

        console.log('Filtering assets - Search:', searchTerm, 'Category:', selectedCategory);

        let visibleCount = 0;
        let hiddenCount = 0;

        assetButtons.forEach((button, index) => {
            const assetName = button.getAttribute('data-name') || '';
            const assetSymbol = button.getAttribute('data-symbol') || '';
            const assetCategory = button.getAttribute('data-category') || '';

            // Convert to lowercase for comparison
            const nameMatch = assetName.toLowerCase().includes(searchTerm);
            const symbolMatch = assetSymbol.toLowerCase().includes(searchTerm);
            const categoryMatch = selectedCategory === '' || assetCategory === selectedCategory;

            // Search matches if search term is empty OR name/symbol contains search term
            const matchesSearch = searchTerm === '' || nameMatch || symbolMatch;
            const shouldShow = matchesSearch && categoryMatch;
            
            // Use multiple methods to ensure visibility changes work
            if (shouldShow) {
                // Show the asset - restore Bootstrap row display
                button.style.display = 'flex';
                button.style.visibility = 'visible';
                button.classList.remove('d-none', 'hidden');
                button.classList.add('d-flex');
                visibleCount++;
            } else {
                // Hide the asset
                button.style.display = 'none';
                button.style.visibility = 'hidden';
                button.classList.add('d-none', 'hidden');
                button.classList.remove('d-flex');
                hiddenCount++;
            }
        });

        console.log(`Filter result - Visible: ${visibleCount}, Hidden: ${hiddenCount}`);
    }

    // Show all assets
    function showAllAssets() {
        console.log('Showing all assets');
        const assetButtons = document.querySelectorAll('.asset-button');
        
        assetButtons.forEach(button => {
            // Use multiple methods to ensure visibility - restore Bootstrap row display
            button.style.display = 'flex';
            button.style.visibility = 'visible';
            button.classList.remove('d-none', 'hidden');
            button.classList.add('d-flex');
        });
        
        // Reset filters
        document.getElementById('assetSearch').value = '';
        document.getElementById('categoryFilter').value = '';
        
        console.log(`All ${assetButtons.length} assets should now be visible`);
    }

    // Show only favorite assets
    function showOnlyFavorites() {
        console.log('Showing only favorites');
        const assetButtons = document.querySelectorAll('.asset-button');
        let favoriteCount = 0;
        
        assetButtons.forEach(button => {
            const hasStar = button.querySelector('.star-icon');
            if (hasStar) {
                // Show favorite asset - restore Bootstrap row display
                button.style.display = 'flex';
                button.style.visibility = 'visible';
                button.classList.remove('d-none', 'hidden');
                button.classList.add('d-flex');
                favoriteCount++;
            } else {
                // Hide non-favorite asset
                button.style.display = 'none';
                button.style.visibility = 'hidden';
                button.classList.add('d-none', 'hidden');
                button.classList.remove('d-flex');
            }
        });
        
        console.log(`Showing ${favoriteCount} favorite assets out of ${assetButtons.length} total`);
    }

    // Toggle favorite function
    function toggleFavorite(assetId, action) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const toggleRoute = document.body.getAttribute('data-toggle-favourite-route');

        fetch(toggleRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                asset_id: assetId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the UI
                const assetButton = document.querySelector(`[data-asset-id="${assetId}"]`);
                if (assetButton) {
                    const starIcon = assetButton.querySelector('.star-icon');
                    if (action === 'add' && !starIcon) {
                        // Add star icon
                        const nameSpan = assetButton.querySelector('.name');
                        nameSpan.innerHTML += '<span class="star-icon" style="color: gold; margin-left: 6px;">★</span>';
                    } else if (action === 'remove' && starIcon) {
                        // Remove star icon
                        starIcon.remove();
                    }
                }
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Error updating favorites', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating favorites', 'error');
        });

        // Hide context menu
        document.getElementById('customContextMenu').style.display = 'none';
    }

    // Edit order function
    function editOrder(orderId, stopLoss, takeProfit) {
        document.getElementById('editOrderId').value = orderId;
        document.getElementById('edit_stop_loss').value = stopLoss || '';
        document.getElementById('edit_take_profit').value = takeProfit || '';
        
        // Set the form action with the correct order ID
        const form = document.getElementById('editOrderForm');
        form.action = `{{ route('order.update', ['id' => '__ORDER_ID__']) }}`.replace('__ORDER_ID__', orderId);
    }

    // Context menu functionality
    function showContextMenu(event, assetId) {
        event.preventDefault();
        event.stopPropagation();
        
        const menu = document.getElementById('customContextMenu');
        const addBtn = document.getElementById('addToFavouriteBtn');
        const removeBtn = document.getElementById('removeFromFavouriteBtn');
        
        // Update asset ID for both buttons
        addBtn.setAttribute('data-asset-id', assetId);
        removeBtn.setAttribute('data-asset-id', assetId);
        
        // Check if asset is already in favorites
        const assetButton = document.querySelector(`[data-asset-id="${assetId}"]`);
        const isFavorite = assetButton.querySelector('.star-icon') !== null;
        
        // Show/hide appropriate buttons
        if (isFavorite) {
            addBtn.style.display = 'none';
            removeBtn.style.display = 'block';
        } else {
            addBtn.style.display = 'block';
            removeBtn.style.display = 'none';
        }
        
        // Position and show menu
        menu.style.left = event.pageX + 'px';
        menu.style.top = event.pageY + 'px';
        menu.style.display = 'block';
        
        // Hide menu when clicking elsewhere
        document.addEventListener('click', hideContextMenu);
    }

    function hideContextMenu() {
        document.getElementById('customContextMenu').style.display = 'none';
        document.removeEventListener('click', hideContextMenu);
    }

    // Function to update form fields and displayed prices when asset is selected
    function updateAssetPrices(assetId, assetSymbol, bidPrice, askPrice) {
        // Update hidden form fields
        document.getElementById('selectedAssetId').value = assetId;
        document.getElementById('selectedAssetSymbol').value = assetSymbol;
        document.getElementById('currentBidPrice').value = bidPrice;
        document.getElementById('currentAskPrice').value = askPrice;
        document.getElementById('currentChartSymbol').value = assetSymbol;
        
        // Update displayed prices on buy/sell buttons
        document.getElementById('displayBidPrice').textContent = parseFloat(bidPrice).toFixed(4);
        document.getElementById('displayAskPrice').textContent = parseFloat(askPrice).toFixed(4);
        
        // Update global asset ID for JavaScript
        window.assetId = assetId;
    }

    // Add click listeners to asset buttons to update prices without page reload
    document.addEventListener('DOMContentLoaded', function() {
        const assetButtons = document.querySelectorAll('.asset-button');
        
        assetButtons.forEach(button => {
            // Add click event that updates prices before navigation
            button.addEventListener('click', function(e) {
                const assetId = this.getAttribute('data-asset-id');
                const assetSymbol = this.getAttribute('data-symbol');
                
                // Find the bid and ask price spans within this button
                const bidPriceSpan = this.querySelector('.bid_price');
                const askPriceSpan = this.querySelector('.ask_price');
                
                if (bidPriceSpan && askPriceSpan) {
                    const bidPrice = bidPriceSpan.textContent.trim();
                    const askPrice = askPriceSpan.textContent.trim();
                    
                    // Update prices immediately for better UX
                    updateAssetPrices(assetId, assetSymbol, bidPrice, askPrice);
                }
            });
        });
    });
    
    // Profile editing functionality
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editProfileBtn');
        const profileForm = document.getElementById('profileForm');
        const profileDisplay = document.getElementById('profileDisplay');
        const cancelBtn = document.querySelector('.cancel-edit-btn');
        
        if (editBtn) {
            editBtn.addEventListener('click', function() {
                profileDisplay.style.display = 'none';
                profileForm.style.display = 'block';
                editBtn.style.display = 'none';
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                profileForm.style.display = 'none';
                profileDisplay.style.display = 'block';
                editBtn.style.display = 'inline-block';
            });
        }
        
        // Handle form submission
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the display values
                        const nameDisplay = document.querySelector('#profileDisplay .info-row:nth-child(1) .value.enhanced');
                        const emailDisplay = document.querySelector('#profileDisplay .info-row:nth-child(2) .value.enhanced');
                        const phoneDisplay = document.querySelector('#profileDisplay .info-row:nth-child(3) .value.enhanced');
                        const countryDisplay = document.querySelector('#profileDisplay .info-row:nth-child(4) .value.enhanced');
                        
                        if (nameDisplay) nameDisplay.textContent = formData.get('name') || 'Not provided';
                        if (emailDisplay) emailDisplay.textContent = formData.get('email') || 'Not provided';
                        if (phoneDisplay) phoneDisplay.textContent = formData.get('phone') || 'Not provided';
                        if (countryDisplay) countryDisplay.textContent = formData.get('country') || 'Not provided';
                        
                        // Switch back to display mode
                        profileForm.style.display = 'none';
                        profileDisplay.style.display = 'block';
                        editBtn.style.display = 'inline-block';
                        
                        // Show success message
                        alert('Profile updated successfully!');
                    } else {
                        alert('Error updating profile: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating profile. Please try again.');
                });
            });
        }
        
        // Deposit Tabs and Refresh functionality
        const refreshBtn = document.querySelector('.refresh-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                // Add loading state
                const icon = this.querySelector('i');
                icon.classList.add('fa-spin');
                
                // Simulate refresh - in a real app, this would reload the data
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
        }
        
        // Tab switching functionality
        const depositTabs = document.querySelectorAll('#depositTabs .nav-link');
        depositTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                depositTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Optional: Add analytics or logging here
                const tabId = this.getAttribute('data-bs-target');
            });
        });
    });
</script>

</body>
</html>
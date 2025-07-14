@extends('layouts.mobile')

@section('content')
<div class="charts-modern-container">
    <!-- Header Section -->
    <div class="charts-header">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="symbol-info">
                <h4 class="symbol-name mb-1">{{ $symbol }}</h4>
                <small class="symbol-description text-muted">Live Chart Analysis</small>
            </div>
            <div class="chart-actions">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('1')">1 Min</button></li>
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('5')">5 Min</button></li>
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('15')">15 Min</button></li>
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('60')">1 Hour</button></li>
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('240')">4 Hour</button></li>
                        <li><button class="dropdown-item" type="button" onclick="changeInterval('D')">Daily</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="chart-wrapper">
        <div class="chart-loading-overlay" id="chartLoading">
            <div class="loading-spinner">
                <output class="spinner-border text-primary" aria-live="polite">
                    <span class="visually-hidden">Loading chart...</span>
                </output>
                <p class="mt-2 text-muted">Loading {{ $symbol }} chart...</p>
            </div>
        </div>
        <div class="tradingview-widget-container" id="tradingview_chart">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container__widget"></div>
            <div class="tradingview-widget-copyright">
                <a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank">
                    <span class="blue-text">Track all markets on TradingView</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Tools Panel -->
    <div class="quick-tools-panel">
        <div class="tools-scroll">
            <button class="tool-btn" data-tool="candlestick" data-style="1" onclick="changeChartStyle(1)">
                <i class="fas fa-chart-line"></i>
                <span>Candles</span>
            </button>
            <button class="tool-btn" data-tool="line" data-style="2" onclick="changeChartStyle(2)">
                <i class="fas fa-chart-area"></i>
                <span>Line</span>
            </button>
            <button class="tool-btn" data-tool="area" data-style="3" onclick="changeChartStyle(3)">
                <i class="fas fa-chart-area"></i>
                <span>Area</span>
            </button>
            <button class="tool-btn" data-tool="bars" data-style="0" onclick="changeChartStyle(0)">
                <i class="fas fa-chart-bar"></i>
                <span>Bars</span>
            </button>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.charts-modern-container {
    min-height: calc(100vh - 120px);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    background-attachment: fixed;
    padding: 0;
    overflow: hidden;
}

.charts-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 1rem;
    margin: 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 10;
}

.symbol-name {
    font-weight: 700;
    color: #2c3e50;
    font-size: 1.25rem;
}

.symbol-description {
    font-size: 0.8rem;
    opacity: 0.7;
}

.chart-actions .btn {
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.chart-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.dropdown-item {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.chart-wrapper {
    position: relative;
    height: calc(100vh - 280px);
    min-height: 400px;
    margin: 0;
    background: #ffffff;
    border-radius: 0;
}

.chart-loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 5;
    backdrop-filter: blur(5px);
}

.loading-spinner {
    text-align: center;
}

.tradingview-widget-container {
    height: 100%;
    width: 100%;
    border-radius: 0;
}

.tradingview-widget-container__widget {
    height: 100% !important;
    width: 100% !important;
}

.tradingview-widget-copyright {
    display: none !important;
}

.quick-tools-panel {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    position: sticky;
    bottom: 0;
    z-index: 10;
}

.tools-scroll {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.tools-scroll::-webkit-scrollbar {
    display: none;
}

.tool-btn {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
    min-width: 70px;
    text-decoration: none;
    color: #6c757d;
    font-size: 0.7rem;
    white-space: nowrap;
}

.tool-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    color: #495057;
    background: rgba(255, 255, 255, 0.95);
}

.tool-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.tool-btn i {
    font-size: 1rem;
}

/* Mobile responsiveness */
@media (max-width: 576px) {
    .charts-header {
        padding: 0.75rem;
    }
    
    .symbol-name {
        font-size: 1.1rem;
    }
    
    .stat-card {
        padding: 0.5rem 0.25rem;
    }
    
    .stat-value {
        font-size: 0.8rem;
    }
    
    .tool-btn {
        min-width: 60px;
        padding: 0.5rem 0.75rem;
    }
    
    .chart-wrapper {
        height: calc(100vh - 300px);
    }
}

/* Dark Theme Support */
[data-theme="dark"] .charts-modern-container {
    background: linear-gradient(135deg, #121624 0%, #1C1F26 100%);
    background-attachment: fixed;
}

[data-theme="dark"] .charts-header {
    background: rgba(28, 31, 38, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #2A2D35;
}

[data-theme="dark"] .symbol-name {
    color: #FFFFFF !important;
}

[data-theme="dark"] .symbol-description {
    color: #B3B3B3 !important;
}

[data-theme="dark"] .chart-actions .btn {
    background: rgba(28, 31, 38, 0.8) !important;
    border: 1px solid #2A2D35 !important;
    color: #FFFFFF !important;
}

[data-theme="dark"] .chart-actions .btn:hover {
    background: rgba(20, 25, 39, 0.9) !important;
    border-color: #FFD700 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
}

[data-theme="dark"] .dropdown-menu {
    background-color: #1C1F26 !important;
    border: 1px solid #2A2D35 !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important;
}

[data-theme="dark"] .dropdown-item {
    color: #FFFFFF !important;
}

[data-theme="dark"] .dropdown-item:hover {
    background-color: rgba(255, 215, 0, 0.1) !important;
    color: #FFD700 !important;
}

[data-theme="dark"] .chart-wrapper {
    background: #141927 !important;
}

[data-theme="dark"] .chart-loading-overlay {
    background: rgba(20, 25, 39, 0.95) !important;
    backdrop-filter: blur(5px);
}

[data-theme="dark"] .loading-spinner .spinner-border {
    color: #FFD700 !important;
}

[data-theme="dark"] .loading-spinner p {
    color: #B3B3B3 !important;
}

[data-theme="dark"] .quick-tools-panel {
    background: rgba(28, 31, 38, 0.95) !important;
    backdrop-filter: blur(10px);
    border-top: 1px solid #2A2D35 !important;
}

[data-theme="dark"] .tool-btn {
    background: rgba(20, 25, 39, 0.8) !important;
    border: 1px solid #2A2D35 !important;
    color: #B3B3B3 !important;
}

[data-theme="dark"] .tool-btn:hover {
    background: rgba(28, 31, 38, 0.95) !important;
    border-color: #FFD700 !important;
    color: #FFFFFF !important;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.2);
}

[data-theme="dark"] .tool-btn.active {
    background: linear-gradient(135deg, #FFD700 0%, #FFC107 100%) !important;
    color: #121624 !important;
    border-color: #FFD700 !important;
    font-weight: 600;
}

[data-theme="dark"] .tool-btn.active:hover {
    background: linear-gradient(135deg, #FFC107 0%, #FFB300 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 215, 0, 0.3);
}

/* Dark theme responsive adjustments */
@media (max-width: 576px) {
    [data-theme="dark"] .charts-header {
        background: rgba(28, 31, 38, 0.98);
    }
    
    [data-theme="dark"] .quick-tools-panel {
        background: rgba(28, 31, 38, 0.98);
    }
}

/* Prefers dark mode fallback */
@media (prefers-color-scheme: dark) {
    .charts-modern-container {
        background: linear-gradient(135deg, #121624 0%, #1C1F26 100%);
    }
    
    .charts-header,
    .quick-tools-panel {
        background: rgba(28, 31, 38, 0.95);
        border-color: #2A2D35;
    }
    
    .symbol-name {
        color: #FFFFFF;
    }
    
    .symbol-description {
        color: #B3B3B3;
    }
    
    .tool-btn {
        background: rgba(20, 25, 39, 0.8);
        border-color: #2A2D35;
        color: #B3B3B3;
    }
    
    .tool-btn:hover {
        border-color: #FFD700;
        color: #FFFFFF;
    }
    
    .tool-btn.active {
        background: #FFD700;
        color: #121624;
        border-color: #FFD700;
    }
}
</style>

<!-- Modern JavaScript -->
<script>
let currentSymbol = '{{ $symbol }}';
let currentInterval = '{{ $interval }}';
let currentStyle = {{ $style }};

// Function to get current chart theme based on data-theme attribute
function getChartTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'light';
    return currentTheme === 'dark' ? 'dark' : 'light';
}

// Function to reload chart with new theme
function reloadChartWithTheme() {
    const chartTheme = getChartTheme();
    const url = `{{ route('clientarea.charts') }}?symbol=${currentSymbol}&interval=${currentInterval}&style=${currentStyle}&theme=${chartTheme}`;
    window.location.href = url;
}

document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    initializeActiveStyle();
    setupThemeWatcher();
    loadTradingViewWidget();
    
    // Hide loading overlay after chart loads
    setTimeout(() => {
        const loadingOverlay = document.getElementById('chartLoading');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    }, 3000);
    
    // Also listen for the widget to load
    const checkForWidget = setInterval(() => {
        const widgetContainer = document.querySelector('.tradingview-widget-container iframe');
        if (widgetContainer) {
            setTimeout(() => {
                const loadingOverlay = document.getElementById('chartLoading');
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            }, 1000);
            clearInterval(checkForWidget);
        }
    }, 500);
});

function loadTradingViewWidget() {
    // Clear any existing widget
    const widgetContainer = document.querySelector('.tradingview-widget-container__widget');
    if (widgetContainer) {
        widgetContainer.innerHTML = '';
    }
    
    // Get current theme
    const currentTheme = getChartTheme();
    
    // Create script element
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js';
    script.async = true;
    
    // Widget configuration
    script.innerHTML = JSON.stringify({
        "autosize": true,
        "symbol": "{{ $symbol }}",
        "interval": "{{ $interval }}",
        "timezone": "Etc/UTC",
        "theme": currentTheme,
        "style": "{{ $style }}",
        "locale": "en",
        "withdateranges": true,
        "hide_side_toolbar": false,
        "allow_symbol_change": false,
        "details": true,
        "hotlist": false,
        "calendar": false,
        "studies": [],
        "support_host": "https://www.tradingview.com"
    });
    
    // Append script to widget container
    widgetContainer.appendChild(script);
}

function setupThemeWatcher() {
    // Watch for theme changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                // Theme changed, reload widget with new theme
                setTimeout(() => {
                    loadTradingViewWidget();
                }, 300); // Small delay to ensure theme transition completes
            }
        });
    });
    
    // Start observing theme changes
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });
    
    // Also listen for storage changes (theme switching in other tabs)
    window.addEventListener('storage', (e) => {
        if (e.key === 'theme') {
            setTimeout(() => {
                loadTradingViewWidget();
            }, 300);
        }
    });
}

function initializeActiveStyle() {
    // Set active style based on current style
    document.querySelectorAll('.tool-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.style == currentStyle) {
            btn.classList.add('active');
        }
    });
}

function setupEventListeners() {
    // Setup any additional event listeners here if needed
}

function changeInterval(interval) {
    currentInterval = interval;
    const chartTheme = getChartTheme();
    // For the embedded widget, we'll need to reload with new parameters
    location.href = `{{ route('clientarea.charts') }}?symbol=${currentSymbol}&interval=${interval}&style=${currentStyle}&theme=${chartTheme}`;
}

function changeChartStyle(style) {
    currentStyle = style;
    
    // Update active tool button
    document.querySelectorAll('.tool-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.tool-btn').classList.add('active');
    
    const chartTheme = getChartTheme();
    // For the embedded widget, we'll need to reload with new parameters
    location.href = `{{ route('clientarea.charts') }}?symbol=${currentSymbol}&interval=${currentInterval}&style=${style}&theme=${chartTheme}`;
}

// Handle page visibility for performance
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Pause updates when page is hidden
    } else {
        // Resume updates when page is visible
    }
});
</script>
@endsection
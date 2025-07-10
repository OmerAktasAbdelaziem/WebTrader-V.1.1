
<div class="card border-0 shadow-sm @if ($locale == 'ar') rtl @endif" style="border-radius: 14px; background: #fff; color: #222;">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
            <div class="fw-bold" style="font-size: 1.1rem;">
                <i class="fas fa-wallet me-2 text-primary"></i>{{ __('web.balance') }}
            </div>
            <div class="fw-semibold" style="font-size: 1.2rem;">$ {{ number_format($finance['balance'], 2, '.', ',') }}</div>
        </div>
        <div class="row g-2 text-center">
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.equity') }}</div>
                    <div class="fw-bold equity">$ {{ number_format($finance['equity'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.profitloss') }}</div>
                    <div class="fw-bold currentPL">$ {{ number_format($finance['currentPL'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.margin') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['usedMargin'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.free') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['freeMargin'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.bonus') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['bonus'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 mb-2">
                <div class="border rounded-3 py-2 px-1 h-100">
                    <div class="small text-muted">{{ __('web.credit') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['credit'], 2, '.', ',') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

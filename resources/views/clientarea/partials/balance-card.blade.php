
<div class="balance-card-modern card shadow-sm @if ($locale == 'ar') rtl @endif" style="border-radius: 18px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="me-2" style="font-size: 2rem;">
                    <i class="fas fa-wallet"></i>
                </span>
                <div>
                    <div class="fw-bold" style="font-size: 1.2rem; letter-spacing: 0.5px;">{{ __('web.balance') }}</div>
                    <div class="fs-5">$ {{ number_format($finance['balance'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="text-end">
                <div class="small text-white-50">ID: {{ auth()->guard('client')->user()->id }}</div>
            </div>
        </div>
        <div class="row g-2 text-center">
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.equity') }}</div>
                    <div class="fw-bold equity">$ {{ number_format($finance['equity'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.profitloss') }}</div>
                    <div class="fw-bold currentPL">$ {{ number_format($finance['currentPL'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.margin') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['usedMargin'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.free') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['freeMargin'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.bonus') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['bonus'], 2, '.', ',') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="bg-white bg-opacity-10 rounded-3 p-2">
                    <div class="small text-uppercase text-white-50">{{ __('web.credit') }}</div>
                    <div class="fw-bold">$ {{ number_format($finance['credit'], 2, '.', ',') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Services\Api\Core\Interfaces\HttpClientServiceInterface;
use App\Http\Services\Api\Core\CurlHttpClientService;
use App\Http\Services\Api\Crm\Interfaces\CrmApiServiceInterface;
use App\Http\Services\Api\Crm\CrmApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HttpClientServiceInterface::class, CurlHttpClientService::class);
        $this->app->bind(CrmApiServiceInterface::class, CrmApiService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

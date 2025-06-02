<?php

use App\Http\Controllers\ClientsTransferController;
use App\Http\Controllers\LandingPagesController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\MainTPController;
use App\Http\Controllers\SmartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

Route::name('api.')->prefix('v1/')->group(function (Router $router) {
    $router->post('LeadCapture/{source?}/{pipeline_id?}', [LandingPagesController::class,    'LeadCapture'])->name('LeadCapture');
    $router->post('smart/registerUser',                   [ClientsTransferController::class, 'register_user'])->name('register_user');
    $router->get('getFinancialDataPhoenix/{broker_id}',   [MainTPController::class,          'get_financial_data_phoenix'])->name('get_financial_data_phoenix');
    $router->get('getOnlineStatusPhoenix/{broker_id}',    [MainTPController::class,          'get_online_status_phoenix'])->name('get_online_status_phoenix');
    $router->get('getFinancialData/{broker_id}',          [MainTPController::class,          'get_financial_data'])->name('get_financial_data');
    $router->post('smart/updateUserPassword',             [SmartController::class,           'update_user_password'])->name('update_user_password');
    $router->get('getOpenedData/{broker_id}',             [MainTPController::class,          'get_opened_data'])->name('get_opened_data');
});

Route::name('telegram.')->prefix('telegram')->controller(TelegramController::class)->group(function (Router $router) {
    $router->name('webhooks.')->prefix('webhooks')->group(function (Router $router) {
        $router->post('/inbound', 'inbound')->name('inbound');
    });
});

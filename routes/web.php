<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebTraderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

Route::get('lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar', 'tr'])) {
        Session::put('locale', $lang);
    }
    return Redirect::back();
})->name('switch.lang');



Auth::routes(['verify' => true]);

Auth::routes();

Route::get('', function () {
    return redirect()->route('client.webtrader');
});
Route::get ('/client/signup',                    [ClientLoginController::class,     'showSignupForm'          ])->name('client.signup');
Route::post('/client/signup',                    [ClientLoginController::class,     'signup'                  ])->name('client.signup.submit');

Route::get ('/client/login',                     [ClientLoginController::class,     'showLoginForm'           ])->name('client.login');
Route::post('/client/login',                     [ClientLoginController::class,     'login'                   ]);

Route::post('/client/logout',                    [LoginController::class,           'logout'                  ])->name('client.logout');

Route::get ('/client/reset/password',            [ClientsController::class,         'showResetPasswordForm'   ])->name('client.reset.password');
Route::post('/client/reset/password',            [ClientsController::class,         'resetPassword'           ])->name('client.reset_password');


Route::middleware(['clientAuth','role:isEnabled'])->group(function (Router $router) {
    $router->get   ('/client/dashboard',                [ClientDashboardController::class, 'index'                   ])->name('client.dashboard');
    $router->get   ('/client/kyc',                      [ClientsController::class,         'showKycForm'             ])->name('client.kyc.form');
    $router->post  ('/client/kyc/upload',               [ClientsController::class,         'uploadKycPhoto'          ])->name('client.kyc.upload');
    $router->get   ('/client/trading-platform',         [ClientsController::class,         'showTradingPlatform'     ])->name('client.trading.platform');
    $router->get   ('/client/webtrader',                [WebTraderController::class,       'index'                   ])->withoutMiddleware('clientAuth')->name('client.webtrader');
    $router->get   ('/deposit',                         [ClientsController::class,         'showDepositForm'         ])->name('client.deposit');
    $router->post  ('/deposit',                         [ClientsController::class,         'processDeposit'          ])->name('deposit.process');
    $router->post  ('/client/get-banks-by-country',     [BankController::class,            'getBanksByCountry'       ])->name('get.banks');
    $router->post  ('/client/get-bank-details',         [BankController::class,            'getBankDetails'          ])->name('get.bank.details');
    $router->get   ('/client/withdraw',                 [ClientsController::class,         'showWithdrawForm'        ])->name('client.withdraw');
    $router->post  ('/client/withdraw',                 [ClientsController::class,         'submitWithdrawForm'      ])->name('client.withdraw.submit');
    $router->get   ('/client/quotes',                   [ClientsController::class,         'showQuotes'              ])->name('clientarea.quotes');
    $router->get   ('/client/orders',                   [ClientsController::class,         'showOrders'              ])->name('clientarea.orders');
    $router->get   ('/client/charts',                   [ClientsController::class,         'showCharts'              ])->name('clientarea.charts');
    $router->get   ('/client/account',                  [ClientsController::class,         'showAccount'             ])->name('clientarea.account');
    $router->get   ('/toggle-favourite',                [ClientsController::class,         'toggleFavourite'         ])->name('toggle.favourite');
    $router->post  ('/order',                           [OrderController::class,           'store'                   ])->name('order.store');
    $router->post  ('/order/multiClose',                [OrderController::class,           'multiClose'              ])->name('order.multiClose');
    $router->post  ('/order/{id}',                      [OrderController::class,           'close'                   ])->name('order.close');
    $router->put   ('/order/{id}',                      [OrderController::class,           'update'                  ])->name('order.update');
    $router->delete('/order/{id}',                      [OrderController::class,           'delete'                  ])->name('order.delete');
    $router->get   ('/chat',                            [ChatController::class,            'index'                   ])->name('chat.index');
    $router->post  ('/chat',                            [ChatController::class,            'store'                   ])->name('chat.store');
});
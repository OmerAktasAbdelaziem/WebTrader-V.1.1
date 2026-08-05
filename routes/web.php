<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientLoginController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebTraderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

Route::get('lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar', 'tr'])) {
        Session::put('locale', $lang);
    }
    // Get the previous URL the user came from
    $previousUrl = URL::previous();
    
    //  Parse the URL path
    $path = parse_url($previousUrl, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $lastSegment = end($segments);

    // If the previous URL ends with a language code, remove it
    if (in_array($lastSegment, ['en', 'ar', 'tr'])) {
        array_pop($segments);
        $cleanPath = implode('/', $segments);
        
        // Rebuild the clean URL (keeping query parameters if any exist)
        $queryString = parse_url($previousUrl, PHP_URL_QUERY);
        $newUrl = url($cleanPath . "/$lang") . ($queryString ? '?' . $queryString : '');
        if($cleanPath == 'client/signup' || $cleanPath == 'client/login'){
            return redirect($newUrl);
        }
    }else{
        $cleanPath = implode('/', $segments);
        $queryString = parse_url($previousUrl, PHP_URL_QUERY);
        $newUrl = url($cleanPath . "/$lang") . ($queryString ? '?' . $queryString : '');
        if($cleanPath == 'client/signup' || $cleanPath == 'client/login'){
            return redirect($newUrl);
        }

    }

    return Redirect::back();
})->name('switch.lang');



Auth::routes(['verify' => true]);

Auth::routes();

Route::get('', function () {
    return redirect()->route('client.webtrader');
});
Route::get ('/client/signup/{lang?}',            [ClientLoginController::class,     'showSignupForm'          ])->name('client.signup');
Route::post('/client/signup',                    [ClientLoginController::class,     'signup'                  ])->name('client.signup.submit');

Route::get ('/client/login/{lang?}',             [ClientLoginController::class,     'showLoginForm'           ])->name('client.login');
Route::post('/client/login',                     [ClientLoginController::class,     'login'                   ]);
Route::get('auto-login',                         [ClientLoginController::class,     'handleAutoLogin'         ])->name('auto.login');

Route::post('/client/logout',                    [LoginController::class,           'logout'                  ])->name('client.logout');

Route::get ('/client/reset/password',            [ClientsController::class,         'showResetPasswordForm'   ])->name('client.reset.password');
Route::post('/client/reset/password',            [ClientsController::class,         'resetPassword'           ])->name('client.reset_password');


Route::middleware(['clientAuth','role:isEnabled'])->group(function (Router $router) {
    $router->get   ('/client/dashboard',                [ClientDashboardController::class, 'index'                   ])->name('client.dashboard');
    $router->get   ('/client/kyc',                      [ClientsController::class,         'showKycForm'             ])->name('client.kyc.form');
    $router->post  ('/client/kyc/upload',               [ClientsController::class,         'uploadKycPhoto'          ])->name('client.kyc.upload');
    $router->get   ('/client/trading-platform',         [ClientsController::class,         'showTradingPlatform'     ])->name('client.trading.platform');
    $router->get   ('/client/webtrader',                [WebTraderController::class,       'index'                   ])->withoutMiddleware('clientAuth')->name('client.webtrader');
    $router->get   ('/client/webtrader/loading',        [WebTraderController::class,       'showLoading'             ])->name('client.webtrader.loading');
    $router->get   ('/client/webtrader/main',           [WebTraderController::class,       'index'                   ])->name('client.webtrader.main');
    $router->get   ('/deposit',                         [ClientsController::class,         'showDepositForm'         ])->name('client.deposit');
    $router->post  ('/deposit',                         [ClientsController::class,         'processDeposit'          ])->name('deposit.process');
    $router->post  ('/client/get-banks-by-country',     [BankController::class,            'getBanksByCountry'       ])->name('get.banks');
    $router->post  ('/client/get-bank-details',         [BankController::class,            'getBankDetails'          ])->name('get.bank.details');
    $router->get   ('/client/withdraw',                 [ClientsController::class,         'showWithdrawForm'        ])->name('client.withdraw');
    $router->post  ('/client/withdraw',                 [ClientsController::class,         'submitWithdrawForm'      ])->name('client.withdraw.submit');
    $router->get   ('/client/quotes',                   [ClientsController::class,         'showQuotes'              ])->name('clientarea.quotes');
    $router->get   ('/client/orders',                   [ClientsController::class,         'showOrders'              ])->name('clientarea.orders');
    $router->get   ('/client/charts',                   [ClientsController::class,         'showCharts'              ])->name('clientarea.charts');
    $router->get   ('/api/price-data',                  [ClientsController::class,         'getPriceData'            ])->name('api.price.data');
    $router->get   ('/api/pnl-data',                    [OrderController::class,           'getPnlData'              ])->name('api.pnl.data');
    $router->get   ('/client/account',                  [ClientsController::class,         'showAccount'             ])->name('clientarea.account');
    $router->put   ('/client/profile',                  [ClientsController::class,         'updateProfile'           ])->name('client.update.profile');
    $router->post  ('/toggle-favourite',                [ClientsController::class,         'toggleFavourite'         ])->name('toggle.favourite');
    $router->get   ('/toggle-favourite',                [ClientsController::class,         'toggleFavourite'         ])->name('toggle.favourite');
    $router->post  ('/order',                           [OrderController::class,           'store'                   ])->name('order.store');
    $router->post  ('/order/multiClose',                [OrderController::class,           'multiClose'              ])->name('order.multiClose');
    $router->post  ('/order/edit/{id}',                 [OrderController::class,           'update'                  ])->name('order.edit');
    $router->post  ('/order/{id}',                      [OrderController::class,           'close'                   ])->name('order.close');
    $router->put   ('/order/{id}',                      [OrderController::class,           'update'                  ])->name('order.update');
    $router->delete('/order/{id}',                      [OrderController::class,           'delete'                  ])->name('order.delete');
    $router->post  ('/withdrawal',                      [ClientsController::class,         'processWithdrawal'       ])->name('client.withdrawal');
    $router->post  ('/withdrawal/store',                [ClientsController::class,         'processWithdrawal'       ])->name('client.withdrawal.store');
    $router->get   ('/withdrawals/history',             [ClientsController::class,         'getWithdrawalHistory'    ])->name('client.withdrawals.history');
    $router->get   ('/withdrawals/debug-status',        [ClientsController::class,         'debugWithdrawalStatuses' ])->name('client.withdrawals.debug-status');
    $router->post  ('/withdrawal/cancel',               [ClientsController::class,         'cancelWithdrawal'        ])->name('client.withdrawal.cancel');
    $router->get   ('/transactions/refresh',            [ClientsController::class,         'refreshTransactions'     ])->name('client.transactions.refresh');
    $router->get   ('/deposits/refresh',                [ClientsController::class,         'refreshDepositTransactions'])->name('client.deposits.refresh');
    $router->get   ('/chat',                            [ChatController::class,            'index'                   ])->name('chat.index');
    $router->post  ('/chat',                            [ChatController::class,            'store'                   ])->name('chat.store');
    $router->post  ('/notification/read',               [WebTraderController::class,       'markNotificationAsRead'  ])->name('notification.read');
    $router->get   ('/notification/test',               [WebTraderController::class,       'createTestNotifications' ])->name('notification.test');
    
    // New notification routes
    $router->post  ('/notifications/{id}/read',         [NotificationController::class,    'markAsRead'              ])->name('notifications.mark-as-read');
    $router->post  ('/notifications/{id}/delete',       [NotificationController::class,    'delete'                  ])->name('notifications.delete');
    $router->post  ('/notifications/mark-all-read',     [NotificationController::class,    'markAllAsRead'           ])->name('notifications.mark-all-read');
    $router->post  ('/notifications/clear-all',         [NotificationController::class,    'clearAll'                ])->name('notifications.clear-all');
    
    // Upload Document Routes
    $router->post  ('/client/upload-documents',         [ClientsController::class,         'uploadDocuments'         ])->name('client.upload.documents');
    $router->get   ('/client/get-documents',            [ClientsController::class,         'getDocuments'            ])->name('client.get.documents');
    $router->get   ('/client/download-document/{id}',   [ClientsController::class,         'downloadDocument'        ])->name('client.download.document');
    $router->post  ('/client/delete-document',          [ClientsController::class,         'deleteDocument'          ])->name('client.delete.document');
});
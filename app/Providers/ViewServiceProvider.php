<?php

namespace App\Providers;

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\UserController;
use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Chat_ah;
use App\Models\Client;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Pipeline;
use App\Models\SystemStyle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $client = Auth::guard('client')->user();
        
            if ($client && $client->broker_id) {
                $view->with([
                    'nav_pipelines'  => Pipeline::select('id', 'name')->where('id', '!=', $client->pipeline_id)->get(),
                    'notifications'  => Notification::where('client_id', $client->id)->unread()->latest()->limit(15)->get(),
                    'finance'        => (new ClientsController)->get_financial_data($client->broker_id),
                    'system'         => SystemStyle::first(),
                    'locale'         => Session::get('locale'),
                ]);
            } elseif ($client) {
                // Client exists but no broker_id - provide default values
                $view->with([
                    'nav_pipelines'  => Pipeline::select('id', 'name')->where('id', '!=', $client->pipeline_id ?? 0)->get(),
                    'notifications'  => Notification::where('client_id', $client->id)->unread()->latest()->limit(15)->get(),
                    'finance'        => [
                        'balance' => 0.00,
                        'equity' => 0.00,
                        'usedMargin' => 0.00,
                        'freeMargin' => 0.00,
                        'currentPL' => 0.00,
                    ],
                    'system'         => SystemStyle::first(),
                    'locale'         => Session::get('locale'),
                ]);
            }
        });
        
        View::composer('layouts.client', function ($view) {
            $client = Auth::guard('client')->user();
        
            if ($client) {
                $client->update(['remember_token' => Str::random(60)]);
                $view->with('remember_token', $client->remember_token);
            }
        });

        View::composer('layouts.mobile', function ($view) {
            $user = Auth::guard('client')->user();
            
            $forexAssets          = [];
            $cryptoAssets         = [];
            $stocksAssets         = [];
            $indicesAssets        = [];
            $commodityAssets      = [];
            $favourite_assets     = [];
            $favourite_assets_ids = $user ? ($user->favourite_assets ?? []) : [];
    
            $asset_group_id = $user ? $user->asset_group_id : null;
            $totalChat      = $user ? Chat_ah::where('client_id', $user->id)->where('read',0)->count() : 0;
            
            if ($asset_group_id) {
                $assetGroup       = AssetGroup::find($asset_group_id);
                if ($assetGroup && $assetGroup->asset_ids) {
                    $forexAssets      = Asset::where('bid_price','!=',0)->where('category', 'Forex')  ->whereIn('id',$assetGroup->asset_ids)->get();
                    $cryptoAssets     = Asset::where('bid_price','!=',0)->where('category', 'Crypto') ->whereIn('id',$assetGroup->asset_ids)->get();
                    $stocksAssets     = Asset::where('bid_price','!=',0)->where('category', 'Stocks') ->whereIn('id',$assetGroup->asset_ids)->get();
                    $indicesAssets    = Asset::where('bid_price','!=',0)->where('category', 'Indx')   ->whereIn('id',$assetGroup->asset_ids)->get();
                    $commodityAssets  = Asset::where('bid_price','!=',0)->where('category', 'Commodity')->whereIn('id',$assetGroup->asset_ids)->get();
                    $favourite_assets = Asset::whereIn('id', $assetGroup->asset_ids)->whereIn('id', $favourite_assets_ids)->where('bid_price','!=',0)->whereIn('category', ['Crypto','Forex', 'Stocks', 'Commodity','Indx'])->get();
                }
            }
            
            $view->with([
                'favourite_assets_ids' => $favourite_assets_ids,
                'favourite_assets'     => $favourite_assets ,
                'commodityAssets'      => $commodityAssets ,
                'asset_group_id'       => $asset_group_id ,
                'indicesAssets'        => $indicesAssets ,
                'cryptoAssets'         => $cryptoAssets ,
                'stocksAssets'         => $stocksAssets ,
                'forexAssets'          => $forexAssets ,
                'totalChat'            => $totalChat ,
            ]);
        });
        
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('client')->check() == false || Auth::guard('client')->user()->deleted == 1) {
            if (Auth::guard('client')->check()) {
                Auth::guard('client')->logout();
            }
            return redirect()->route('client.login');
        }
        return $next($request);
    }
}

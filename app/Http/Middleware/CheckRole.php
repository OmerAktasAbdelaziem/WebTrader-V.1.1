<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $option)
    {
        if (auth()->guard('client')->user()) {
            $options = auth()->guard('client')->user()->options;
        }else{
            return $next($request);
        }

        if (!isset($options[$option])) {
            return response()->view('clientarea.no_access');
        }

        if (isset($options['forceChangePassword'])) {
            return redirect()->route('client.reset.password');
        }

        return $next($request);
    }
}

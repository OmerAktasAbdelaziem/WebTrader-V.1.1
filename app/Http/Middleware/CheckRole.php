<?php

namespace App\Http\Middleware;

use App\Models\Pipeline;
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
            $pipelineId = config('app.pipeline_id');
            $crmbaseUrl = config('services.crm_api.url');
            $pipeline = Pipeline::find($pipelineId);
            $logoUrl = "$crmbaseUrl/storage/$pipeline->logo";
            return response()->view('clientarea.no_access', compact('logoUrl'));
        }

        if (isset($options['forceChangePassword'])) {
            return redirect()->route('client.reset.password');
        }

        return $next($request);
    }
}

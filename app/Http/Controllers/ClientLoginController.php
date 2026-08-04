<?php

namespace App\Http\Controllers;

use App\Helpers\CLientHelper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\Client;
use Carbon\Carbon;
use App\Models\AssetGroup;
use App\Models\SsoToken;

class ClientLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $pipelineId = config('app.pipeline_id');
        $crmbaseUrl = config('services.crm_api.url');
        $pipeline = Pipeline::find($pipelineId);
        $logoUrl = "$crmbaseUrl/storage/$pipeline->logo";

        if ($lang = $request->lang) {
            Session::put('locale', $lang);
        }
        return view('clientarea.login', compact('logoUrl', 'pipeline'));
    }

    protected function authenticated()
    {
        if (Auth::check()) {
            //echo Auth::guard('client')->id();
    $user = Auth::guard('client')->user();
    $user->loggedAt = now();
    $user->save();
}
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ]);
        $pipelineId = config('app.pipeline_id');
        $credentials = $request->only('user', 'password');

        if (Auth::guard('client')->attempt(['email' => $credentials['user'], 'password' => $credentials['password'], 'deleted' => 0, 'pipeline_id' => $pipelineId])) {
            //$this->authenticated();
            return redirect()->route('client.webtrader.loading')->with('success', __('web.login_successful'));
        }
        if (Auth::guard('client')->attempt(['username' => $credentials['user'], 'password' => $credentials['password'], 'deleted' => 0, 'pipeline_id' => $pipelineId])) {
            //$this->authenticated();
            return redirect()->route('client.webtrader.loading')->with('success', __('web.login_successful'));
        }

        Log::warning('Login failed for user:', ['user' => $request->user,'pass' => $request->password]);

        return back()->withErrors([
            'email' => __('web.the_provided_credentials_do_not_match_our_records'),
        ])->withInput();
    }

    public function showSignupForm(Request $request)
    {
        
        $pipelineId = config('app.pipeline_id');
        $crmbaseUrl = config('services.crm_api.url');
        $pipeline = Pipeline::find($pipelineId);
        $logoUrl = "$crmbaseUrl/storage/$pipeline->logo";
        
        if ($lang = $request->lang) {
            Session::put('locale', $lang);
        }
        return view('clientarea.signup', compact('logoUrl', 'pipeline'));
    }

    public function signup(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone1' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
    
        $inputs = $request->only([
            'first_name',
            'country',
            'phone1',
            'email',
        ]);
        $pipelineId = config('app.pipeline_id');
        $existingClient = Client::where('email', $request->email)->where('pipeline_id', $pipelineId)->first();
    
        if ($existingClient) {
            if ($existingClient->broker_id) {
                return redirect()->back()->with('fail', __('web.email_is_already_exist'));
            } else {
                $broker_id = $this->createBrokerId();
                $inputs['broker_id'] = $broker_id;
            }
        } else {
            $broker_id = $this->createBrokerId();
            $inputs['broker_id'] = $broker_id;
        }
    
        $options = [];
        $inputs['password_text']            = $request->password;
        $options['isEnabled']               = 1;
        $options['isVerified']              = 1;
        $options['enableDepositRequest']    = 1;
        $options['enableWithdrawalRequest'] = 1;
    
        $inputs['password']                 = Hash::make($request->password);
        $inputs['reg_date']                 = Carbon::now();
        $inputs['username']                 = $request->email;
        $inputs['options']                  = $options;
        $inputs['account_type']             = 'Demo';
        // $inputs['source']                   = 'BNC';
        $inputs['source']                   = $request->getHost();
        $inputs['favourite_assets']         = ["1", "2", "3", "4", "5", "6", "20", "22", "10", "73", "74"];
        $inputs['asset_group_id']           = 1;
        $inputs['pipeline_id'] = config('app.pipeline_id');
        $inputs['registeration_ip']         = ClientHelper::getClientIp();

    
        if ($existingClient) {
            $existingClient->update($inputs);
        } else {
            $defaultAssetGroup = AssetGroup::where('pipeline_id', config('app.pipeline_id'))
    ->where('default', 1)
    ->first();
    $inputs = array_merge($inputs, [
        'asset_group_id' => $defaultAssetGroup->id,
    ]);
            $client = Client::create($inputs);
        }
    
        if (Auth::guard('client')->attempt(['username' => $request->email, 'password' => $request->password, 'deleted' => 0, 'pipeline_id' => $pipelineId])) {
            $this->authenticated();
            return redirect()->route('client.webtrader.loading')->with('success', __('web.registration_successful'));
        }
    
        return redirect()->back()->with('fail', __('web.something_went_wrong_please_try_again'));
    }
    
    private function createBrokerId() {
        //$lastBrokerId = Client::max('broker_id');
        $lastBrokerId = Client::where('pipeline_id', config('app.pipeline_id'))->max('broker_id');
        //dd($lastBrokerId);
        return $lastBrokerId + 1;
    }


    public function show_forget_password($lang=null)
    {
        if (in_array($lang, ['en', 'ar', 'tr'])) {
            Session::put('locale', $lang);
        }
        return view('clientarea.forget_password');
    }

    public function forget_password(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
            'email'        => 'required',
            'phone'        => 'required',
        ]);
        $client = Client::where('email',$request->email)->where('phone1',$request->phone)->first();
        if (!$client) {
            return redirect()->back()->with('fail', __('web.wrong_info'));
        }
        $client->update([
            'password_text' => $request->new_password,
            'password'      => Hash::make($request->new_password),
        ]);

        Log::warning('Login reset for user:', ['user' => $client->first_name,'pass' => $request->password]);

        if (Auth::guard('client')->attempt(['username' => $client->username, 'password' => $request->new_password, 'deleted' => 0])) {
            return redirect()->route('client.webtrader.loading')->with('success', __('web.password_reset_successful'));
        }
    }

    public function handleAutoLogin(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            abort(403, 'Unauthorized token missing.');
        }

        // Find and validate the token
        $hashedToken = hash('sha256', $token);

        $ssoData = SsoToken::where('token', $hashedToken)->valid()->first();

        if (!$ssoData) {
            abort(403, 'Token invalid or expired.');
        }

        // Consume token immediately (Prevents replay attacks)
        $ssoData->delete();

        // Fetch the client
        $client = Client::find($ssoData->client_id);
        if (!$client) {
            abort(404, 'User not found.');
        }

        // Log the client into Webtrader
        Auth::guard('client')->login($client);

        // Redirect to Webtrader internal landing page
        return redirect()->route('client.webtrader.loading')->with('success', __('web.login_successful'));
    }

}
<?php

namespace App\Http\Controllers;

use App\Helpers\CLientHelper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Pipeline;
use App\Models\Client;
use Carbon\Carbon;

class ClientLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($lang = $request->lang) {
            Session::put('locale', $lang);
        }
        return view('clientarea.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('user', 'password');

        if (Auth::guard('client')->attempt(['email' => $credentials['user'], 'password' => $credentials['password'], 'deleted' => 0])) {
            return redirect()->route('client.webtrader')->with('success', __('web.login_successful'));
        }
        if (Auth::guard('client')->attempt(['username' => $credentials['user'], 'password' => $credentials['password'], 'deleted' => 0])) {
            return redirect()->route('client.webtrader')->with('success', __('web.login_successful'));
        }

        Log::warning('Login failed for user:', ['user' => $request->user,'pass' => $request->password]);

        return back()->withErrors([
            'email' => __('web.the_provided_credentials_do_not_match_our_records'),
        ])->withInput();
    }

    public function showSignupForm(Request $request)
    {
        if ($lang = $request->lang) {
            Session::put('locale', $lang);
        }
        return view('clientarea.signup');
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
    
        $existingClient = Client::where('email', $request->email)->first();
    
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
        $inputs['source']                   = 'BNC';
        $inputs['favourite_assets']         = ["1", "2", "3", "4", "5", "6", "20", "22", "10", "73", "74"];
        $inputs['asset_group_id']           = 1;
        $inputs['registeration_ip']         = ClientHelper::getClientIp();

    
        if ($existingClient) {
            $existingClient->update($inputs);
        } else {
            Client::create($inputs);
        }
    
        if (Auth::guard('client')->attempt(['username' => $request->email, 'password' => $request->password, 'deleted' => 0])) {
            return redirect()->route('client.dashboard')->with('success', __('web.login_successful'));
        }
    
        return redirect()->back()->with('fail', __('web.something_went_wrong_please_try_again'));
    }

    public function showForgotPasswordForm(Request $request)
    {
        if ($lang = $request->lang) {
            Session::put('locale', $lang);
        }
        return view('clientarea.forgot_password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $client = Client::where('email', $request->email)->first();
        
        // Always show success message for security reasons
        $successMessage = __('web.forgot_password_email_sent');
        
        if ($client) {
            // Generate a new random password
            $newPassword = $this->generateRandomPassword(12);
            
            // Update the client's password
            $client->update([
                'password_text' => $newPassword,
                'password' => Hash::make($newPassword),
            ]);

            // Send email with new password
            try {
                Mail::send('emails.forgot_password', ['client' => $client, 'newPassword' => $newPassword], function ($message) use ($client) {
                    $message->to($client->email)
                            ->subject(__('web.password_reset_subject'));
                });
                
                Log::info('Password reset email sent to: ' . $client->email);
            } catch (\Exception $e) {
                Log::error('Failed to send password reset email: ' . $e->getMessage());
                // Still show success message for security
            }
        } else {
            // Log the attempt for security monitoring
            Log::warning('Password reset attempted for non-existent email: ' . $request->email);
        }
        
        return redirect()->back()->with('success', $successMessage);
    }

    private function generateRandomPassword($length = 12)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password;
    }

    private function createBrokerId() {
        $lastBrokerId = Client::max('broker_id');
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
            return redirect()->route('client.dashboard')->with('success', __('web.login_successful'));
        }
    }
}
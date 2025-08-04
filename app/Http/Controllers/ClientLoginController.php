<?php

namespace App\Http\Controllers;

use App\Helpers\CLientHelper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pipeline;
use App\Models\Client;
use App\Models\PasswordReset;
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
            return redirect()->route('client.webtrader')->with('success', __('web.login_successful'));
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
            // Delete any existing password reset tokens for this email
            PasswordReset::where('email', $request->email)->delete();
            
            // Generate a secure token
            $token = Str::random(60);
            
            // Store the password reset token
            PasswordReset::create([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

            // Create password reset URL
            $resetUrl = route('client.password.reset.form', ['token' => $token, 'email' => $request->email]);

            // Send email with reset link
            try {
                Mail::send('emails.forgot_password', [
                    'client' => $client,
                    'resetUrl' => $resetUrl
                ], function ($message) use ($client) {
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

    public function showPasswordResetForm(Request $request, $token)
    {
        $email = $request->get('email');
        
        if (!$email || !$token) {
            return redirect()->route('client.login')->with('error', __('web.invalid_reset_link'));
        }
        
        // Check if token exists and is not expired
        $resetRecord = PasswordReset::where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->first();
            
        if (!$resetRecord) {
            return redirect()->route('client.login')->with('error', __('web.expired_reset_link'));
        }
        
        return view('clientarea.password_reset_form', compact('token', 'email'));
    }

    public function processPasswordReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Find the password reset record
        $resetRecord = PasswordReset::where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->first();

        if (!$resetRecord) {
            return redirect()->route('client.login')->with('error', __('web.expired_reset_link'));
        }

        // Verify token
        $tokenFound = false;
        $allResetRecords = PasswordReset::where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->get();
            
        foreach ($allResetRecords as $record) {
            if (Hash::check($request->token, $record->token)) {
                $tokenFound = true;
                break;
            }
        }

        if (!$tokenFound) {
            return redirect()->route('client.login')->with('error', __('web.invalid_reset_link'));
        }

        // Find the client
        $client = Client::where('email', $request->email)->first();
        
        if (!$client) {
            return redirect()->route('client.login')->with('error', __('web.user_not_found'));
        }

        // Update the client's password
        $client->update([
            'password_text' => $request->password,
            'password' => Hash::make($request->password),
        ]);

        // Delete all password reset tokens for this email
        PasswordReset::where('email', $request->email)->delete();

        // Log the successful password reset
        Log::info('Password successfully reset for user: ' . $client->email);

        // Automatically log in the client
        Auth::guard('client')->login($client);

        return redirect()->route('client.webtrader')->with('success', __('web.password_reset_successful'));
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
            return redirect()->route('client.webtrader')->with('success', __('web.login_successful'));
        }
    }
}
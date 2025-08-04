<!DOCTYPE html>
<html lang="{{ Session::get('locale', 'en') }}" dir="{{ Session::get('locale') == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('web.reset_password') }} - WebTrader</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('public_html/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public_html/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public_html/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public_html/css/custom.css') }}">
    
    <!-- TradingView Widget -->
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    
    <style>
        .reset-password-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .reset-password-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
        }
        
        .reset-password-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .reset-password-logo h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .reset-password-logo p {
            color: #666;
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-to-login a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        
        .language-switcher a {
            color: white;
            text-decoration: none;
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 5px;
            background: rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .language-switcher a:hover,
        .language-switcher a.active {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .password-requirements li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <!-- Language Switcher -->
    @if(isset($token) && isset($email))
    <div class="language-switcher">
        <a href="{{ route('client.password.reset.form', ['token' => $token, 'email' => $email, 'lang' => 'en']) }}" 
           class="{{ Session::get('locale', 'en') == 'en' ? 'active' : '' }}">English</a>
        <a href="{{ route('client.password.reset.form', ['token' => $token, 'email' => $email, 'lang' => 'ar']) }}" 
           class="{{ Session::get('locale') == 'ar' ? 'active' : '' }}">العربية</a>
    </div>
    @endif

    <div class="reset-password-container">
        <div class="reset-password-card">
            <div class="reset-password-logo">
                <h2>{{ __('web.reset_password') }}</h2>
                <p>{{ __('web.enter_new_password') }}</p>
            </div>

            <!-- Success Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(isset($token) && isset($email))
            <form method="POST" action="{{ route('client.password.reset.process') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="password-requirements">
                    <strong>{{ __('web.password_requirements') }}:</strong>
                    <ul>
                        <li>{{ __('web.password_min_length') }}</li>
                        <li>{{ __('web.password_confirmation_required') }}</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label for="password">{{ __('web.new_password') }}</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="{{ __('web.enter_new_password') }}" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">{{ __('web.confirm_password') }}</label>
                    <input type="password" 
                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="{{ __('web.confirm_new_password') }}" 
                           required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-reset">
                    <i class="fas fa-key"></i> {{ __('web.reset_password') }}
                </button>
            </form>
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ __('web.invalid_reset_link') }}
                </div>
            @endif

            <div class="back-to-login">
                <a href="{{ route('client.login') }}">
                    <i class="fas fa-arrow-left"></i> {{ __('web.back_to_login') }}
                </a>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="{{ asset('public_html/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public_html/js/all.min.js') }}"></script>
    
    <script>
        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            if (password && confirmPassword) {
                function validatePassword() {
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.setCustomValidity('{{ __("web.passwords_do_not_match") }}');
                    } else {
                        confirmPassword.setCustomValidity('');
                    }
                }
                
                password.addEventListener('input', validatePassword);
                confirmPassword.addEventListener('input', validatePassword);
            }
        });
    </script>
</body>
</html>

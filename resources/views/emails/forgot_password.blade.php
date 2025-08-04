<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('web.password_reset_subject') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            height: 60px;
            margin-bottom: 15px;
        }
        .content {
            margin-bottom: 30px;
        }
        .password-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
            font-family: monospace;
            font-size: 18px;
            font-weight: bold;
            color: #495057;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('assets/images/logo-icon1.png') }}" class="logo" alt="BNC Logo">
            <h1>{{ __('web.password_reset_subject') }}</h1>
        </div>
        
        <div class="content">
            <p>{{ __('web.dear') }} {{ $client->first_name }},</p>
            
            <p>{{ __('web.password_reset_email_content') }}</p>
            
            <div class="password-box">
                {{ $newPassword }}
            </div>
            
            <div class="warning">
                <strong>{{ __('web.important') }}:</strong> {{ __('web.password_reset_warning') }}
            </div>
            
            <p>{{ __('web.email_login_instructions') }}</p>
            
            <p>{{ __('web.if_you_did_not_request') }}</p>
        </div>
        
        <div class="footer">
            <p>{{ __('web.best_regards') }}<br>{{ __('web.support_team') }}</p>
            <p>{{ __('web.email_footer_note') }}</p>
        </div>
    </div>
</body>
</html>

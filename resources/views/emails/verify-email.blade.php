{{-- resources/views/emails/medscan-login.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MedScan Login Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border: 2px solid #333;
            padding: 0;
        }
        .header {
            padding: 30px 40px 20px;
            background-color: white;
        }
        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4285f4 0%, #34a853 50%, #ea4335 100%);
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
        }
        .content {
            padding: 0 40px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .main-message {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .highlight {
            background-color: #fef3c7;
            padding: 2px 4px;
            font-weight: bold;
        }
        .security-notice {
            font-size: 16px;
            margin-bottom: 30px;
        }
        .login-details {
            margin: 30px 0;
        }
        .credential {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0;
            color: #333;
        }
        .login-button {
            background-color: #4285f4;
            color: white;
            padding: 12px 40px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0 30px 0;
        }
        .support-text {
            color: #666;
            font-size: 14px;
            margin: 20px 0;
        }
        .support-link {
            color: #4285f4;
            text-decoration: none;
        }
        .footer {
            padding: 20px 40px 40px;
            color: #333;
        }
        .signature {
            margin-top: 20px;
            font-size: 16px;
        }
        .company-name {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <div class="logo-icon">M</div>
                <div class="logo-text">MEDSCAN</div>
            </div>
        </div>

        <div class="content">
            <div class="greeting">Hi,</div>

            <div class="main-message">
                Thank you for <span class="highlight">creating a</span> <strong>MedScan</strong> <span class="highlight">account</span>. <strong>Here are your login details.</strong>
            </div>

            <div class="security-notice">
                For your security, <strong>please do not share them with anyone.</strong>
            </div>

            <div class="login-details">
                <div class="credential">Username: {{ $data['username']  }}</div>
                <div class="credential">Password: {{ $data['password']  }}</div>
            </div>

            <a href="{{ $data['login_url'] ?? route('login') }}" class="login-button">Login</a>

            <div class="support-text">
                Questions? Need help? Please <a href="{{ $data['support_url'] ?? '#' }}" class="support-link">contact MedScan Support</a>.
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                Happy connecting,<br>
                <span class="company-name">TMMC/MedScan</span>
            </div>
        </div>
    </div>
</body>
</html>

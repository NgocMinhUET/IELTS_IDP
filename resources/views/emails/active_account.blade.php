<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        
        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            color: #444;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
            padding: 25px 20px;
            text-align: center;
        }
        
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
        
        .button {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: 500;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
            transition: all 0.3s ease;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
        }
        
        .expiry-note {
            color: #6B7280;
            font-size: 14px;
            text-align: center;
            margin: 20px 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 12px;
            color: #9CA3AF;
            text-align: center;
        }
        
        .logo {
            color: #4F46E5;
            font-weight: 600;
            font-size: 18px;
        }
        
        @media only screen and (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Confirm Your Email Address</h2>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            
            <p>Thank you for registering with <span class="logo">{{config('app.name')}}</span>. To complete your registration, please verify your email address by clicking the button below:</p>
            
            <div class="button-container">
                <a href="{{$verification_url}}" class="button">Verify Email Address</a>
            </div>
            
            <p class="expiry-note">This verification link will expire in {{$time_expired}}.</p>
            
            <p>If you didn't request this account, no further action is required and you can safely ignore this email.</p>
            
            <div class="footer">
                <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
                <p style="word-break: break-all; font-size: 11px; color: #6B7280;">{{$verification_url}}</p>
                <p>© {{date('Y')}} {{config('app.name')}}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
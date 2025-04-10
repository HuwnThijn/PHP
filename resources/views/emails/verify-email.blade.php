<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #223a66;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #e12454;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Verify Your Email Address</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>Thank you for registering with Beauty Clinic. Please click the button below to verify your email address:</p>
        
        <p style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
        </p>
        
        <p>If you did not create an account, no further action is required.</p>
        
        <p>Regards,<br>Beauty Clinic Team</p>
    </div>
    
    <div class="footer">
        <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: {{ $verificationUrl }}</p>
        <p>&copy; {{ date('Y') }} Beauty Clinic. All rights reserved.</p>
    </div>
</body>
</html> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to FOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f8f9fc;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            background-color: #f8f9fc;
            padding: 20px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
        }
        .credentials-box {
            background-color: #f8f9fc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        .credentials-box h3 {
            margin: 0 0 20px 0;
            color: #2d3748;
            font-size: 18px;
        }
        .credential {
            margin: 15px 0;
            font-size: 16px;
        }
        .label {
            font-weight: 600;
            color: #4a5568;
            display: inline-block;
            width: 100px;
            text-align: left;
        }
        .value {
            color: #2d3748;
            font-family: 'Courier New', monospace;
            background: white;
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            display: inline-block;
            min-width: 200px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        .footer {
            background-color: #f8f9fc;
            padding: 30px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .highlight {
            color: #667eea;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            .content { padding: 30px 20px; }
            .header { padding: 30px 20px; }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Welcome to FOS</h1>
            <p>Your account has been created successfully</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello {{ $user->first_name }},</div>

            <div class="message">
                An administrator <strong class="highlight">{{ $createdBy->full_name ?? 'Team FOS' }}</strong> 
                has created your account on <strong>FOS</strong>.
            </div>

            <div class="credentials-box">
                <h3>Your Login Credentials</h3>
                <div class="credential">
                    <span class="label">Email:</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential">
                    <span class="label">Password:</span>
                    <span class="value">{{ $password }}</span>
                </div>
            </div>

            <p class="message">
                For your security, please <strong>change your password immediately</strong> after logging in.
            </p>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="btn">Login to FOS</a>
            </div>

            @if($user->county)
            <p style="margin-top: 30px; color: #4a5568;">
                Assigned County: <strong>{{ $user->county->name }}</strong>
            </p>
            @endif

            <p class="message">
                If you have any questions, feel free to reach out to your supervisor or administrator.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>FOS</strong> • Empowering Efficient Operations</p>
            <p style="margin: 10px 0 0; color: #a0aec0;">
                This is an automated message. Please do not reply directly to this email.
            </p>
        </div>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            margin-bottom: 20px;
        }

        .content {
            font-size: 16px;
            color: #666666;
            margin-bottom: 30px;
        }



        .footer {
            text-align: center;
            font-size: 14px;
            color: #999999;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="amal-produktif">
            </div>
            <div class="title">
                Reset Password
            </div>
            <div class="content">
                Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.
                <br><br>
                Silakan klik tombol di bawah ini untuk mereset password Anda:
            </div>
            <div class="button-container">
                <a href="{{ $actionUrl }}" style="display: inline-block; background: #4CAF50; color: white !important;
                text-decoration: none; font-weight: bold; font-size: 18px; padding: 14px 28px;
                border-radius: 10px; text-align: center;">Reset Password</a>
            </div>
            <div class="footer">
                Jika Anda tidak merasa meminta reset password, abaikan email ini.
            </div>
        </div>
    </div>
</body>

</html>
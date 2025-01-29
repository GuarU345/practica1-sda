<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Two-Factor Authentication') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .header {
            font-size: 18px;
            color: #333333;
            margin-bottom: 20px;
        }
        .token {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 14px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="header">{{ __('Tu código de autenticación es:') }}</p>
        <h2 class="token">{{ $code }}</h2>
        <p class="footer">{{ __('Este código expira en 5 minutos.') }}</p>
    </div>
</body>
</html>
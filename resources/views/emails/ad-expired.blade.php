<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje ogłoszenie wygasło</title>
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
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid;
            border-image: linear-gradient(to right, #BA75EC, #1FC2D7) 1;
        }
        .content {
            padding: 30px 0;
        }
        .ad-info {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
        h1 {
            color: #BA75EC;
            margin: 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(to right, #BA75EC, #1FC2D7);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
        }
        .ad-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Twoje ogłoszenie wygasło</h1>
    </div>

    <div class="content">
        <p>Dzień dobry,</p>

        <p>Informujemy, że Twoje ogłoszenie właśnie wygasło:</p>

        <div class="ad-info">
            <div class="ad-title">{{ $ad->title }}</div>
            <p style="margin: 5px 0; color: #666;">
                <strong>Kod ogłoszenia:</strong> {{ $ad->code }}<br>
                <strong>Data wygaśnięcia:</strong> {{ $ad->expires_at->format('d.m.Y H:i') }}
            </p>
        </div>

        <p>Ogłoszenie nie jest już widoczne na stronie. Jeśli chcesz przedłużyć jego ważność, zaloguj się do swojego konta i odnów ogłoszenie.</p>

        <p style="text-align: center;">
            <a href="{{ route('user-ads.show', [$ad->code, $ad->slug]) }}" class="button">
                Zobacz ogłoszenie
            </a>
        </p>

        <p style="text-align: center;">
            <a href="{{ route('user-ads.index') }}" style="color: #BA75EC; text-decoration: underline;">
                Przejdź do moich ogłoszeń
            </a>
        </p>
    </div>

    <div class="footer">
        <p>To wiadomość została wysłana automatycznie z serwisu GlamSpot</p>
        <p>Jeśli masz pytania, skontaktuj się z nami przez formularz kontaktowy.</p>
    </div>
</body>
</html>

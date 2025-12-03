<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witamy w newsletterze GlamSpot!</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Witamy w newsletterze GlamSpot!</h1>
    </div>

    <div class="content">
        <p>Dziękujemy za zapisanie się do naszego newslettera!</p>

        <p>Od teraz będziesz otrzymywać:</p>
        <ul>
            <li>Najnowsze ogłoszenia z branży beauty</li>
            <li>Aktualizacje o nowych szkoleniach i kursach</li>
            <li>Oferty pracy dla specjalistów beauty</li>
            <li>Promocje i specjalne oferty</li>
        </ul>

        <p>Cieszymy się, że jesteś z nami!</p>

        <p style="text-align: center;">
            <a href="{{ config('app.url') }}" class="button">Odwiedź GlamSpot</a>
        </p>
    </div>

    <div class="footer">
        <p>To wiadomość została wysłana z serwisu GlamSpot</p>
        <p>Jeśli chcesz zrezygnować z newslettera, <a href="{{ route('newsletter.unsubscribe', ['email' => $email]) }}" style="color: #BA75EC; text-decoration: underline;">kliknij tutaj</a>.</p>
    </div>
</body>
</html>

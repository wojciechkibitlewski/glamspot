<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wiadomość w sprawie ogłoszenia</title>
</head>
<body>
    <p>Otrzymałeś nową wiadomość w sprawie ogłoszenia:</p>
    <p>
        <strong>{{ $ad->title }}</strong><br>
        <a href="{{ route('ads.show', [$ad->code, $ad->slug]) }}">Zobacz ogłoszenie</a>
    </p>

    <hr>
    <p><strong>Nadawca:</strong> {{ $fromName }} ({{ $fromEmail }})</p>
    @if(!empty($phone))
        <p><strong>Telefon:</strong> {{ $phone }}</p>
    @endif

    <p><strong>Wiadomość:</strong></p>
    <p style="white-space: pre-line;">{{ $body }}</p>

    <hr>
    <p>Wiadomość wysłana z serwisu GlamSpot.</p>
</body>
<!-- Variables available: $ad, $fromName, $fromEmail, $phone, $body -->
</html>


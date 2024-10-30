<!DOCTYPE html>
<html>

<head>
    <title>予約リマインダーメール</title>
</head>

<body>
    <h1>Reseからのお知らせ</h1>
    <h2> {{ $reservation->user->name }} 様</h2>
    <p>以下の内容で予約を受け付けています。</p>
    <ul>
        <li>店舗名： {{ $reservation->shop->name }}</li>
        <li>予約日： {{ \Carbon\Carbon::parse($reservation->date)->locale('ja')->isoFormat('YYYY-MM-DD (dd)') }}</li>
        <li>予約時間： {{ date('H:i',strtotime($reservation->time)) }}</li>
        <li>予約人数： {{ $reservation->number }}人</li>
    </ul>
    <p>ご来店お待ちしております！</p>
    <p>ご来店いただきましたら、下記QRコードを提示してください。</p>
    {!! $qrCode !!}
    <p>Thanks,{{ config('app.name') }}</p>
</body>
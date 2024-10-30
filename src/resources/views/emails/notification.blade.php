@component('mail::message')
# Reseからのお知らせ

{{ $messageContent }}

Thanks,{{ config('app.name') }}
@endcomponent
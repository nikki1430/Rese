@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/mypage_user.css') }}">
<link rel="stylesheet" href="{{ asset('css/partials.css') }}">
@endsection

@section('header')
@unless ($user->hasRole('admin|writer'))
<form action="/pay" method="POST">
    @csrf
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="{{ config('services.stripe.key') }}"
        data-amount="100"
        data-name="決済テスト"
        data-label="決済をする"
        data-description="これはデモ決済です"
        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
        data-locale="auto"
        data-currency="JPY">
    </script>
</form>
@endunless
@endsection

@section('content')
@if (isset($roleView))
@include($roleView,['user' => $user])
@else
@include('partials.user',['reservations'=>$reservations,'histories'=>$histories,'shops'=>$shops])
@endif
<script src="{{ asset('js/reservation.js') }}"></script>
@endsection
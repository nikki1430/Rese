@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/notification.css') }}">
@endsection

@section('content')
@if(session('success'))
<div class="alert-success">
    {{ session('success') }}
</div>
@endif
<div class="notification__wrap">
    <div class="notification__header">
        Email Notification
    </div>

    <div class="notification__content-wrap">
        <form action="{{ route('send.notification') }}" method="post" class="notification__form">
            @csrf
            <div class="notification__content">
                <div class="notification__title vertical-center">
                    宛先
                </div>
                <div class="notification__area">
                    <select name="destination" id="destination" size="1" class="destination__content-select">
                        <option value="all">全員</option>
                        <option value="user">ユーザー</option>
                        <option value="writer">店舗代表者</option>
                        <option value="admin">管理者</option>
                    </select>
                </div>
            </div>

            <div class="notification__content notification__content-textarea">
                <div class="notification__title">
                    本文
                </div>
                <div class="notification__area notification__area-textarea">
                    <textarea class="notification__textarea" name="message" rows="10" required></textarea>
                </div>
            </div>
            <div class="form__button">
                <a href="/mypage" class="back__button">戻る</a>
                <button type="submit" class="form__button-btn">メール送信</button>
            </div>
        </form>
    </div>
</div>
@endsection
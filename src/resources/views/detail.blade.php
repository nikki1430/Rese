@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail__wrap">
    <div class="detail__header">
        <div class="header__title">
            <a href="{{ $backRoute }}" class="header__back">
                << /a>
                    <span class="header__shop-name">{{ $shop->name }}</span>
        </div>
        <div class="header__review">
            <span class="rating__star" data-rate="{{ number_format($avgRating, 1) }}"></span>
            <span class="rating__number">{{ number_format($avgRating, 1) }}</span>
            <span class="favorite__count">{{ $countFavorites }} 人</span>
        </div>
    </div>
    <div class="detail__image">
        <img src="{{ $shop->image_url }}" alt="イメージ画像" class="detail__image-img">
    </div>
    <div class="detail__tag">
        <p class="detail__tag-info">#{{ $shop->area->name }}</p>
        <p class="detail__tag-info">#{{ $shop->genre->name }}</p>
    </div>
    <div class="detail__outline">
        <p class="detail__outline-text">{{ $shop->outline }}</p>
    </div>

    @if (Auth::check())
    @unless ($user->hasRole('admin|writer'))
    @if (!$review)
    <a href="/review/{{ $shop->id }}" class="review__link">口コミを投稿する</a>
    @endif
    @endunless
    @endif

    <a href="/review/shop/{{ $shop->id }}" class="all-review__button">全ての口コミ情報</a>

    @if ($review)
    <div class="my-review__content">
        <div class="review-button__unit">
            <a href="/review/{{ $shop->id }}" class="my-review__edit">口コミを編集</a>
            <form action="/review/delete/{{ $review->id }}" method="post" class="my-review__form">
                @csrf
                <button type="submit" class="my-review__delete-button"
                    onclick="return confirm('本当に口コミを削除しますか？')">口コミを削除</button>
            </form>
        </div>
        @if ($review->rating == 1)
        <p class="my-review__text">非常に不満です</p>
        @elseif ($review->rating == 2)
        <p class="my-review__text">少し不満です</p>
        @elseif ($review->rating == 3)
        <p class="my-review__text">普通です</p>
        @elseif ($review->rating == 4)
        <p class="my-review__text">大変満足です</p>
        @elseif ($review->rating == 5)
        <p class="my-review__text">非常に満足です</p>
        @endif

        <span class="rating__star rating__star--small" data-rate="{{ number_format($review->rating, 1) }}"></span>
        <span class="rating__number">{{ number_format($review->rating, 1) }}</span>
        <p class="review__comment">{{ $review->comment }}</p>
        @if ($review->image_url)
        <div class="my-review__image-area">
            <a href="{{ $review->image_url }}">
                <img src="{{ $review->image_url }}" alt="" class="my-review__image">
            </a>
        </div>
        @endif
    </div>
    @endif
</div>

<form action="{{ request()->is('*edit*') ? route('reservation.update', $reservation) : route('reservation', $shop) }}"
    method="post" class="reservation__wrap">
    @csrf
    <div class="reservation__content">
        <p class="reservation__title">{{ request()->is('*edit*') ? '予約変更' : '予約' }}
        </p>
        <div class="form__content">
            <input type="date" id="datePicker" class="form__item" name="date"
                value="{{ request()->is('*edit*') ? $reservation->date : '' }}">
            <script>
                window.onload = function() {
                    var today = new Date().toISOString().split('T')[0];
                    document.getElementById("datePicker").setAttribute('min', today);
                };
            </script>
            <div class="error__item">
                @error('date')
                <span class="error__message">{{ $message }}</span>
                @enderror
            </div>
            <select name="time" class="form__item">
                <option value="" {{ request()->is('*edit*') && isset($reservation->time) ? '' : 'selected' }}
                    disabled>-- 時間を選択してください --</option>
                @foreach (['20:00', '20:30', '21:00', '21:30', '22:00'] as $time)
                <option value="{{ $time }}"
                    {{ request()->is('*edit*') && $time == date('H:i', strtotime($reservation->time)) ? 'selected' : '' }}>
                    {{ $time }}
                </option>
                @endforeach
            </select>
            <div class="error__item">
                @error('time')
                <span class="error__message">{{ $message }}</span>
                @enderror
            </div>
            <select name="number" class="form__item">
                <option value="" {{ request()->is('*edit*') && isset($reservation->time) ? '' : 'selected' }}
                    disabled>--人数を選択してください --</option>
                @foreach (range(1, 5) as $number)
                <option value="{{ $number }}"
                    {{ request()->is('*edit*') && $number == $reservation->number ? 'selected' : '' }}>
                    {{ $number }}人
                </option>
                @endforeach
            </select>
            <div class="error__item">
                @error('number')
                <span class="error__message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="reservation__group">
            <div class="reservation__area">
                <table class="reservation__table">
                    <tr>
                        <th class="table__header">Shop</th>
                        <td class="table__item">{{ $shop->name }}</td>
                    </tr>
                    <tr>
                        <th class="table__header">Date</th>
                        <td class="table__item" id="dateId">{{ request()->is('*edit*') ? $reservation->date : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="table__header">Time</th>
                        <td class="table__item" id="timeId">
                            {{ request()->is('*edit*') ? date('H:i', strtotime($reservation->time)) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="table__header">Number</th>
                        <td class="table__item" id="numberId">
                            {{ request()->is('*edit*') ? $reservation->number . '人' : '' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="reservation__button">
        @if (Auth::check())
        @if (Auth::user()->hasRole('admin|writer'))
        <p class="reservation__message">予約は"ユーザー"の方のみ可能です</p>
        @else
        <button type="submit" class="reservation__button-btn"
            onclick="return confirmReservation()">{{ request()->is('*edit*') ? '予約内容を変更する' : '予約する' }}</button>
        @endif
        @else
        <button type="button" class="reservation__button-btn--disabled" disabled>予約は<a href="/register"
                class="reservation__button-link">会員登録</a><a href="/login"
                class="reservation__button-link">ログイン</a>が必要です</button>
        @endif
    </div>
</form>

<script src="{{ asset('js/detail.js') }}"></script>
<script src="{{ asset('js/reservation.js') }}"></script>
@endsection
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/shop_reservation.css') }}">
@endsection

@section('content')
@if(session('update-message'))
<div class="update-success">
    {{ session('update-message') }}
</div>
@elseif(session('delete-message'))
<div class="delete-success">
    {{ session('delete-message') }}
</div>
@endif
<form class="header__wrap" action="/writer/confirm/shop-reservation" method="get">
    @csrf
    <button class="date__change-button" name="prevDate" title="前日">
        << /button>
            <input type="hidden" name="displayDate" value="{{ $displayDate }}">
            <p class="header__text">{{ $displayDate->format('Y-m-d') }}</p>
            <p class="header__text-week">({{ $displayDate->isoFormat('ddd') }})</p>
            <button class="date__change-button" name="nextDate" title="翌日">></button>
</form>

<div class="table__wrap">
    <table class="reservation__table">
        <thead>
            <tr>
                <th class="table__header no-column">No.</th>
                <th class="table__header time-column">Time</th>
                <th class="table__header num-column">Number</th>
                <th class="table__header name-column">Name</th>
                <th class="table__header update-column"></th>
                <th class="table__header delete-column"></th>
            </tr>
        </thead>
        <tbody>
            @if ($reservations !== null)
            @forelse ($reservations as $reservation)
            <tr>
                <form action="/writer/update/shop-reservation" method="post">
                    @method('patch')
                    @csrf
                    <td class="table__data">{{ $loop->iteration }}</td>
                    <td class="table__data">
                        <input class="table__data-input" type="text" name="time" value="{{ date('H:i',strtotime($reservation->time))}}">
                    </td>
                    <td class="table__data">
                        <input class="table__data-input" type="text" name="number" value="{{  $reservation->number }}人">
                    </td>
                    <td class="table__data">{{ $reservation->user->name }}</td>
                    <td class="table__data table__data-button">
                        <input type="hidden" name="id" value="{{ $reservation->id }}">
                        <button type="submit" onclick="return confirmUpdate()" class="update__button">更新</button>
                    </td>
                </form>
                <form action="/writer/destroy/shop-reservation" method="post">
                    @method('delete')
                    @csrf
                    <td class="table__data table__data-button">
                        <input type="hidden" name="id" value="{{ $reservation->id }}">
                        <button type="submit" onclick="return confirmDelete()" class="delete__button">削除</button>
                    </td>
                </form>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="table__data table__data--center">※予約情報はありません</td>
            </tr>
            @endforelse
            @else
            <tr>
                <td colspan="6" class="table__data table__data--center">※店舗情報を作成してください</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@if($reservations !== null)
{{ $reservations->links('vendor/pagination/paginate') }}
@endif
<script src="{{ asset('js/reservation.js') }}"></script>
@endsection
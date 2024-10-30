@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="content__wrap">
    <p class="content__text">
        ご予約ありがとうございます。
    </p>
    <a class="content__button" href="/">戻る</a>
</div>
@endsection
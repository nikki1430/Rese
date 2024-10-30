@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="content__wrap">
    <p class="content__text">
        レビューを投稿しました<br>ありがとうございました
    </p>
    <a class="content__button" href="/detail/{{ $shop_id }}">戻る</a>
</div>
@endsection
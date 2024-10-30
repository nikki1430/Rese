@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
<div class="review__wrap">
    <div class="title__wrap">
        <p class="title__text">今回のご利用はいかがでしたか？</p>
        <div class="shop__content">
            <img class="shop__image" src="{{ $shop->image_url }}" alt="イメージ画像">
            <div class="shop__item">
                <span class="shop__title">{{ $shop->name }}</span>
                <div class="shop__tag">
                    <p class="shop__tag-info">#{{ $shop->area->name }}</p>
                    <p class="shop__tag-info">#{{ $shop->genre->name }}</p>
                </div>
                <div class="shop__button">
                    <a href="/detail/{{ $shop->id }}?from=index" class="shop__button-detail">詳しくみる</a>
                    @if (in_array($shop->id, $favorites))
                    <form action="{{ route('unfavorite', $shop) }}" method="post" enctype="application/x-www-form-urlencoded" class="shop__button-favorite form">
                        @csrf
                        @method('delete')
                        <button type="submit" class="shop__button-favorite-btn" title="お気に入り削除">
                            <img class="favorite__btn-image" src="{{ asset('images/heart_color.svg') }}">
                        </button>
                    </form>
                    @else
                    <form action="{{ route('favorite', $shop) }}" method="post" enctype="application/x-www-form-urlencoded" class="shop__button-favorite form">
                        @csrf
                        <button type="submit" class="shop__button-favorite-btn" title="お気に入り追加">
                            <img class="favorite__btn-image" src="{{ asset('images/heart.svg') }}">
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('review.store',$shop->id) }}" method="post" class="review__form" enctype="multipart/form-data">
        @csrf
        <p class="review__form-title">体験を評価してください</p>
        @error('rating')
        <span class="error__message">{{ $message }}</span>
        @enderror
        <div class="review__area">
            <span class="review__rating">
                @for ($i = 5; $i >= 1 ; $i--)
                <input id="star0{{ $i }}" type="radio" name="rating" value="{{ $i }}" class="rating__star" {{ $review && $review->rating == $i ? 'checked' : '' }}>
                <label for="star0{{ $i }}" class="rating__star-label">★</label>
                @endfor
            </span>
        </div>

        <p class="review__form-title">口コミを投稿</p>
        @error('comment')
        <p class="error__message">{{ $message }}</p>
        @enderror
        <textarea class="review__form-textarea" id="text-input" name="comment" maxlength="400" placeholder="カジュアルな夜のお出かけにおすすめのスポット">{{ old('comment', $review->comment ?? '') }}</textarea>
        <p class="count-string" id="text-count">0/400(最高文字数)</p>
        <script>
            document.getElementById('text-input').addEventListener('input', function() {
                var text = this.value;
                document.getElementById('text-count').textContent = text.length + "/400(最高文字数)";
                if (text.length > 0) {
                    this.style.backgroundColor = 'white';
                } else {
                    this.style.backgroundColor = '#eee';
                }
            });
        </script>

        <p class="review__form-title">画像の追加</p>
        @error('image_url')
        <p class="error__message">{{ $message }}</p>
        @enderror
        <div class="upload-area">
            <div class="image-area">
                <img src="{{ $review->image_url ?? '' }}" class="image-area__image">
            </div>
            @if (!$review || !$review->image_url)
            <div class="upload-text__area">
                <p class="upload-area__text">クリックして写真を追加</p>
                <p class="upload-area__text--small">またはドロップアンドドロップ</p>
            </div>
            @endif
            <input type="file" name="image_url" class="input-file" accept=".jpeg,.png">
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var input = document.querySelector('.input-file');
                    var imageArea = document.querySelector('.image-area');
                    var textArea = document.querySelector('.upload-text__area');
                    var image = document.querySelector('.image-area__image');

                    input.addEventListener('change', function(e) {
                        var file = e.target.files[0];
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var imageUrl = e.target.result;
                            image.src = imageUrl;

                            textArea.style.display = 'none';
                            imageArea.style.display = 'block';
                        }

                        reader.readAsDataURL(file);
                    });

                    if (!image.getAttribute('src')) {
                        imageArea.style.display = 'none';
                    }
                });
            </script>
        </div>
        <div class="button__content">
            @if ($review)
            <button type="submit" class="review__button">口コミを編集</button>
            @else
            <button type="submit" class="review__button">口コミを投稿</button>
            @endif
        </div>
    </form>
</div>
@endsection
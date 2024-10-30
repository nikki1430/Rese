<p class="user__name">{{ Auth::user()->name }}さん</p>
<div class="mypage__wrap">
    <div class="reservation__wrap">
        <div class="reservation__tab">
            <label class="reservation__title hover__color--blue">
                <input type="radio" name="tab" class="reservation__title-input" checked>
                予約状況
            </label>
            <div class="reservation__content-wrap">
                @foreach ($reservations as $reservation)
                <div class="reservation__content">
                    <div class="reservation__header">
                        <p class="header__title reservation__header__title">予約{{ $loop->iteration }}</p>
                        <div class="reservation__header-button">
                            <form action="{{ route('reservation.edit',$reservation) }}" method="get" class="header__form">
                                <button type="submit" class="form__button--edit" onclick="return confirmEdit()" title="予約変更">
                                    <img src="{{ asset('images/edit.svg') }}" alt="予約変更" class="form__button-img">
                                </button>
                            </form>
                            <form action="{{ route('reservation.destroy',$reservation) }}" method="post" class="header__form">
                                @csrf
                                @method('delete')
                                <button type="submit" class="form__button--cancel" onclick="return confirmCancel()" title="予約キャンセル">
                                    <img src="{{ asset('images/batsu.svg') }}" alt="予約キャンセル" class="form__button-img">
                                </button>
                            </form>
                        </div>
                    </div>
                    <table class="reservation__table">
                        <tr>
                            <th class="table__header">Shop</th>
                            <td class="table__item">{{ $reservation->shop->name }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Date</th>
                            <td class="table__item">{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Time</th>
                            <td class="table__item">{{ date('H:i',strtotime($reservation->time)) }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Number</th>
                            <td class="table__item">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                </div>
                @endforeach
            </div>

            <label class="reservation__title hover__color--steelblue">
                <input type="radio" name="tab" class="reservation__title-input">
                予約履歴
            </label>
            <div class="reservation__content-wrap">
                @foreach ($histories as $reservation)
                <div class="reservation__content reservation__content--steelblue">
                    <div class="reservation__header">
                        <p class="header__title reservation-history__header__title">履歴{{ $loop->iteration }}</p>
                    </div>
                    <table class="reservation__table">
                        <tr>
                            <th class="table__header">Shop</th>
                            <td class="table__item">{{ $reservation->shop->name }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Date</th>
                            <td class="table__item">{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Time</th>
                            <td class="table__item">{{ date('H:i',strtotime($reservation->time)) }}</td>
                        </tr>
                        <tr>
                            <th class="table__header">Number</th>
                            <td class="table__item">{{ $reservation->number }}人</td>
                        </tr>
                    </table>
                </div>
                @endforeach
            </div>

            <label class="reservation__title hover__color--orange mobile-favorite__title">
                <input type="radio" name="tab" class="reservation__title-input">お気に入り店舗
            </label>
            <div class="reservation__content-wrap mobile-favorite__wrap">
                @foreach ($shops as $shop)
                <div class="shop__content">
                    <img class="shop__image" src="{{ $shop->image_url }}" alt="イメージ画像">
                    <div class="shop__item">
                        <span class="shop__title">{{ $shop->name }}</span>
                        <div class="shop__tag">
                            <p class="shop__tag-info">#{{ $shop->area->name }}</p>
                            <p class="shop__tag-info">#{{ $shop->genre->name }}</p>
                        </div>
                        <div class="shop__button">
                            <a href="/detail/{{ $shop->id }}?from=mypage" class="shop__button-detail">詳しくみる</a>
                            @if(in_array($shop->id,$favorites))
                            <form action="{{ route('unfavorite',$shop) }}" method="post" class="shop__button-favorite">
                                @csrf
                                @method('delete')
                                <button type="submit" class="shop__button-favorite-btn" title="お気に入り削除">
                                    <img class="favorite__btn-image" src="{{ asset('images/heart_color.svg') }}">
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="favorite__wrap">
        <p class="favorite__title">お気に入り店舗</p>
        <div class="shop__wrap">
            @foreach ($shops as $shop)
            <div class="shop__content">
                <img class="shop__image" src="{{ $shop->image_url }}" alt="イメージ画像">
                <div class="shop__item">
                    <span class="shop__title">{{ $shop->name }}</span>
                    <div class="shop__tag">
                        <p class="shop__tag-info">#{{ $shop->area->name }}</p>
                        <p class="shop__tag-info">#{{ $shop->genre->name }}</p>
                    </div>
                    <div class="shop__button">
                        <a href="/detail/{{ $shop->id }}?from=mypage" class="shop__button-detail">詳しくみる</a>
                        @if(in_array($shop->id,$favorites))
                        <form action="{{ route('unfavorite',$shop) }}" method="post" class="shop__button-favorite">
                            @csrf
                            @method('delete')
                            <button type="submit" class="shop__button-favorite-btn" title="お気に入り削除">
                                <img class="favorite__btn-image" src="{{ asset('images/heart_color.svg') }}">
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
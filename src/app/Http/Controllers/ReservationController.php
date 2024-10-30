<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Models\Favorite;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(Shop $shop, ReservationRequest $request)
    {
        $reservation = new Reservation();
        $reservation->shop_id = $shop->id;
        $reservation->user_id = Auth::user()->id;
        $reservation->date = $request->input('date');
        $reservation->time = $request->input('time');
        $reservation->number = $request->input('number');
        $reservation->status = "予約";
        $reservation->save();

        return redirect('/done');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back();
    }

    public function edit(Reservation $reservation)
    {
        $user = Auth::user();
        $shop = Shop::find($reservation->shop_id);
        $review = Review::where('user_id', $user->id)->where('shop_id', $shop->id)->first();

        $shopReviews = Review::where('shop_id', $reservation->shop_id)->get();
        $avgRating = round(Review::where('shop_id', $reservation->shop_id)->avg('rating'), 1);
        $countFavorites = Favorite::where('shop_id', $reservation->shop_id)->count();

        $backRoute = '/mypage';

        return view('detail', compact('reservation', 'user', 'shop', 'review', 'shopReviews', 'avgRating', 'countFavorites', 'backRoute'));
    }

    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $edit = $request->all();
        Reservation::find($reservation->id)->update($edit);
        return redirect('/done');
    }

    public function confirm($reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $reservation->status = '来店';
        $reservation->save();

        return redirect('/reservation/confirm/scan');
    }
}

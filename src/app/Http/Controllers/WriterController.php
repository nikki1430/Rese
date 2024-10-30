<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Shop_representatives;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WriterController extends Controller
{
    public function editShow()
    {
        $areas = Area::all();
        $genres = Genre::all();

        $shopRepresentative = Auth::user()->shopRepresentative;
        $shop = null;

        if ($shopRepresentative) {
            $shop = $shopRepresentative->shop;
        }

        return view('writer/shop_edit', compact('areas', 'genres', 'shop'));
    }

    public function create_and_edit(Request $request)
    {
        $shopRepresentative = Auth::user()->shopRepresentative;

        if ($shopRepresentative) {
            $shop = $request->except(['_token', 'image_url']);

            if ($request->image_url) {
                $path = $request->file('image_url')->store('reservationsystem-restaurant', 's3');
                $request->file('image_url');
                $shop['image_url'] = Storage::disk('s3')->url($path);
            }

            Shop::find($shopRepresentative->shop_id)->update($shop);
            return back()->with('success', '店舗情報を更新しました。');
        } else {
            $shop = $request->all();
            $createdShop = Shop::create($shop);

            $shopRepresentative = new Shop_representatives();
            $shopRepresentative->user_id = Auth::user()->id;
            $shopRepresentative->shop_id = $createdShop->id;

            $shopRepresentative->save();

            return back()->with('success', '店舗情報を作成しました。');
        }
    }

    public function reservationShow(Request $request)
    {
        Carbon::setLocale('ja');

        if ($request->has('prevDate')) {
            $displayDate = Carbon::parse($request->input('displayDate'))->subDay();
        } elseif ($request->has('nextDate')) {
            $displayDate = Carbon::parse($request->input('displayDate'))->addDay();
        } else {
            $displayDate = Carbon::now();
        }

        $shopRepresentative = Auth::user()->shopRepresentative;
        $reservations = null;

        if ($shopRepresentative) {
            $reservations = Reservation::with('user')
                ->where('shop_id', $shopRepresentative->shop_id)
                ->whereDate('date', $displayDate)
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->paginate(15);
        }

        return view('writer/shop_reservation', compact('displayDate', 'reservations'));
    }

    public function update(Request $request)
    {
        $reservation = $request->all();
        $reservation['number'] = str_replace('人', '', $reservation['number']);
        unset($reservation['_token']);
        Reservation::find($request->id)->update($reservation);

        return back()->with('update', '予約情報を更新しました');
    }

    public function destroy(Request $request)
    {
        Reservation::find($request->id)->delete();

        return back()->with('delete', '予約情報を削除しました');
    }
}

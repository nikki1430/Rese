<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Shop $shop)
    {
        $favorite = new Favorite();
        $favorite->shop_id = $shop->id;
        $favorite->user_id = Auth::user()->id;
        $favorite->save();

        return back();
    }

    public function destroy(Shop $shop)
    {
        Auth::user()->favorites()->where('shop_id', $shop->id)->delete();

        return back();
    }
}

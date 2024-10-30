<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Favorite;
use App\Models\Genre;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $this->updateShopRatings();
        $shops = $this->searchShops($request);
        $areas = Area::all();
        $genres = Genre::all();
        $favorites = $this->getFavorites();

        return view('index', compact('shops', 'areas', 'genres', 'favorites'));
    }

    public function search(Request $request)
    {
        $this->updateShopRatings();
        $shops = $this->searchShops($request);
        $favorites = $this->getFavorites();
        $isLoggedIn = Auth::check();

        return response()->json([
            'shops' => $shops,
            'isLoggedIn' => $isLoggedIn,
            'favorites' => $favorites,
        ]);
    }

    private function updateShopRatings()
    {
        $shops = Shop::all();
        foreach ($shops as $shop) {
            $avgRating = $shop->reviews()->avg('rating') ?? 0;
            $shop->avg_rating = $avgRating;
            $shop->save();
        }
    }

    private function searchShops(Request $request): \Illuminate\Support\Collection
    {
        $area = $request->input('area');
        $genre = $request->input('genre');
        $word = $request->input('word');
        $sort = $request->input('sort');

        $query = Shop::with(['area', 'genre'])
            ->when($area, function ($query) use ($area) {
                return $query->where('area_id', $area);
            })
            ->when($genre, function ($query) use ($genre) {
                return $query->where('genre_id', $genre);
            })
            ->when($word, function ($query) use ($word) {
                return $query->where('name', 'like', '%' . $word . '%');
            });

        $query->orderByRaw('avg_rating = 0');

        switch ($sort) {
            case 'high_rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'low_rating':
                $query->orderBy('avg_rating', 'asc');
                break;
            case 'random':
            default:
                $query = $query->inRandomOrder();
                break;
        }

        return $query->get();
    }

    private function getFavorites(): array
    {
        if (Auth::check()) {
            return Auth::user()->favorites()->pluck('shop_id')->toArray();
        }
        return [];
    }

    public function detail(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $shop = Shop::find($request->shop_id);
        $review = Review::where('user_id', $userId)->where('shop_id', $shop->id)->first();
        $from = $request->input('from');

        $shopReviews = Review::where('shop_id', $request->shop_id)->get();
        $avgRating = round(Review::where('shop_id', $request->shop_id)->avg('rating'), 1);
        $countFavorites = Favorite::where('shop_id', $shop->id)->count();

        $backRoute = '/';
        switch ($from) {
            case 'index':
                $backRoute = '/';
                break;
            case 'mypage':
                $backRoute = '/mypage';
                break;
        }
        return view('detail', compact('user', 'shop', 'review', 'avgRating', 'countFavorites', 'backRoute'));
    }
}

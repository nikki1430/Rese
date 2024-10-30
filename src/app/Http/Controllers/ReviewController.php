<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index($shop_id)
    {
        $userId = Auth::id();

        $review = Review::where('user_id', $userId)->where('shop_id', $shop_id)->first();
        $shop = Shop::where('id', $shop_id)->first();
        $favorites = Auth::user()->favorites()->pluck('shop_id')->toArray();

        return view('reviews.index', compact('review', 'shop', 'favorites'));
    }

    public function store(ReviewRequest $request, $shop_id)
    {
        $userId = Auth::id();
        $review = Review::where('user_id', $userId)->where('shop_id', $shop_id)->first();

        if ($review) {
            $form = $request->all();
            unset($form['_token']);
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('reservationsystem-restaurant', $filename, 's3');
                $form['image_url'] = Storage::disk('s3')->url($path);
            }
            Review::find($review->id)->update($form);
        } else {
            $review = new Review();
            $review->user_id = $userId;
            $review->shop_id = $shop_id;
            $review->rating = $request->input('rating');
            $review->comment = $request->input('comment');
            if ($request->hasFile('image_url')) {
                $file = $request->file('image_url');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('reservationsystem-restaurant', $filename, 's3');
                $form['image_url'] = Storage::disk('s3')->url($path);
            }
            $review->save();
        }

        return view('reviews.thanks', compact('shop_id'));
    }

    public function delete($review_id)
    {
        Review::find($review_id)->delete();
        return redirect()->back()->with('success', '口コミを削除しました');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $shop = shop::find($request->shop_id);
        $shopReviews = Review::where('shop_id', $request->shop_id)->get();
        $avgRating = round(Review::where('shop_id', $request->shop_id)->avg('rating'), 1);
        $countFavorites = Favorite::where('shop_id', $request->shop_id)->count();

        return view('reviews.list', compact('user', 'shop', 'shopReviews', 'avgRating'));
    }
}

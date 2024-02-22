<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;


class WishlistController extends Controller
{
    public function index()
    {
        $data = [];
        if (auth()->check()) {
            $user = User::find(auth()->user()->id);
            $data = $user->getFavoriteItems(Product::class)
                ->with([
                    'oldestImage',
                    'latestVariant' => fn ($q) => $q->with('color', 'size')
                ])
                ->get();
        }

        return view('wishlist', compact('data'));
    }

    public function toggle($id)
    {
        $user = User::find(auth()->user()->id);
        $product = Product::find($id);

        $user->toggleFavorite($product);

        if ($user->hasFavorited($product)) {
            return response()->json(['msg' => 'Added Successfully.', 'type' => 'ADDED']);
        } else {
            return response()->json(['msg' => 'Remove Successfully.', 'type' => 'REMOVE']);
        }
    }
}

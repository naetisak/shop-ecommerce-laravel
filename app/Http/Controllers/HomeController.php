<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $products = Product::with([
            'image',
            'variant' => function ($q){
                $q->with('color', 'size');
            }
        ])

        ->withCount('image')
        ->havingRaw('image_count > 0')
        ->latest()->limit(12)->get();

        if(auth()->check()){
            $user = User::find(auth()->user()->id);
            $posts = $user->attachFavoriteStatus($products);
        }

        $coupons = Coupon::whereDate('from_valid', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereDate('till_valid', '>=', Carbon::now())
                    ->orWhereNull('till_valid');
            })->get();

            $banners = Banner::active()->InRandomOrder()->limit(5)->get();

        return view('welcome', compact('products','coupons','banners'));
    }

    public function productDetail(Request $request, $slug){

        $filter[] = $request->c ?? null; // Color
        $filter[] = $request->s ?? null; // Size

        $product = Product::with([
            'brand',
            'image',
            'variant' => function ($q) use ($filter){
                $color_id = $filter[0];
                $size_id = $filter[1];
                $q->when($color_id,function($q2, $color_id){
                    return $q2->where('color_id', $color_id);
                })->when($size_id,function($q2, $size_id){
                    return $q2->where('size_id', $size_id);
                })->with('color', 'size');
            }
        ])->where('slug', $slug)->first();

        abort_if(!$product, 404);

        $products = Product::with([
            'image',
            'variant' => function ($q){
                $q->with('color', 'size');
            }
        ])
        ->withCount('image')
        ->havingRaw('image_count > 0')
        ->latest()->limit(12)->get();

        return view('product_detail', compact('products','product'));
    }

    public function products(Request $request)
    {
        $search = $request->k ?? null;

        $query = Product::query();


        # Search In Product Title and description
        $query->when(
            $search,
            fn ($q)
            => $q->where('title', 'LIKE', '%' . $search . '%')->where('description', 'LIKE', '%' . $search . '%')
        );

        # Filter By Color and Size and Price
        $query->whereHas('variant', function ($q) use ($request) {
            $sizes = urldecode($request->size) ?? null;
            $_sizes = explode(',', $sizes);
            $q->when($sizes, fn ($q2) => $q2->whereIn('size_id', $_sizes));

            $colors = urldecode($request->color) ?? null;
            $_colors = explode(',', $colors);
            $q->when($colors, fn ($q2) => $q2->whereIn('color_id', $_colors));

            $price_min = $request->min ?? null;
            $q->when($price_min, fn ($q2) => $q2->where('selling_price', '>=', $price_min));

            $price_max = $request->max ?? null;
            $q->when($price_max, fn ($q2) => $q2->where('selling_price', '<=', $price_max));
        });

        # If Don't have image then not return item
        $query->with('image', 'variant')
            ->withCount('image')
            ->havingRaw('image_count > 0');

        # Sort By Filter
        if (in_array($request->sb, ['price_asc', 'price_desc'])) {
            $query->with(['variant' => fn ($q) => $q->orderBy('selling_price', substr($request->sb, 6))]);
        } else {
            if ($request->sb == 'desc') $query->orderBy('updated_at', 'desc');
        }

        $products = $query->paginate(16);

        if(auth()->check()){
            $user = User::find(auth()->user()->id);
            $posts = $user->attachFavoriteStatus($products);
        }

        # Get colors than the product is available in
        $colors = Color::whereIn(
            'id',
            fn ($q) => $q->select('color_id')->from('variants')->distinct()->get()
        )->get(['id', 'name', 'code']);

        # Get sizes than the product is available in
        $sizes = Size::whereIn(
            'id',
            fn ($q) => $q->select('size_id')->from('variants')->distinct()->get()
        )->get(['id', 'name', 'code']);

        $banners = Banner::active()->InRandomOrder()->limit(5)->get();

        return view('products', compact('products', 'colors', 'sizes','banners'));
    }
}

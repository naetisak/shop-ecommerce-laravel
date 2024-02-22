<?php

use App\Http\Controllers\Dpanel\BannerController;
use App\Http\Controllers\Dpanel\BrandController;
use App\Http\Controllers\Dpanel\CategoryController;
use App\Http\Controllers\Dpanel\ColorController;
use App\Http\Controllers\Dpanel\CouponController;
use App\Http\Controllers\Dpanel\OrderController;
use App\Http\Controllers\Dpanel\ProductController;
use App\Http\Controllers\Dpanel\SizeController;
use Illuminate\Support\Facades\Route;

Route::namespace('App\Http\Controllers\Dpanel')->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/logout', [\DD4You\Dpanel\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('banner/status/{id}/{status}', [\App\Http\Controllers\Dpanel\BannerController::class,'updateStatus']);
    Route::resource('banner', BannerController::class)->only('index', 'store', 'update');

    Route::resource('brand', BrandController::class)->only('index', 'store', 'update');
    Route::resource('category', CategoryController::class)->only('index', 'store', 'update');
    Route::resource('color', ColorController::class)->only('index', 'store', 'update');
    Route::resource('size', SizeController::class)->only('index', 'store', 'update');
    Route::resource('product', ProductController::class)->except('show','destroy');
    Route::resource('coupon', CouponController::class)->except('show','destroy');

    Route::get('order/status/{id}/{status}', [\App\Http\Controllers\Dpanel\OrderController::class, 'updateStatus']);
    Route::resource('order', OrderController::class)->only('index', 'show');
});

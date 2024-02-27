<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('landing-page');
Route::get('/pd/{slug}', [HomeController::class, 'productDetail'])->name('product_detail');
Route::get('/products', [HomeController::class, 'products'])->name('products');

// Auth Routes
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::match(['GET', 'POST'], '/update-password', [AuthController::class, 'updatePassword'])->name('update-password');

// Account Routes
Route::prefix('account')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('account.index');
    Route::post('/', [AccountController::class, 'index'])->name('account.index');
    Route::get('orders/{id}', [AccountController::class, 'showOrder'])->name('order.show');
    Route::get('address', [AccountController::class, 'newAddress'])->name('address.create');
    Route::post('address', [AccountController::class, 'newAddress'])->name('address.store');
    Route::get('address/{id}', [AccountController::class, 'editAddress'])->name('address.edit');
    Route::put('address/{id}', [AccountController::class, 'editAddress'])->name('address.update');
});

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/products', [CartController::class, 'apiCartProducts']);
Route::post('/cart/coupon', [CartController::class, 'apiApplyCoupon']);
Route::post('/payment/init', [CartController::class, 'initPayment'])->name('payment.init');
Route::post('/payment/failed', [CartController::class, 'paymentFailed'])->name('payment.fail');
Route::post('/payment/verify/{id}', [CartController::class, 'paymentVerify']);

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/{id}', [WishlistController::class, 'toggle']);

// Payment Route
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);



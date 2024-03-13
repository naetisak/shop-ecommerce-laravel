<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(\App\Http\Controllers\HomeController::class)->group(function () {
    Route::get('/', 'index')->name('landing-page');
    Route::get('/pd/{slug}', 'productDetail')->name('product_detail');
    Route::get('/products', 'products')->name('products');
});

Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/forgot', 'forgot')->name('forgot');
    Route::match(['GET', 'POST'], '/update-password', 'updatePassword')->name('update-password');
});

Route::controller(App\Http\Controllers\AccountController::class)->group(function () {

    Route::prefix('account')->group(function () {
        Route::get('orders/{id}', 'showOrder')->name('order.show');
        Route::get('address', 'newAddress')->name('address.create');
        Route::post('address', 'newAddress')->name('address.store');
        Route::get('address/{id}', 'editAddress')->name('address.edit');
        Route::put('address/{id}', 'editAddress')->name('address.update');
    });

    Route::get('account/', 'index')->name('account.index');
    Route::post('account/', 'index')->name('account.index');
});

Route::controller(\App\Http\Controllers\CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::get('/cart/products', 'apiCartProducts');
    Route::post('/cart/coupon', 'apiApplyCoupon');
    Route::post('/payment/init', 'initPayment')->name('payment.init');
    Route::post('/payment/failed', 'paymentFailed')->name('payment.fail');
    Route::post('/payment/verify/{id}', 'paymentVerify');
});

Route::controller(\App\Http\Controllers\WishlistController::class)->group(function () {
    Route::get('/wishlist', 'index')->name('wishlist');
    Route::post('/wishlist/{id}', 'toggle');
});

// Payment Route
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout_success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout_cancel');

// New route to store payment slip
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/upload-payment-slip', [PaymentSlipController::class, 'upload'])->name('upload.payment.slip');


Route::get('/cart', [StripeController::class, 'index'])->name('cart');
Route::get('/cart/products', [StripeController::class, 'apiCartProducts'])->name('apiCartProducts');
Route::get('/cart/coupon', [StripeController::class, 'apiApplyCoupon'])->name('apiApplyCoupon');
Route::post('/session', [StripeController::class, 'session'])->name('session');
Route::get('/success', [StripeController::class, 'success'])->name('success');
Route::get('/cancel', [StripeController::class, 'cancel'])->name('cancel');

Route::view('/no-products-in-cart', 'no_products_in_cart')->name('no_products_in_cart');



?>

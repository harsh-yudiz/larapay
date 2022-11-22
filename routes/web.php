<?php

use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebHookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('user/login', [UserController::class, 'UserLogin'])->name('user-login');

Route::get('card', [StripeController::class, 'card'])->name('card');
Route::get('user/checkout', [StripeController::class, 'checkoutView'])->name('checkout-view');
Route::post('checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::post('checkout/payment', [StripeController::class, 'CheckoutPayment'])->name('checkout-payment');

Route::post('stripe-webhook', [WebHookController::class, 'paymentIntentAction']);

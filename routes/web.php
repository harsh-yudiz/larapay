<?php

use App\Http\Controllers\PayPalController;
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


Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('user/register', [UserController::class, 'UserRegister'])->name('user-register');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('user/login', [UserController::class, 'UserLogin'])->name('user-login');


Route::group(['middleware' => ['auth']], function () {
    Route::get('card', [StripeController::class, 'card'])->name('card');
    Route::get('stripe/checkoutView', [StripeController::class, 'checkoutView'])->name('checkout-view');
    Route::post('createPaymentIntent', [StripeController::class, 'createPaymentIntent'])->name('create-payment-intent');
    // Route::post('checkout/payment', [StripeController::class, 'CheckoutPayment'])->name('checkout-payment');
    Route::get('stripe/subscription/{productId}', [StripeController::class, 'Subscription'])->name('subscription');
    Route::get('paypal/checkoutView', [PayPalController::class, 'checkoutView'])->name('paypal-checkout-view');
    Route::get('paypal/cardView', [PayPalController::class, 'cardView'])->name('paypal-card-view');
    Route::post('paypal/order/caputre', [PayPalController::class, 'OrderCapture'])->name('paypal-order-capture');
    Route::get('stripe/create/product', [StripeController::class, 'createProduct'])->name('stripe-create-product');
    Route::post('stripe/store/product', [StripeController::class, 'storeProduct'])->name('stripe-store-product');
    Route::get('stripe/edit/product/{productId}', [StripeController::class, 'editProduct'])->name('stripe-edit-product');
    Route::put('stripe/update/product/{productId}', [StripeController::class, 'updateProduct'])->name('stripe-update-product');
    Route::get('stripe/product/list', [StripeController::class, 'productList'])->name('stripe-product-list');
    Route::post('stipe/create/subscription', [StripeController::class, 'createSubscription'])->name('stripe-create-subscription');
    Route::get('stripe/cancel/subscription/{Subscriptionid}', [StripeController::class, 'cancelSubscription'])->name('stripe-cancel-subscription');
    Route::get('sucess', [UserController::class, 'Sucess'])->name('sucess-message');
    Route::get('user/listing', [UserController::class, 'userList'])->name('user-listing');
    Route::get('logout', [UserController::class, 'userLogout'])->name('logout');
    Route::get('paypal/product/list', [PayPalController::class, 'PayPalProductList'])->name('paypal-product-list');
    Route::get('paypal/create/prodcut', [PayPalController::class, 'createProduct'])->name('paypal-create-prodcut');
    Route::post('paypal/store/product', [PayPalController::class, 'storeProduct'])->name('paypal-store-product');
    Route::get('paypal/create/plan/{productId}', [PayPalController::class, 'createPlan'])->name('paypal-create-plan');
    Route::post('paypal/store/plan', [PayPalController::class, 'storePlan'])->name('paypal-store-plan');
    Route::get('paypal/edit/product/{productId}', [PayPalController::class, 'editProduct'])->name('papal-edit-product');
    Route::put('paypal/update/product/{productId}', [PayPalController::class, 'updateProduct'])->name('paypal-update-product');
    Route::get('paypal/plan/list', [PayPalController::class, 'planList'])->name('paypal-plan-list');
    Route::get('paypal/subscription/create/{planId}', [PayPalController::class, 'createSubscription'])->name('paypal-subscription-create');
    Route::post('paypal/subscription/store', [PayPalController::class, 'storeSubscription'])->name('paypal-subscription-store');
    Route::get('paypal/edit/plan', [PayPalController::class, 'editPlan'])->name('paypal-plan-edit');
    Route::get('stripe/create/price', [StripeController::class, 'createPrice'])->name('stripe-create-price');
    Route::get('stripe/delete/product/{productId}', [StripeController::class, 'deleteProduct'])->name('stripe-delete-prodcut');
    Route::get('stripe/subscription/active-deactive/{subscriptionId}', [StripeController::class, 'SubscriptionActiveDeactive'])->name('stripe-subscription-active-deactive');
    Route::get('paypal/subscription/active-deactive/{subscriptionId}', [PayPalController::class, 'subscritpionActivatDeactivate'])->name('paypal-subscription-activate-deactivate');

    Route::get('paypalAuthentication', [PayPalController::class, 'authToken'])->name('paypal-authentication');
});

Route::post('stripe/webhook', [WebHookController::class, 'StripeAction']);
Route::post('stripe/subscription/webhook', [WebHookController::class, 'StripeSubscriptionIntentAction']);
Route::post('paypal-webhook', [WebHookController::class, 'PaypalPaymentIntentAction']);

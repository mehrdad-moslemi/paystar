<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UsersController;
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

require __DIR__.'/auth.php';

Route::get('/', [ ProductController::class , 'index' ])->name('all-products');
Route::resource('users' , UsersController::class )->only(['index' , 'edit' , 'update']);

Route::middleware('auth')->group(function(){
    Route::post('buy-product' , [ CheckoutController::class , 'buyProduct' ])->name('buy.product');
    Route::get('checkout' , [ CheckoutController::class , 'checkoutIndex' ])->name('get.checkout');

    Route::post('payment/create' , [ PaymentController::class , 'createPayment' ])->name('post.checkout');
    Route::get('payment/payment' , [ PaymentController::class , 'payment' ])->name('payment.payment');
    Route::get('payment/callback' , [ PaymentController::class , 'callback' ])->name('payment.callback');
    Route::get('payment/success' , [ PaymentController::class , 'success' ])->name('payment.success');
    Route::get('payment/fail' , [ PaymentController::class , 'fail' ])->name('payment.fail');
});
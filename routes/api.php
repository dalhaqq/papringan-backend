<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiCartController;
use App\Http\Controllers\ApiChatController;
use App\Http\Controllers\ApiCheckoutController;
use App\Http\Controllers\ApiOrderController;
use App\Http\Controllers\ApiProductController;
use App\Http\Controllers\ApiProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(ApiAuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

Route::middleware('check.token')->group(function () {
    Route::controller(ApiProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'show')->name('show');
        Route::post('/', 'update')->name('update');
        Route::post('/update-name', 'updateName')->name('updateName');
        Route::post('/update-email', 'updateEmail')->name('updateEmail');
        Route::post('/update-phone', 'updatePhone')->name('updatePhone');
        Route::post('/update-address', 'updateAddress')->name('updateAddress');
        Route::post('/update-city', 'updateCity')->name('updateCity');
        Route::post('/change-password', 'changePassword')->name('changePassword');
        Route::post('/change-image', 'changeImage')->name('changeImage');
    });
    Route::controller(ApiProductController::class)->prefix('product')->name('product.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/search/{keyword}', 'search')->name('search');
    });
    Route::controller(ApiCartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'add')->name('add');
        Route::post('/{id}/update', 'update')->name('update');
        Route::post('/{id}/remove', 'remove')->name('remove');
    });
    Route::controller(ApiCheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
        Route::post('/', 'checkout')->name('checkout');
        Route::post('/confirm', 'confirmCheckout')->name('confirmCheckout');
        Route::get('/payments', 'payments')->name('payments');
    });
    Route::controller(ApiOrderController::class)->prefix('order')->name('order.')->group(function () {
        Route::get('/pesanan', 'pesanan')->name('pesanan');
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/', 'pesan')->name('pesan');
        Route::post('/hapus', 'hapus')->name('hapus');
    });
    Route::controller(ApiChatController::class)->prefix('chat')->name('chat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });
});

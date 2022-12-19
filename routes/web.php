<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::prefix('/products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    Route::prefix('/orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    });
});

Route::get('login', [AuthController::class, 'index'])->name('login.view');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');

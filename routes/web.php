<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/', function () {
        return view('dashboard');
    });
    Route::get('/table', function () {
        return view('tables');
    });
    Route::get('/form', function () {
        return view('forms');
    });
});

Route::get('login', [AuthController::class, 'index'])->name('login.view');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('signout', [AuthController::class, 'signOut'])->name('signout');

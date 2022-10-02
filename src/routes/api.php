<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/movies', [Controllers\MovieController::class, 'index']);

Route::post('/customer/login', [Controllers\CustomerAuthController::class, 'login'])->name('customer_login');
Route::post('/customer/register', [Controllers\CustomerAuthController::class, 'register'])->name('customer_register');

Route::group(['middleware' => ['auth:user'], 'prefix' => 'customer', 'as' => 'customer.'], function () {
    Route::get('me', [Controllers\CustomerAuthController::class, 'me'])->name('me');
});

Route::group(['middleware' => ['auth:user'], 'prefix' => 'movies', 'as' => 'movies.'], function () {
    Route::get('/{movie}', [Controllers\MovieController::class, 'show'])->name('show');
    Route::post('/rent', [Controllers\MovieController::class, 'rent'])->name('me');
});

Route::group(['middleware' => ['auth:user', 'isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::post('/import-movie', [Controllers\MovieController::class, 'importMovie']);
});

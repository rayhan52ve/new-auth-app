<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UrlShortenerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/signin', [LoginController::class, 'login'])->name('login');
Route::match(['get', 'post'], '/register', [LoginController::class, 'register'])->name('register');

Route::group(['middleware' => ['auth']], function () {  //auth
    //Dashboard Start
    Route::get('/control', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile-update/{id}', [DashboardController::class, 'profile_update'])->name('profile_update');

    Route::post('/profile/change-password', [DashboardController::class, 'changePassword'])->name('changePassword');

    // url shortener
    Route::get('/utl-shortener', [UrlShortenerController::class, 'index'])->name('shortener.index');
    Route::post('/shorten', [UrlShortenerController::class, 'shorten'])->name('shorten');
    Route::get('/{key}', [UrlShortenerController::class, 'redirectToOriginal'])->name('shortened');

    // url shortener

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});//auth

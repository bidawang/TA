<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rental;
use App\Http\Controllers\SetRental;
use App\Http\Controllers\Fasilitas;
use App\Http\Controllers\Galeri;
use App\Http\Controllers\PS;
use App\Http\Controllers\GamePS;
use App\Http\Controllers\Google;
use App\Http\Controllers\Rating;
use App\Http\Controllers\User_C;

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

Route::get('/', [Rental::class, 'dashboard']);

Route::resource('rental', Rental::class);
Route::resource('setrental', SetRental::class);
Route::resource('fasilitas', Fasilitas::class);
Route::resource('galeri', Galeri::class);
Route::resource('ps', PS::class);
Route::post('/gameps/bulk-delete', [GamePS::class, 'bulkDelete'])->name('gameps.bulkDelete');

Route::resource('gameps', GamePS::class);
Route::resource('rating', Rating::class);
Route::resource('user', User_C::class);
Route::get('user/{google_id}/profile', [User_C::class, 'profile'])->name('user.profile');


Route::get('/auth/google/callback', [Google::class, 'handleGoogleCallback']);
Route::get('/auth/google', [Google::class, 'redirectToGoogle'])->name('google.login');
Route::post('/logout', [Google::class, 'logout'])->name('logout');





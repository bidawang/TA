<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rental;
use App\Http\Controllers\SetRental;
use App\Http\Controllers\Fasilitas;
use App\Http\Controllers\Galeri;
use App\Http\Controllers\PS;
use App\Http\Controllers\TV;
use App\Http\Controllers\GamePS;
use App\Http\Controllers\Google;
use App\Http\Controllers\Rating;
use App\Http\Controllers\User_C;
use App\Http\Controllers\Transaksi;
use App\Http\Controllers\Tripay;
use App\Http\Controllers\View;
use App\Http\Controllers\Wallet;
use App\Http\Controllers\Ajax;
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

Route::get('/', [Rental::class, 'dashboard'])->name('dashboard');
Route::resource('user', User_C::class);
Route::get('/auth/google/callback', [Google::class, 'handleGoogleCallback']);
Route::get('/auth/google', [Google::class, 'redirectToGoogle'])->name('google.login');
Route::post('/logout', [Google::class, 'logout'])->name('logout');
Route::post('/callback/tripay', [Tripay::class, 'handle'])->name('tripay.callback');
Route::resource('rental', Rental::class);
Route::resource('setrental', SetRental::class);


Route::middleware(['auth', 'phone.verified'])->group(function () {
Route::put('/setrental/selesai/{id}', [SetRental::class, 'selesai'])->name('setrental.selesai');
Route::resource('fasilitas', Fasilitas::class);
Route::resource('galeri', Galeri::class);
Route::resource('ps', PS::class);
Route::resource('tv', TV::class);
Route::post('/gameps/bulk-delete', [GamePS::class, 'bulkDelete'])->name('gameps.bulkDelete');
Route::resource('gameps', GamePS::class);
Route::resource('rating', Rating::class);
Route::put('ratingByUser/{id}', [Rating::class, 'updateByUser'])->name('rating.updateByUser');

Route::get('user/{google_id}/profile', [User_C::class, 'profile'])->name('user.profile');
Route::resource('transaksi', Transaksi::class);



Route::resource('riwayat', View::class);
Route::resource('wallet', Wallet::class);
Route::post('wallet/withdraw', [Wallet::class, 'tariktunai'])
    ->name('wallet.withdraw');  // pastikan hanya user yg login bisa akses
    Route::put('/wallet/update-status/{id}', [Wallet::class, 'updateStatus'])->name('wallet.updateStatus')->middleware('auth');

Route::get('/pendapatan/{id}', [View::class, 'pendapatan'])->name('pendapatan');

Route::get('pendapatan/custom', [View::class, 'pendapatanCustom'])->name('pendapatan.Custom');
Route::patch('/transaksi/{id}/update-status', [Transaksi::class, 'updateStatus'])->name('transaksi.updateStatus');
Route::post('/transaksi/{id}/bayar', [Transaksi::class, 'bayar'])->name('transaksi.bayar');

Route::put('/setrental/{id}/maintenance', [SetRental::class, 'toMaintenance'])->name('setrental.maintenance');
Route::put('/setrental/{id}/aktifkan', [SetRental::class, 'toAktif'])->name('setrental.aktifkan');
Route::get('/notifikasi/transaksi', [Ajax::class, 'getTransaksi'])->name('notifikasi.transaksi')->middleware('auth');

});
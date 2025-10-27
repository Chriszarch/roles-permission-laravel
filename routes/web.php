<?php

use App\Http\Controllers\QrCodeController;
use App\Livewire\Dashboard;
use App\Livewire\Login;
use App\Livewire\SearchPlace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/r/{qrCode:uuid}', [QrCodeController::class, 'redirect'])->name('qr.redirect');

Route::middleware('auth')->get('/qr-codes/{qrCode:uuid}/download', [QrCodeController::class, 'download'])->name('qr.download');

Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/users', App\Livewire\Users::class)
        ->can('users.view', App\Models\User::class)
        ->name('users');

    Route::get('/qr-codes', App\Livewire\QrCodes::class)
        ->can('qr.view', App\Models\QrCode::class)
        ->name('qr-codes');

    Route::get('/google-places', SearchPlace::class)->name('google-places');
});

Route::get('/logout', function () {
    Auth::logout();

    return redirect('/login');
})->name('logout');

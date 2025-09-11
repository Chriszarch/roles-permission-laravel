<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Login;


Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/users', App\Livewire\Users::class)
        ->can('view', App\Models\User::class)
        ->name('users');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

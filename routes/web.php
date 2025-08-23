<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Welcome;
use App\Livewire\Login;

Route::get('/', Welcome::class);
Route::get('/login', Login::class);

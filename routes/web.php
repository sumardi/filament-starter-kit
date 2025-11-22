<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn () => redirect()->route('filament.user.auth.login'))->name('login');
Route::get('/user', fn () => redirect()->route('filament.user.auth.profile'));

<?php

use App\Http\Controllers\ImageUploadController;
use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::auth.login')->middleware('throttle:5,1')->name('login');
Route::livewire('/register', 'pages::auth.register')->middleware('throttle:5,1')->name('register');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::post('/admin/upload-image', ImageUploadController::class)
    ->middleware(['auth', 'throttle:10,1']);

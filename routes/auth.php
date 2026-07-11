<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');

Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::post('/admin/upload-image', function () {
    $file = request()->file('image');
    abort_unless($file, 400);
    $path = $file->store('uploads', 'public');

    return response()->json(['success' => true, 'url' => asset('storage/'.$path)]);
})->middleware('auth');

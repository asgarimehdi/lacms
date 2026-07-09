<?php

use App\Http\Livewire\PageManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/pages', PageManager::class)->name('pages.index');

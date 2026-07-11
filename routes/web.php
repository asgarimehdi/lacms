<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// CMS Routes — protected by auth middleware
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::livewire('/', 'pages::dashboard.index')->name('dashboard');
    Route::livewire('/posts', 'pages::posts.index')->name('posts.index');
    Route::livewire('/posts/create', 'pages::posts.create-edit')->name('posts.create');
    Route::livewire('/posts/{post}/edit', 'pages::posts.create-edit')->name('posts.edit');
    Route::livewire('/pages', 'pages::pages.index')->name('pages.index');
    Route::livewire('/pages/create', 'pages::pages.create-edit')->name('pages.create');
    Route::livewire('/pages/{page}/edit', 'pages::pages.create-edit')->name('pages.edit');
    Route::livewire('/categories', 'pages::categories.index')->name('categories.index');
    Route::livewire('/categories/create', 'pages::categories.create-edit')->name('categories.create');
    Route::livewire('/categories/{category}/edit', 'pages::categories.create-edit')->name('categories.edit');

    // Tags
    Route::livewire('/tags', 'pages::tags.index')->name('tags.index');
    Route::livewire('/tags/create', 'pages::tags.create-edit')->name('tags.create');
    Route::livewire('/tags/{tag}/edit', 'pages::tags.create-edit')->name('tags.edit');

    // Settings
    Route::livewire('/settings', 'pages::settings.index')->name('settings');
});

<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/','pages::users.index')->name('users.index');

// CMS Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::livewire('/', 'pages::dashboard.index')->name('dashboard');

    // Posts
    Route::livewire('/posts', 'pages::posts.index')->name('posts.index');
    Route::livewire('/posts/create', 'pages::posts.create-edit')->name('posts.create');
    Route::livewire('/posts/{post}/edit', 'pages::posts.create-edit')->name('posts.edit');

    // Pages
    Route::livewire('/pages', 'pages::pages.index')->name('pages.index');
    Route::livewire('/pages/create', 'pages::pages.create-edit')->name('pages.create');
    Route::livewire('/pages/{page}/edit', 'pages::pages.create-edit')->name('pages.edit');

    // Categories
    Route::livewire('/categories', 'pages::categories.index')->name('categories.index');
    Route::livewire('/categories/create', 'pages::categories.create-edit')->name('categories.create');
    Route::livewire('/categories/{category}/edit', 'pages::categories.create-edit')->name('categories.edit');
});

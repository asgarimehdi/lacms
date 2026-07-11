<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Public\BlogIndex;
use App\Livewire\Public\BlogShow;
use App\Livewire\Public\CategoryShow;
use App\Livewire\Public\PageView;
use App\Livewire\Public\TagShow;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public CMS Routes — no authentication required
Route::get('/p/{page:slug}', PageView::class)->name('public.page');

Route::get('/blog', BlogIndex::class)->name('public.posts.index');

Route::get('/blog/{post:slug}', BlogShow::class)->name('public.posts.show');

Route::get('/category/{category:slug}', CategoryShow::class)->name('public.categories.show');

Route::get('/tag/{tag:slug}', TagShow::class)->name('public.tags.show');

// Admin Routes — protected by auth middleware
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
    Route::livewire('/tags', 'pages::tags.index')->name('tags.index');
    Route::livewire('/tags/create', 'pages::tags.create-edit')->name('tags.create');
    Route::livewire('/tags/{tag}/edit', 'pages::tags.create-edit')->name('tags.edit');
    Route::livewire('/settings', 'pages::settings.index')->name('settings');
});

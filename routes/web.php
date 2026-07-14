<?php

use App\Livewire\Public\Homepage;
use Illuminate\Support\Facades\Route;

// Public CMS Routes — SPA with Livewire component
Route::livewire('/', Homepage::class)->name('home');

// Public CMS Routes — no authentication required
Route::livewire('/p/{page:slug}', 'public.page-view')->name('public.page');

Route::livewire('/blog', 'public.blog-index')->name('public.posts.index');

Route::livewire('/blog/{post:slug}', 'public.blog-show')
    ->middleware('throttle:3,1')
    ->name('public.posts.show');

Route::livewire('/category/{category:slug}', 'public.category-show')->name('public.categories.show');

Route::livewire('/tag/{tag:slug}', 'public.tag-show')->name('public.tags.show');

// Admin Routes — protected by auth middleware
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::livewire('/', 'pages::dashboard.index')->name('dashboard');
    Route::livewire('/posts', App\Livewire\Pages\Posts\Index::class)->name('posts.index');
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
    Route::livewire('/comments', 'pages::comments.index')->name('comments.index');
    Route::livewire('/comments/{comment}/edit', 'pages::comments.create-edit')->name('comments.edit');
});

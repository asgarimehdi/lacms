<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;

class HomePage extends Component
{
    public function with(): array
    {
        return [
            'featuredPosts' => Post::with('category')->where('is_published', true)->latest()->take(6)->get(),
            'categories' => Category::withCount('posts')->get(),
            'latestPost' => Post::with('category')->where('is_published', true)->latest()->first(),
        ];
    }

    public function render()
    {
        return view('livewire.home-page')->layout('layouts.app');
    }
}

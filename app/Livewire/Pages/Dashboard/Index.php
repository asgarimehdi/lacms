<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public function with(): array
    {
        return [
            'postsCount' => Post::count(),
            'pagesCount' => Page::count(),
            'categoriesCount' => Category::count(),
            'publishedPostsCount' => Post::where('is_published', true)->count(),
            'draftPostsCount' => Post::where('is_published', false)->count(),
        ];
    }

    public function render()
    {
        return view('pages.dashboard.index');
    }
}
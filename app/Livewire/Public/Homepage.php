<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;

class Homepage extends Component
{
    public ?Page $page = null;
    public Collection $featuredPosts;
    public Collection $pages;
    public Collection $categories;
    public Collection $tags;
    public bool $loaded = false;

    public function mount(): void
    {
        $this->page = Page::where('status', 'published')
            ->where('slug', 'home')
            ->firstOr(fn () => Page::whereNull('parent_id')->first());

        $this->featuredPosts = collect();
        $this->pages = collect();
        $this->categories = collect();
        $this->tags = collect();
    }

    public function loadContent(): void
    {
        if ($this->loaded) {
            return;
        }

        $this->featuredPosts = Post::where('is_published', true)
            ->with(['category', 'tags'])
            ->latest()
            ->take(6)
            ->get();

        $this->pages = Page::where('status', 'published')
            ->orderBy('sort')
            ->get();

        $this->categories = Category::withCount('posts')->get();
        $this->tags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        $this->loaded = true;
    }

    public function render()
    {
        return view('livewire.public.homepage');
    }
}
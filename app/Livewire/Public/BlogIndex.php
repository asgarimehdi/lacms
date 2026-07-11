<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class BlogIndex extends Component
{
    use WithPagination;

    public ?string $search = '';

    public ?string $category = '';

    public bool $loaded = false;

    public function mount(): void
    {
        $this->loaded = false;
    }

    public function loadContent(): void
    {
        $this->loaded = true;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getPostsProperty(): LengthAwarePaginator
    {
        $query = Post::where('is_published', true)->with(['category', 'tags']);

        if ($this->search) {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->category));
        }

        return $query->latest()->paginate(12);
    }

    public function render(): View
    {
        return view('livewire.public.blog-index');
    }
}

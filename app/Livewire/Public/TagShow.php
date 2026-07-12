<?php

namespace App\Livewire\Public;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class TagShow extends Component
{
    use WithPagination;

    public Tag $tag;
    public ?string $search = '';

    public function mount(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getPostsProperty(): LengthAwarePaginator
    {
        return $this->tag->posts()
            ->where('is_published', true)
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.public.tag-show');
    }
}
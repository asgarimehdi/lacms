<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryShow extends Component
{
    use WithPagination;

    public Category $category;
    public ?string $search = '';

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getPostsProperty(): LengthAwarePaginator
    {
        return $this->category->posts()
            ->where('is_published', true)
            ->when($this->search, fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.public.category-show');
    }
}
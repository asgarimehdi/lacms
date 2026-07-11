<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryShow extends Component
{
    public Category $category;

    public bool $loaded = false;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render(): View
    {
        return view('livewire.public.category-show');
    }
}

<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Livewire\Component;

class CategoryShow extends Component
{
    public Category $category;

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        return view('livewire.public.category-show');
    }
}
<?php

namespace App\Livewire\Pages\Categories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public string $search = '';
    public bool $drawer = false;
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    #[On('category-updated')]
    public function clear(): void
    {
        $this->reset();
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function delete($id): void
    {
        Category::find($id)?->delete();
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => __('cms.title'), 'class' => 'w-64'],
            ['key' => 'slug', 'label' => 'slug', 'sortable' => false],
        ];
    }

    public function categories(): Collection
    {
        return Category::query()
            ->withCount('posts')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'categories' => $this->categories(),
            'headers' => $this->headers(),
        ];
    }

    public function render()
    {
        return view('pages.categories.index');
    }
}
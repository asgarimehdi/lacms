<?php

use App\Models\Category;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
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
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <x-header :title="__('cms.categories')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('cms.search')" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('cms.create')" link="{{ route('admin.categories.create') }}" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$categories" :sort-by="$sortBy" link="categories/{id}/edit">
            @scope('actions', $category)
            <x-button icon="o-trash" wire:click="delete({{ $category['id'] }})" wire:confirm="{{ __('cms.confirm') }}" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>
</div>

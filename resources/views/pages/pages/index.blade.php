<?php

use App\Models\Page;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';
    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];

    public function delete($id): void
    {
        Page::find($id)?->delete();
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => __('cms.title'), 'class' => 'w-64'],
            ['key' => 'slug', 'label' => 'slug', 'sortable' => false],
        ];
    }

    public function pages(): Collection
    {
        return Page::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'pages' => $this->pages(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <x-header :title="__('cms.pages')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('cms.search')" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button :label="__('cms.create')" link="{{ route('admin.pages.create') }}" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$pages" :sort-by="$sortBy" link="pages/{id}/edit">
            @scope('actions', $page)
            <x-button icon="o-trash" wire:click="delete({{ $page['id'] }})" wire:confirm="{{ __('cms.confirm') }}" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>
</div>
<?php

use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public function delete($id): void
    {
        Tag::find($id)?->delete();
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => __('cms.title'), 'class' => 'w-64'],
            ['key' => 'slug', 'label' => 'slug', 'sortable' => false],
            ['key' => 'posts_count', 'label' => 'Posts'],
        ];
    }

    public function tags(): Collection
    {
        return Tag::query()
            ->withCount('posts')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'tags' => $this->tags(),
            'headers' => $this->headers()
        ];
    }
}; ?>

<div>
    <x-header title="Tags" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="{{ __('cms.search') }}" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('cms.create') }}" link="/admin/tags/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$tags" :sort-by="$sortBy" link="tags/{id}/edit">
            @scope('actions', $tag)
            <x-button icon="o-trash" wire:click="delete({{ $tag['id'] }})" wire:confirm="{{ __('cms.confirm') }}" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>
</div>
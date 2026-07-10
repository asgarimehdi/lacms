<?php

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';
    public array $selected = [];
    public array $sortBy = ['column' => 'title', 'direction' => 'asc'];

    public function bulkDelete(): void
    {
        Post::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function delete($id): void
    {
        Post::find($id)?->delete();
        $this->success(__('cms.deleted'), position: 'toast-bottom');
    }

    public function headers(): array
    {
        return [
            ['key' => 'checkbox', 'label' => '', 'class' => 'w-1'],
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => __('cms.title'), 'class' => 'w-64'],
            ['key' => 'category.name', 'label' => __('cms.category')],
            ['key' => 'is_published', 'label' => __('cms.status')],
            ['key' => 'updated_at', 'label' => __('cms.last_updated')],
            ['key' => 'author', 'label' => __('cms.author')]
        ];
    }

    public function posts(): Collection
    {
        return Post::query()
            ->with('category')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'headers' => $this->headers(),
            'selected' => $this->selected
        ];
    }
}; ?>

<div>
    <x-header :title="__('cms.posts')" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input :placeholder="__('cms.search')" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            @if(count($selected) > 0)
                <x-button :label="__('cms.bulk_delete') . ' (' . count($selected) . ')'" wire:click="bulkDelete" wire:confirm="{{ __('cms.confirm') }}" icon="o-trash" class="btn-error" spinner="bulkDelete" />
            @endif
            <x-button :label="__('cms.create')" link="{{ route('admin.posts.create') }}" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$posts" :sort-by="$sortBy" link="posts/{id}/edit">
            @scope('cell_checkbox', $post)
                <x-checkbox wire:model.live="selected" value="{{ $post['id'] }}" class="checkbox-primary" />
            @endscope
            @scope('cell_is_published', $post)
                @if($post['is_published'])
                    <x-badge value="{{ __('cms.published') }}" class="badge-success" />
                @else
                    <x-badge value="{{ __('cms.draft') }}" class="badge-warning" />
                @endif
            @endscope
            @scope('actions', $post)
            <x-button icon="o-trash" wire:click="delete({{ $post['id'] }})" wire:confirm="{{ __('cms.confirm') }}" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>
</div>
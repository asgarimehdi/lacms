<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public string $search = '';
    public ?int $category_filter = null;
    public bool $published_filter = false;
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
            ['key' => 'tags_list', 'label' => 'Tags'],
            ['key' => 'updated_at', 'label' => __('cms.last_updated')],
        ];
    }

    public function posts(): Collection
    {
        $allowedColumns = ['id', 'title', 'is_published', 'updated_at', 'created_at'];
        $column = $this->sortBy['column'] ?? 'title';
        $direction = $this->sortBy['direction'] ?? 'asc';
        if (! in_array($column, $allowedColumns, true)) {
            $column = 'title';
            $direction = 'asc';
        }

        return Post::query()
            ->with(['category', 'tags'])
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->category_filter, fn($q) => $q->where('category_id', $this->category_filter))
            ->when($this->published_filter !== null, fn($q) => $q->where('is_published', $this->published_filter))
            ->orderBy($column, $direction)
            ->get();
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'headers' => $this->headers(),
            'categories' => Category::all()->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
        ];
    }
}; ?>

<div>
    <x-header title="{{ __('cms.posts') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="{{ __('cms.search') }}" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            @if(count($selected) > 0)
                <x-button :label="__('cms.bulk_delete').' ('.count($selected).')'" wire:click="bulkDelete" wire:confirm="{{ __('cms.confirm') }}" icon="o-trash" class="btn-error" spinner="bulkDelete" />
            @endif
            <x-button :label="__('cms.create')" link="/admin/posts/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <div class="flex gap-3 mb-4 flex-wrap">
            <x-select placeholder="All Categories" wire:model.live="category_filter" :options="$categories" placeholder-value="0" class="max-w-xs" />
            <x-button label="Published" wire:click="$set('published_filter', true)" class="{{ $published_filter === true ? 'btn-primary' : 'btn-outline' }} btn-sm" />
            <x-button label="Drafts" wire:click="$set('published_filter', false)" class="{{ $published_filter === false ? 'btn-primary' : 'btn-outline' }} btn-sm" />
            <x-button label="All" wire:click="$set('published_filter', null)" class="{{ is_null($published_filter) ? 'btn-primary' : 'btn-outline' }} btn-sm" />
        </div>

        <x-table :headers="$headers" :rows="$posts" :sort-by="$sortBy" link="/admin/posts/{id}/edit">
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
            @scope('cell_tags_list', $post)
                @foreach($post['tags'] as $tag)
                    <span class="badge badge-outline badge-sm me-1">{{ $tag['name'] }}</span>
                @endforeach
            @endscope
        </x-table>
    </x-card>
</div>
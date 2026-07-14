<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
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
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->category_filter, fn ($q) => $q->where('category_id', $this->category_filter))
            ->when($this->published_filter !== null, fn ($q) => $q->where('is_published', $this->published_filter))
            ->orderBy($column, $direction)
            ->get();
    }

    public function with(): array
    {
        return [
            'posts' => $this->posts(),
            'headers' => $this->headers(),
            'categories' => Category::all()->map(fn ($c) => ['id' => $c->id, 'name' => $c->name]),
        ];
    }

    public function render()
    {
        return view('pages.posts.index');
    }
}

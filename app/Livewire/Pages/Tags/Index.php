<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
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
            'headers' => $this->headers(),
        ];
    }

    public function render()
    {
        return view('pages.tags.index');
    }
}
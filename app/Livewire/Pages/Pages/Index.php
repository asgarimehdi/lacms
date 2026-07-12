<?php

namespace App\Livewire\Pages\Pages;

use App\Models\Page;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
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
            'headers' => $this->headers(),
        ];
    }

    public function render()
    {
        return view('pages.pages.index');
    }
}
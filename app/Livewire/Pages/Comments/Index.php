<?php

namespace App\Livewire\Pages\Comments;

use App\Models\Comment;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public string $search = '';

    public ?string $filter_status = null;

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function approve($id): void
    {
        Comment::find($id)?->update(['is_approved' => true]);
        $this->success('Comment approved.');
    }

    public function reject($id): void
    {
        Comment::find($id)?->delete();
        $this->success('Comment rejected and deleted.');
    }

    public function approveAll(): void
    {
        Comment::where('is_approved', false)->update(['is_approved' => true]);
        $this->success('All pending comments approved.');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'post.title', 'label' => 'Post', 'sortable' => false],
            ['key' => 'author_name', 'label' => 'Author'],
            ['key' => 'body', 'label' => 'Comment', 'sortable' => false, 'class' => 'w-64'],
            ['key' => 'is_approved', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Submitted'],
        ];
    }

    public function comments(): Collection
    {
        return Comment::query()
            ->with('post')
            ->when($this->filter_status === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($this->filter_status === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($this->search, fn ($q) => $q->where('author_name', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'comments' => $this->comments(),
            'headers' => $this->headers(),
            'pending_count' => Comment::where('is_approved', false)->count(),
        ];
    }

    public function render()
    {
        return view('pages.comments.index');
    }
}

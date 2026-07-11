<?php

namespace App\Livewire\Public;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BlogShow extends Component
{
    public Post $post;

    public string $author_name = '';

    public string $author_email = '';

    public string $body = '';

    public bool $submitted = false;

    public function mount(Post $post): void
    {
        if (! $post->is_published) {
            abort(404);
        }
        $this->post = $post->load(['category', 'tags', 'user', 'comments' => fn ($q) => $q->where('is_approved', true)->latest()]);
    }

    public function submitComment(): void
    {
        $this->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:100',
            'body' => 'required|string|min:10|max:2000',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'body' => $this->body,
            'is_approved' => false,
        ]);

        $this->submitted = true;
        $this->reset('author_name', 'author_email', 'body');
    }

    public function render(): View
    {
        return view('livewire.public.blog-show');
    }
}

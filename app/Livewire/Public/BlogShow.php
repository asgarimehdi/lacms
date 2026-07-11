<?php

namespace App\Livewire\Public;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class BlogShow extends Component
{
    public Post $post;

    public bool $loaded = false;

    public function mount(Post $post): void
    {
        if (! $post->is_published) {
            abort(404);
        }
        $this->post = $post;
    }

    public function render(): View
    {
        return view('livewire.public.blog-show');
    }
}

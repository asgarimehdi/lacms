<?php

namespace App\Livewire\Public;

use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TagShow extends Component
{
    public Tag $tag;

    public bool $loaded = false;

    public function mount(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function render(): View
    {
        return view('livewire.public.tag-show');
    }
}

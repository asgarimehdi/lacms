<?php

namespace App\Livewire\Public;

use App\Models\Tag;
use Livewire\Component;

class TagShow extends Component
{
    public Tag $tag;

    public function mount(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function render()
    {
        return view('livewire.public.tag-show');
    }
}
<?php

namespace App\Livewire\Public;

use App\Models\Page;
use Livewire\Component;

class PageView extends Component
{
    public Page $page;

    public function mount(Page $page): void
    {
        if ($page->status !== 'published') {
            abort(404);
        }
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.public.page-view');
    }
}
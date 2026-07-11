<?php

namespace App\Livewire\Public;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PageView extends Component
{
    public Page $page;

    public bool $loaded = false;

    public function mount(Page $page): void
    {
        if ($page->status !== 'published') {
            abort(404);
        }
        $this->page = $page;
    }

    public function render(): View
    {
        return view('livewire.public.page-view');
    }
}

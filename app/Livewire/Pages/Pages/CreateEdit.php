<?php

namespace App\Livewire\Pages\Pages;

use App\Models\Page;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateEdit extends Component
{
    use Toast;

    public ?Page $page = null;
    public string $title = '';
    public string $content = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function mount(?Page $page = null): void
    {
        if ($page && $page->exists) {
            $this->page = $page;
            $this->title = $page->title;
            $this->content = $page->content;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->page) {
            $this->page->update($data);
        } else {
            Page::create($data);
        }

        $this->success($this->page ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect('/admin/pages');
    }

    public function render()
    {
        return view('pages.pages.create-edit');
    }
}
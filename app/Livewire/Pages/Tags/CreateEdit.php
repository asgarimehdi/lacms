<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateEdit extends Component
{
    use Toast;

    public ?Tag $tag = null;
    public string $name = '';

    protected function rules(): array
    {
        return ['name' => 'required|string|max:255'];
    }

    public function mount(?Tag $tag = null): void
    {
        if ($tag && $tag->exists) {
            $this->tag = $tag;
            $this->name = $tag->name;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->tag) {
            $this->tag->update($data);
        } else {
            Tag::create($data);
        }

        $this->success($this->tag ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect('/admin/tags');
    }

    public function render()
    {
        return view('pages.tags.create-edit');
    }
}
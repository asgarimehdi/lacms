<?php

use App\Models\Page;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
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
}; ?>

<div>
    <x-header :title="$page ? __('cms.edit') : __('cms.create')" separator>
        <x-slot:actions>
            <x-button :label="__('cms.back')" link="{{ route('admin.pages.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input :label="__('cms.title')" wire:model="title" />
            <div class="mt-4">
                <label class="label">
                    <span class="label-text">{{ __('cms.content') }}</span>
                </label>
                <input type="hidden" wire:model="content" id="page-content" />
                <div data-quill="page-content" class="min-h-[400px]"></div>
            </div>
            <x-slot:actions>
                <x-button :label="__('cms.save')" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
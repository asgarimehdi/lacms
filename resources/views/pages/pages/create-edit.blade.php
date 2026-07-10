<?php

use App\Models\Page;
use Illuminate\Support\Str;
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
        $this->redirect(route('pages.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.pages-form');
    }
}; ?>

<div>
    <x-header :title="$page ? __('cms.edit') : __('cms.create')" separator>
        <x-slot:actions>
            <x-button :label="__('cms.back')" link="{{ route('pages.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input :label="__('cms.title')" wire:model="title" />
            <div class="mt-2 text-sm text-gray-500">
                <span>{{ __('cms.slug_preview') }}:/{{ Str::slug($title) }}</span>
            </div>
            <div class="mt-4">
                <label class="label">
                    <span class="label-text">{{ __('cms.content') }}</span>
                </label>
                <input type="hidden" wire:model="content" />
                <trix-editor x-data="{ initialized: false }" x-on:trix-initialize="initialized = true" x-on:trix-change="$wire.content = $event.target.value" wire:ignore input="content" class="min-h-[300px] prose prose-sm max-w-none"></trix-editor>
            </div>
            <x-slot:actions>
                <x-button :label="__('cms.save')" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
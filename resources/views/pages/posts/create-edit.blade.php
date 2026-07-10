<?php

use App\Models\Category;
use App\Models\Post;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Post $post = null;
    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    public bool $is_published = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'is_published' => 'boolean',
        ];
    }

    public function mount(?Post $post = null): void
    {
        if ($post && $post->exists) {
            $this->post = $post;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->category_id = $post->category_id;
            $this->is_published = $post->is_published;
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->post) {
            $this->post->update($data);
        } else {
            Post::create($data);
        }

        $this->success($this->post ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect('/admin/posts');
    }

    public function with(): array
    {
        return [
            'categories' => Category::all()->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
        ];
    }
}; ?>

<div>
    <x-header :title="$post ? __('cms.edit') : __('cms.create')" separator>
        <x-slot:actions>
            <x-button :label="__('cms.back')" link="{{ route('admin.posts.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input :label="__('cms.title')" wire:model="title" />
            <x-select :label="__('cms.category')" wire:model="category_id" :options="$categories" placeholder="{{ __('cms.select_category') }}" />
            <div class="mt-4">
                <label class="label">
                    <span class="label-text">{{ __('cms.content') }}</span>
                </label>
                <input type="hidden" wire:model="content" />
                <trix-editor x-data="{ initialized: false }" x-on:trix-initialize="initialized = true" x-on:trix-change="$wire.content = $event.target.value" wire:ignore input="content" class="min-h-[300px] prose prose-sm max-w-none"></trix-editor>
            </div>
            <x-checkbox :label="__('cms.published')" wire:model="is_published" class="mt-4" />
            <x-slot:actions>
                <x-button :label="__('cms.save')" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
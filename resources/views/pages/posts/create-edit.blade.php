<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public ?Post $post = null;
    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    public bool $is_published = false;
    public array $tag_ids = [];

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
            $this->tag_ids = $post->tags->pluck('id')->all();
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->post) {
            $this->post->update($data);
            $this->post->tags()->sync($this->tag_ids);
        } else {
            $data['user_id'] = auth()->id();
            $post = Post::create($data);
            $post->tags()->sync($this->tag_ids);
        }

        $this->success($this->post ? __('cms.updated') : __('cms.created'), position: 'toast-bottom');
        $this->redirect('/admin/posts');
    }

    public function with(): array
    {
        return [
            'categories' => Category::all()->map(fn($c) => ['id' => $c->id, 'name' => $c->name]),
            'tags' => Tag::all()->map(fn($t) => ['id' => $t->id, 'name' => $t->name]),
        ];
    }
}; ?>

<div>
    <x-header title="{{ $post ? __('cms.edit') : __('cms.create') }} {{ __('cms.posts') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('cms.back') }}" link="/admin/posts" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-form wire:submit="save">
            <x-input label="{{ __('cms.title') }}" wire:model="title" />
            <x-select label="{{ __('cms.category') }}" wire:model="category_id" :options="$categories" placeholder="{{ __('cms.select_category') }}" />
            <x-choices-offline label="Tags" wire:model="tag_ids" :options="$tags" searchable />

            <div class="mt-4" x-data="{ ready: false }" x-init="ready = true">
                <label class="label"><span class="label-text">{{ __('cms.content') }}</span></label>
                <input type="hidden" wire:model="content" id="post-content" />
                <div x-show="ready" data-editorjs="post-content" class="min-h-[400px] border border-base-300 rounded-lg p-4 prose prose-sm max-w-none bg-base-100"></div>
                <div x-show="!ready" class="min-h-[400px] border border-base-300 rounded-lg bg-base-300 animate-pulse flex items-center justify-center">
                    <x-icon name="o-cube" class="w-12 h-12 text-base-content/20" />
                </div>
            </div>

            <x-checkbox label="{{ __('cms.published') }}" wire:model="is_published" class="mt-4" />

            <x-slot:actions>
                <x-button label="{{ __('cms.save') }}" type="submit" icon="o-check" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateEdit extends Component
{
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

    public function render()
    {
        return view('pages.posts.create-edit');
    }
}
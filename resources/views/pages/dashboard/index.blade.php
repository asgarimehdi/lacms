<?php

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public $postsCount;
    public $pagesCount;
    public $categoriesCount;
    public $publishedPostsCount;
    public $draftPostsCount;

    public function mount()
    {
        $this->postsCount = Post::count();
        $this->pagesCount = Page::count();
        $this->categoriesCount = Category::count();
        $this->publishedPostsCount = Post::where('is_published', true)->count();
        $this->draftPostsCount = Post::where('is_published', false)->count();
    }
}; ?>

<div>
    <x-header title="{{ __('cms.dashboard') }}" separator progress-indicator />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-card shadow class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('cms.posts') }}</p>
                    <p class="text-3xl font-bold">{{ $postsCount }}</p>
                </div>
                <x-icon name="o-document" class="w-10 text-gray-300" />
            </div>
            <div class="mt-2 flex gap-2 text-xs">
                <span class="badge badge-success">{{ $publishedPostsCount }} {{ __('cms.published') }}</span>
                <span class="badge badge-warning">{{ $draftPostsCount }} {{ __('cms.draft') }}</span>
            </div>
        </x-card>

        <x-card shadow class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('cms.pages') }}</p>
                    <p class="text-3xl font-bold">{{ $pagesCount }}</p>
                </div>
                <x-icon name="o-document-text" class="w-10 text-gray-300" />
            </div>
            <a href="{{ route('admin.pages.index') }}" class="mt-2 text-sm text-primary hover:underline">{{ __('cms.view_all') }}</a>
        </x-card>

        <x-card shadow class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('cms.categories') }}</p>
                    <p class="text-3xl font-bold">{{ $categoriesCount }}</p>
                </div>
                <x-icon name="o-folder" class="w-10 text-gray-300" />
            </div>
            <a href="{{ route('admin.categories.index') }}" class="mt-2 text-sm text-primary hover:underline">{{ __('cms.view_all') }}</a>
        </x-card>

        <x-card shadow class="p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">{{ __('cms.quick_actions') }}</p>
                </div>
                <x-icon name="o-bolt" class="w-10 text-gray-300" />
            </div>
            <div class="mt-2 flex gap-2">
                <a href="{{ route('admin.posts.create') }}" wire:navigate class="btn btn-sm btn-primary">{{ __('cms.new_post') }}</a>
                <a href="{{ route('admin.pages.create') }}" wire:navigate class="btn btn-sm btn-outline">{{ __('cms.new_page') }}</a>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <x-card shadow>
            <x-slot:header>
                <h3 class="font-semibold">{{ __('cms.recent_posts') }}</h3>
            </x-slot:header>
            <x-table :headers="[['key'=>'title','label'=>__('cms.title')],['key'=>'status','label'=>__('cms.status')],['key'=>'updated_at','label'=>__('cms.last_updated')]]"
                     :rows="App\Models\Post::with('category')->latest()->take(5)->get()" link="posts/{id}/edit">
                @scope('cell_status', $post)
                    @if($post['is_published'])
                        <x-badge value="{{ __('cms.published') }}" class="badge-success" />
                    @else
                        <x-badge value="{{ __('cms.draft') }}" class="badge-warning" />
                    @endif
                @endscope
            </x-table>
        </x-card>

        <x-card shadow>
            <x-slot:header>
                <h3 class="font-semibold">{{ __('cms.recent_pages') }}</h3>
            </x-slot:header>
            <x-table :headers="[['key'=>'title','label'=>__('cms.title')],['key'=>'updated_at','label'=>__('cms.last_updated')]]"
                     :rows="App\Models\Page::latest()->take(5)->get()" link="pages/{id}/edit" />
        </x-card>
    </div>
</div>
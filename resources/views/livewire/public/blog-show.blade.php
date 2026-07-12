<?php

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

new class extends Component
{
    public Post $post;

    public string $author_name = '';

    public string $author_email = '';

    public string $body = '';

    public bool $submitted = false;

    public function mount(Post $post): void
    {
        if (! $post->is_published) {
            abort(404);
        }
        $this->post = $post->load(['category', 'tags', 'user', 'comments' => fn ($q) => $q->where('is_approved', true)->latest()]);
    }

    public function submitComment(): void
    {
        $this->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:100',
            'body' => 'required|string|min:10|max:2000',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'body' => $this->body,
            'is_approved' => false,
        ]);

        $this->submitted = true;
        $this->reset('author_name', 'author_email', 'body');
    }
};
?>

<div class="min-h-screen bg-base-200">
    <!-- Header -->
    <header class="bg-base-100 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                <span class="text-xl font-bold">CMS</span>
            </a>
            <div class="flex gap-2">
                <x-button label="خانه" icon="o-home" link="/" class="btn-ghost btn-sm" />
                <x-button label="وبلاگ" icon="o-document" link="/blog" class="btn-ghost btn-sm" />
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <main class="container mx-auto px-4 py-12">
        <x-card class="max-w-4xl mx-auto shadow-xl" padded>
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm text-base-content/60 mb-6">
                <a href="/" wire:navigate class="hover:text-primary">خانه</a>
                <x-icon name="o-chevron-right" class="w-4 h-4" />
                <a href="/blog" wire:navigate class="hover:text-primary">وبلاگ</a>
                @if($post->category)
                    <x-icon name="o-chevron-right" class="w-4 h-4" />
                    <a href="{{ route('public.categories.show', $post->category->slug) }}" wire:navigate class="hover:text-primary">
                        {{ $post->category->name }}
                    </a>
                @endif
            </nav>

            <!-- Featured Image -->
            <div class="aspect-video bg-gradient-to-br from-primary/20 to-secondary/20 rounded-lg mb-6 flex items-center justify-center overflow-hidden">
                @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover" />
                @else
                    <x-icon name="o-photo" class="w-16 h-16 text-base-content/30" />
                @endif
            </div>

            <!-- Title & Meta -->
            <div class="border-b border-base-300 pb-6 mb-6">
                @if($post->category)
                    <x-badge size="sm" value="{{ $post->category->name }}" class="badge-primary mb-3" />
                @endif
                <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-base-content/60">
                    <span>
                        <x-icon name="o-calendar" class="w-4 h-4 inline me-1" />
                        {{ $post->created_at->format('Y/m/d') }}
                    </span>
                    @if($post->user)
                        <span>
                            <x-icon name="o-user" class="w-4 h-4 inline me-1" />
                            {{ $post->user->name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <article class="prose prose-lg max-w-none prose RTL editor-content">
                {!! $post->content !!}
            </article>

            <!-- Tags -->
            @if($post->tags->count() > 0)
                <div class="border-t border-base-300 mt-8 pt-6">
                    <div class="flex items-center gap-2 flex-wrap">
                        <x-icon name="o-tag" class="w-4 h-4 text-base-content/60" />
                        @foreach($post->tags as $tag)
                            <x-button tag="a" href="{{ route('public.tags.show', $tag->slug) }}" wire:navigate
                                size="sm" class="badge badge-outline hover:badge-primary">
                                {{ $tag->name }}
                            </x-button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="border-t border-base-300 mt-8 pt-8">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <x-icon name="o-chat-bubble-left" class="w-5 h-5 text-primary" />
                    دیدگاه‌ها ({{ $post->comments->count() }})
                </h3>

                <!-- Comment List -->
                @if($post->comments->count())
                    <div class="space-y-4 mb-8">
                        @foreach($post->comments as $comment)
                            <x-card class="bg-base-100 border border-base-300" padded>
                                <div class="flex items-start gap-3">
                                    <x-icon name="o-user-circle" class="w-8 h-8 text-base-content/40 shrink-0" />
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold">{{ $comment->author_name }}</span>
                                            <span class="text-xs text-base-content/50">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-base-content/80">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            </x-card>
                        @endforeach
                    </div>
                @endif

                <!-- Comment Form -->
                @if($submitted)
                    <x-card class="bg-success/10 border border-success/30" padded>
                        <div class="flex items-center gap-2 text-success">
                            <x-icon name="o-check-circle" class="w-6 h-6" />
                            <p>دیدگاه شما با موفقیت ثبت شد و پس از تأیید نمایش داده می‌شود.</p>
                        </div>
                    </x-card>
                @else
                    <x-card class="bg-base-100 border border-base-300" padded>
                        <h4 class="font-bold mb-4">ارسال دیدگاه</h4>
                        <x-form wire:submit="submitComment">
                            <div class="grid md:grid-cols-2 gap-4">
                                <x-input label="نام" wire:model="author_name" placeholder="نام شما" />
                                <x-input label="ایمیل" wire:model="author_email" type="email" placeholder="email@example.com" />
                            </div>
                            <x-textarea label="دیدگاه" wire:model="body" rows="4" class="mt-4" placeholder="دیدگاه خود را بنویسید..." />
                            <x-slot:actions>
                                <x-button label="ارسال دیدگاه" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="submitComment" />
                            </x-slot:actions>
                        </x-form>
                    </x-card>
                @endif
            </div>
        </x-card>
        <!-- Navigation -->
        <div class="max-w-4xl mx-auto mt-6 flex justify-between">
            <x-button label="بازگشت به وبلاگ" icon="o-arrow-right" link="/blog" class="btn-ghost" />
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-300">
        <span class="text-sm text-base-content/60">
            © {{ date('Y') }} سامانه مدیریت محتوا
        </span>
    </footer>
</div>
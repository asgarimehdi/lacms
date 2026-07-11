<div class="min-h-screen bg-base-200">
    <!-- Header -->
    <header class="bg-base-100 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                <span class="text-xl font-bold">CMS</span>
            </a>
            <x-button label="وبلاگ" icon="o-document" link="/blog" class="btn-ghost btn-sm" />
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-gradient-to-r from-primary/10 to-secondary/10 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold bg-gradient-to-l from-primary to-secondary bg-clip-text text-transparent mb-4">
                وبلاگ
            </h1>
            <p class="text-base-content/60 max-w-2xl mx-auto">
                آخرین پست‌ها و مقالات ما را بخوانید
            </p>
        </div>
    </section>

    <!-- Posts Grid -->
    <main class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->posts as $post)
                <x-card class="shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1" padded>
                    <a href="{{ route('public.posts.show', $post->slug) }}" wire:navigate class="block">
                        <!-- Featured Image Placeholder -->
                        <div class="aspect-video bg-gradient-to-br from-primary/20 to-secondary/20 rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover" />
                            @else
                                <x-icon name="o-photo" class="w-12 h-12 text-base-content/30" />
                            @endif
                        </div>

                        <!-- Category Badge -->
                        @if($post->category)
                            <x-badge size="xs" value="{{ $post->category->name }}" class="badge-primary mb-2" />
                        @endif

                        <!-- Title -->
                        <h2 class="text-lg font-bold line-clamp-2 hover:text-primary transition-colors">
                            {{ $post->title }}
                        </h2>

                        <!-- Excerpt -->
                        <p class="text-sm text-base-content/60 mt-2 line-clamp-3">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <!-- Meta -->
                        <div class="flex items-center justify-between mt-4 text-xs text-base-content/50">
                            <span>
                                <x-icon name="o-calendar" class="w-3 h-3 inline me-1" />
                                {{ $post->created_at->format('Y/m/d') }}
                            </span>
                            @if($post->tags->count() > 0)
                                <div class="flex gap-1">
                                    @foreach($post->tags->take(2) as $tag)
                                        <x-badge size="xs" class="badge-ghost">{{ $tag->name }}</x-badge>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                </x-card>
            @empty
                <!-- Empty State -->
                <div class="col-span-full text-center py-16">
                    <x-icon name="o-document" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/60">هنوز پستی منتشر نشده است.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($this->posts->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $this->posts->links() }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-300">
        <span class="text-sm text-base-content/60">
            © {{ date('Y') }} سامانه مدیریت محتوا
        </span>
    </footer>
</div>
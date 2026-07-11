<div class="min-h-screen bg-base-200">
    <!-- Header -->
    <header class="bg-base-100 shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                <span class="text-xl font-bold">CMS</span>
            </a>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-gradient-to-r from-secondary/10 to-primary/10 py-12">
        <div class="container mx-auto px-4 text-center">
            <x-icon name="o-folder" class="w-12 h-12 mx-auto text-secondary mb-4" />
            <h1 class="text-4xl font-bold mb-2">{{ $category->name }}</h1>
            <p class="text-base-content/60">
                {{ $category->posts->count() ?? 'پست‌ها' }}
            </p>
        </div>
    </section>

    <!-- Posts Grid -->
    <main class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($category->posts()->where('is_published', true)->with(['category', 'tags'])->latest()->paginate(12) as $post)
                <x-card class="shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1" padded>
                    <a href="{{ route('public.posts.show', $post->slug) }}" wire:navigate class="block">
                        <div class="aspect-video bg-gradient-to-br from-primary/20 to-secondary/20 rounded-lg mb-4 flex items-center justify-center">
                            <x-icon name="o-photo" class="w-12 h-12 text-base-content/30" />
                        </div>
                        <h2 class="text-lg font-bold line-clamp-2 hover:text-primary transition-colors">
                            {{ $post->title }}
                        </h2>
                        <p class="text-sm text-base-content/60 mt-2 line-clamp-3">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <div class="flex items-center justify-between mt-4 text-xs text-base-content/50">
                            <span>{{ $post->created_at->format('Y/m/d') }}</span>
                        </div>
                    </a>
                </x-card>
            @empty
                <div class="col-span-full text-center py-16">
                    <x-icon name="o-folder-open" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <p class="text-base-content/60">هنوز پستی در این دسته‌بندی منتشر نشده است.</p>
                </div>
            @endforelse
        </div>

        @if($category->posts()->where('is_published', true)->count() > 12)
            <div class="mt-8 flex justify-center">
                {{ $category->posts()->where('is_published', true)->latest()->paginate(12)->links() }}
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
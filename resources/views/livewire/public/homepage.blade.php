<div class="min-h-screen bg-base-200">
    <!-- Header -->
    <header class="bg-gradient-to-l from-primary/10 to-secondary/10 shadow-sm sticky top-0 z-50 backdrop-blur-lg bg-base-100/80">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" wire:navigate class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-8 h-8 text-primary" />
                <span class="text-2xl font-bold bg-gradient-to-l from-primary to-secondary bg-clip-text text-transparent">CMS</span>
            </a>
            <x-button label="ورود به پنل" icon="o-cog-6-tooth" link="/admin" class="btn-primary" />
        </div>
    </header>

    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            @if($page)
                <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-l from-primary to-secondary bg-clip-text text-transparent animate-pulse">
                    {{ $page->title }}
                </h1>
                <p class="text-base text-base-content/60">
                    <x-icon name="o-clock" class="w-4 h-4 inline me-1" />
                    {{ $page->updated_at?->diffForHumans() ?? 'به تازگی' }}
                </p>
            @else
                <!-- Placeholder -->
                <div class="h-12 w-96 mx-auto bg-base-300 rounded-lg animate-pulse"></div>
                <div class="h-4 w-48 mx-auto bg-base-300 rounded animate-pulse"></div>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-4 pb-16">
        <x-card class="max-w-4xl mx-auto shadow-xl" padded>
            @if($page?->content)
                <article class="prose prose-lg max-w-none prose RTL">
                    {!! $page->content !!}
                </article>
            @else
                <!-- Content Placeholder -->
                <div class="space-y-4">
                    <div class="h-4 w-full bg-base-300 rounded animate-pulse"></div>
                    <div class="h-4 w-5/6 bg-base-300 rounded animate-pulse"></div>
                    <div class="h-4 w-4/5 bg-base-300 rounded animate-pulse"></div>
                    <div class="h-32 w-full bg-base-300 rounded-lg animate-pulse"></div>
                </div>
            @endif
        </x-card>
    </main>

    <!-- Featured Posts -->
    @if($featuredPosts->count() > 0 || !$loaded)
        <section class="bg-base-300/50 py-16">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        <x-icon name="o-fire" class="w-6 h-6 text-error" />
                        پست‌های اخیر
                    </h2>
                    <x-button label="همه پست‌ها" icon="o-arrow-left" link="/blog" class="btn-ghost" />
                </div>

                @if(!$loaded)
                    <!-- Posts Placeholder Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @for($i = 0; $i < 6; $i++)
                            <x-card class="shadow-lg" padded>
                                <div class="space-y-3">
                                    <div class="h-40 w-full bg-base-300 rounded-lg animate-pulse"></div>
                                    <div class="h-6 w-3/4 bg-base-300 rounded animate-pulse"></div>
                                    <div class="h-4 w-full bg-base-300 rounded animate-pulse"></div>
                                    <div class="h-4 w-2/3 bg-base-300 rounded animate-pulse"></div>
                                </div>
                            </x-card>
                        @endfor
                    </div>
                    <div class="text-center mt-6">
                        <x-button label="بارگذاری پست‌ها" icon="o-arrow-down-circle" wire:click="loadContent" class="btn-primary" />
                    </div>
                @else
                    <!-- Posts Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredPosts as $post)
                            <x-card class="shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1" padded>
                                <a href="{{ route('public.posts.show', $post->slug) }}" wire:navigate class="block">
                                    <div class="aspect-video bg-gradient-to-br from-primary/20 to-secondary/20 rounded-lg mb-4 flex items-center justify-center">
                                        <x-icon name="o-photo" class="w-12 h-12 text-base-content/30" />
                                    </div>
                                    <h3 class="text-lg font-bold line-clamp-2 hover:text-primary transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="text-sm text-base-content/60 mt-2 line-clamp-2">
                                        {{ Str::limit(strip_tags($post->content), 100) }}
                                    </p>
                                    <div class="flex items-center justify-between mt-4 text-xs text-base-content/50">
                                        @if($post->category)
                                            <x-badge size="xs" value="{{ $post->category->name }}" class="badge-primary" />
                                        @endif
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            </x-card>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Categories & Tags Sidebar -->
    <section class="container mx-auto px-4 py-16">
        <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
            <!-- Categories -->
            <x-card class="shadow-lg" padded>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <x-icon name="o-folder" class="w-5 h-5 text-secondary" />
                    دسته‌بندی‌ها
                </h3>
                @if($categories->count() > 0)
                    <ul class="space-y-2">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('public.categories.show', $cat->slug) }}" wire:navigate class="flex items-center justify-between p-2 rounded-lg hover:bg-base-300 transition-colors">
                                    <span>{{ $cat->name }}</span>
                                    <x-badge size="xs" value="{{ $cat->posts_count }}" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="space-y-2">
                        @for($i = 0; $i < 4; $i++)
                            <div class="h-8 bg-base-300 rounded animate-pulse"></div>
                        @endfor
                    </div>
                @endif
            </x-card>

            <!-- Popular Tags -->
            <x-card class="shadow-lg" padded>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <x-icon name="o-tag" class="w-5 h-5 text-accent" />
                    برچسب‌های محبوب
                </h3>
                @if($tags->count() > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <x-button tag="a" href="{{ route('public.tags.show', $tag->slug) }}" wire:navigate
                                size="sm" class="badge badge-outline hover:badge-primary">
                                {{ $tag->name }}
                            </x-button>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        @for($i = 0; $i < 8; $i++)
                            <div class="h-6 w-16 bg-base-300 rounded animate-pulse"></div>
                        @endfor
                    </div>
                @endif
            </x-card>

            <!-- Quick Links -->
            <x-card class="shadow-lg" padded>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <x-icon name="o-link" class="w-5 h-5 text-primary" />
                    لینک‌های سریع
                </h3>
                @if($pages->count() > 0)
                    <ul class="space-y-2">
                        @foreach($pages->take(5) as $pageItem)
                            <li>
                                <a href="{{ route('public.page', $pageItem->slug) }}" wire:navigate
                                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-base-300 transition-colors">
                                    <x-icon name="o-document-text" class="w-4 h-4" />
                                    {{ $pageItem->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="space-y-2">
                        @for($i = 0; $i < 4; $i++)
                            <div class="h-8 bg-base-300 rounded animate-pulse"></div>
                        @endfor
                    </div>
                @endif
            </x-card>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <x-card class="max-w-2xl mx-auto bg-gradient-to-r from-primary/20 to-secondary/20 shadow-xl" padded>
                <x-icon name="o-sparkles" class="w-12 h-12 mx-auto mb-4 text-primary" />
                <h2 class="text-2xl font-bold mb-4">آماده شروع هستید؟</h2>
                <p class="mb-6 opacity-80">محتوای این صفحه را از پنل مدیریت ویرایش کنید</p>
                <x-button label="ورود به پنل مدیریت" icon="o-arrow-right" link="/admin" class="btn-primary" />
            </x-card>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-300 mt-auto">
        <div class="flex items-center gap-2">
            <x-icon name="o-sparkles" class="w-5 h-5 text-primary" />
            <span class="text-sm text-base-content/60">
                © {{ date('Y') }} سامانه مدیریت محتوا - کلیه حقوق محفوظ است
            </span>
        </div>
    </footer>
</div>
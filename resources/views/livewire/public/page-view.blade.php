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

    <!-- Page Content -->
    <main class="container mx-auto px-4 py-12">
        <x-card class="max-w-4xl mx-auto shadow-xl" padded>
            <!-- Title -->
            <div class="border-b border-base-300 pb-6 mb-6">
                <h1 class="text-4xl font-bold text-primary mb-2">
                    {{ $page->title }}
                </h1>
                <p class="text-sm text-base-content/60">
                    <x-icon name="o-clock" class="w-4 h-4 inline me-1" />
                    {{ $page->updated_at?->diffForHumans() ?? 'به تازگی' }}
                </p>
            </div>

            <!-- Content with Editor.js styling -->
            <article class="prose prose-lg max-w-none prose RTL editor-content">
                @if($page->content)
                    {!! $page->content !!}
                @else
                    <div class="space-y-4">
                        <div class="h-4 w-full bg-base-300 rounded animate-pulse"></div>
                        <div class="h-4 w-5/6 bg-base-300 rounded animate-pulse"></div>
                        <div class="h-4 w-4/5 bg-base-300 rounded animate-pulse"></div>
                        <div class="h-32 w-full bg-base-300 rounded-lg animate-pulse"></div>
                    </div>
                @endif
            </article>
        </x-card>

        <!-- Navigation Back -->
        <div class="max-w-4xl mx-auto mt-6 text-center">
            <x-button label="بازگشت به خانه" icon="o-arrow-right" link="/" class="btn-ghost" />
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-300">
        <span class="text-sm text-base-content/60">
            © {{ date('Y') }} سامانه مدیریت محتوا
        </span>
    </footer>
</div>
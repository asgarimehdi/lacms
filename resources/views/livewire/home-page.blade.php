<div>
    <section class="relative bg-gradient-to-br from-primary/10 via-base-100 to-secondary/10 py-20 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-5xl font-black mb-4">{{ __('cms.site_tagline') ?? 'مدیریت آسان محتوا' }}</h1>
            <p class="text-xl text-base-content/70 mb-8">سامانه مدیریت محتوای لاراول با لایوایر و MaryUI</p>
            <x-button label="مشاهده مقالات" icon="o-arrow-right" link="/blog" class="btn-primary btn-lg" />
        </div>
    </section>

    @if($latestPost)
    <section class="max-w-5xl mx-auto px-6 py-12">
        <x-header title="آخرین مقاله" separator />
        <x-card shadow class="overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3 bg-gradient-to-tr from-primary/20 to-secondary/20 flex items-center justify-center p-8 min-h-48">
                    <x-icon name="o-document-text" class="w-24 h-24 text-primary/40" />
                </div>
                <div class="md:w-2/3 p-6">
                    <x-badge value="{{ $latestPost->category->name ?? 'دسته‌بندی' }}" class="badge-primary mb-2" />
                    <h2 class="text-2xl font-bold mb-2">{{ $latestPost->title }}</h2>
                    <p class="text-base-content/60 line-clamp-3 mb-4">{{ strip_tags($latestPost->content) }}</p>
                    <x-button label="ادامه مطلب" link="/blog/{{ $latestPost->slug }}" icon="o-arrow-right" class="btn-sm btn-primary" />
                </div>
            </div>
        </x-card>
    </section>
    @endif

    @if($featuredPosts->count() > 1)
    <section class="max-w-5xl mx-auto px-6 py-8">
        <x-header title="مقالات اخیر" separator />
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredPosts->skip(1) as $post)
            <x-card shadow class="hover:shadow-lg transition-shadow">
                <x-slot:header>
                    <x-badge value="{{ $post->category->name ?? '' }}" class="badge-soft badge-primary text-xs" />
                </x-slot:header>
                <h3 class="font-bold text-lg mb-2 line-clamp-2">{{ $post->title }}</h3>
                <p class="text-sm text-base-content/60 line-clamp-3 mb-4">{{ strip_tags($post->content) }}</p>
                <x-slot:actions>
                    <x-button label="ادامه" link="/blog/{{ $post->slug }}" icon="o-arrow-right" class="btn-ghost btn-sm" />
                </x-slot:actions>
            </x-card>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <x-button label="همه مقالات" link="/blog" icon="o-folder" class="btn-outline" />
        </div>
    </section>
    @endif

    @if($categories->count())
    <section class="max-w-5xl mx-auto px-6 py-8">
        <x-header title="دسته‌بندی‌ها" separator />
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $cat)
            <x-card shadow class="text-center hover:scale-105 transition-transform cursor-pointer" onclick="location.href='/category/{{ $cat->slug }}'">
                <x-icon name="o-folder" class="w-8 h-8 mx-auto mb-2 text-primary" />
                <p class="font-semibold">{{ $cat->name }}</p>
                <p class="text-xs text-base-content/50 mt-1">{{ $cat->posts_count }} مقاله</p>
            </x-card>
            @endforeach
        </div>
    </section>
    @endif
</div>
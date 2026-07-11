<div class="min-h-screen bg-base-100">
    <!-- Header -->
    <header class="bg-base-200 shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-primary">
                    سامانه مدیریت محتوا
                </h1>
                <a href="/admin" class="btn btn-primary">ورود به پنل</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        @if($page)
            <div class="max-w-4xl mx-auto space-y-8">
                <!-- Page Title -->
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-primary mb-4">
                        {{ $page->title }}
                    </h1>
                    <div class="text-base-content/70 mb-6">
                        <small>به‌روزرسانی شده در: {{ $page->updated_at->diffForHumans() }}</small>
                    </div>
                </div>

                <!-- Page Content -->
                <article class="prose prose-lg max-w-none">
                    {!! $page->content !!}
                </article>

                <!-- Call to Action -->
                <div class="text-center py-8 bg-base-200 rounded-xl">
                    <h2 class="text-2xl font-bold mb-4">آماده شروع هستید؟</h2>
                    <p class="text-base-content/70 mb-6">
                        محتوای این صفحه را از طریق پنل مدیریت ویرایش کنید و سایت خود را شخصی‌سازی کنید.
                    </p>
                    <a href="/admin" class="btn btn-primary btn-lg">
                        ورود به پنل مدیریت
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <h2 class="text-2xl font-bold mb-4">هیچ محتوایی یافت نشد</h2>
                <p class="text-base-content/70">
                    لطفاً ابتدا یک صفحه را از طریق <a href="/admin/pages" class="text-primary underline">پنل مدیریت</a> ایجاد کنید.
                </p>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-200 mt-auto">
        <span class="text-sm text-base-content/60">
            © {{ now()->year }} سامانه مدیریت محتوا - کلیه حقوق محفوظ است
        </span>
    </footer>
</div>
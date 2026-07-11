<?php

use Livewire\Component;
use App\Models\Page;

new class extends Component
{
    public $page;

    public function mount()
    {
        // Fetch the first published page as homepage content
        $this->page = Page::where('status', 'published')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc')
            ->first();

        // If no published page found, create a default one for demo
        if (!$this->page) {
            $this->page = Page::create([
                'title' => 'خوش آمدید به سامانه مدیریت محتوا',
                'slug' => 'home',
                'content' => '<p>این یک صفحه نمونه است که از پایگاه داده بازیابی شده است.</p><p>محتوای این صفحه را از طریق پنل مدیریت ویرایش کنید.</p>',
                'status' => 'published'
            ]);
        }
    }
}
?>

<div class="min-h-screen bg-base-100">
    <!-- Header with MaryUI -->
    <header class="bg-gradient-to-r from-primary/10 to-secondary/10 shadow-lg">
        <div class="container mx-auto px-4 py-8">
            <x-navbar class="bg-transparent shadow-none">
                <x-slot:brand>
                    <span class="text-2xl font-bold text-primary">CMS</span>
                </x-slot:brand>
                <x-slot:actions>
                    <x-button label="ورود به پنل" icon="o-cog-6-tooth" link="/admin" class="btn-primary" />
                </x-slot:actions>
            </x-navbar>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto text-center space-y-6">
            <h1 class="text-5xl font-bold bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                {{ $page->title }}
            </h1>
            <p class="text-lg text-base-content/70">
                {{ $page->updated_at->diffForHumans() }}
            </p>
        </div>
    </section>

    <!-- Main Content with MaryUI Card -->
    <main class="container mx-auto px-4 pb-16">
        <x-card class="max-w-4xl mx-auto shadow-xl" padded>
            <article class="prose prose-lg max-w-none prose RTL">
                {!! $page->content !!}
            </article>
        </x-card>
    </main>

    <!-- Features Grid -->
    <section class="bg-base-200 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">ویژگی‌های سیستم</h2>
            <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <x-card class="shadow-lg hover:shadow-xl transition-shadow" padded>
                    <x-icon name="o-document-text" class="w-12 h-12 text-primary mb-4" />
                    <h3 class="text-xl font-bold mb-2">مدیریت صفحات</h3>
                    <p class="text-base-content/70">ایجاد و ویرایش صفحات وب با رابط کاربری ساده</p>
                </x-card>
                <x-card class="shadow-lg hover:shadow-xl transition-shadow" padded>
                    <x-icon name="o-document" class="w-12 h-12 text-secondary mb-4" />
                    <h3 class="text-xl font-bold mb-2">مدیریت پست‌ها</h3>
                    <p class="text-base-content/70">نوشتن و انتشار پست‌های وبلاگ حرفه‌ای</p>
                </x-card>
                <x-card class="shadow-lg hover:shadow-xl transition-shadow" padded>
                    <x-icon name="o-folder" class="w-12 h-12 text-accent mb-4" />
                    <h3 class="text-xl font-bold mb-2">دسته‌بندی و تگ</h3>
                    <p class="text-base-content/70">سازماندهی محتوا با دسته‌بندی و برچسب</p>
                </x-card>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <x-card class="max-w-2xl mx-auto bg-primary text-primary-content" padded>
                <h2 class="text-2xl font-bold mb-4">آماده شروع هستید؟</h2>
                <p class="mb-6 opacity-90">محتوای این صفحه را از پنل مدیریت ویرایش کنید</p>
                <x-button label="ورود به پنل مدیریت" icon="o-arrow-right" link="/admin" class="btn-secondary" />
            </x-card>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-center py-6 bg-base-200 mt-auto">
        <span class="text-sm text-base-content/60">
            © {{ now()->year }} سامانه مدیریت محتوا - کلیه حقوق محفوظ است
        </span>
    </footer>
</div>
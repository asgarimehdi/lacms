<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'مدیر سایت',
            'email' => 'admin@lacms.test',
            'password' => Hash::make('password'),
        ]);

        // Additional users
        collect([
            ['name' => 'علی محمدی', 'email' => 'ali@example.com'],
            ['name' => 'سارا احمدی', 'email' => 'sara@example.com'],
            ['name' => 'رضا کریمی', 'email' => 'reza@example.com'],
        ])->each(fn ($u) => User::create(array_merge($u, ['password' => Hash::make('password')])));

        // Categories (Persian) - create all first to get real IDs
        $catTech = Category::create(['name' => 'فناوری', 'slug' => 'technology']);
        $catLife = Category::create(['name' => 'سبک زندگی', 'slug' => 'lifestyle']);
        $catBiz = Category::create(['name' => 'کسب و کار', 'slug' => 'business']);
        $catHealth = Category::create(['name' => 'سلامت', 'slug' => 'health']);
        $catEdu = Category::create(['name' => 'آموزش', 'slug' => 'education']);
        $catTravel = Category::create(['name' => 'گردشگری', 'slug' => 'travel']);
        $catArt = Category::create(['name' => 'هنر', 'slug' => 'art']);

        // Tags (Persian)
        $tags = collect([
            ['name' => 'لاراول', 'slug' => 'laravel'],
            ['name' => 'پی اچ پی', 'slug' => 'php'],
            ['name' => 'لایوایر', 'slug' => 'livewire'],
            ['name' => 'نکات', 'slug' => 'tips'],
            ['name' => 'آموزشی', 'slug' => 'tutorial'],
            ['name' => 'وب', 'slug' => 'web'],
            ['name' => 'برنامه‌نویسی', 'slug' => 'programming'],
            ['name' => 'طراحی', 'slug' => 'design'],
        ])->map(fn ($t) => Tag::create($t));

        // Posts (Persian with rich content) - use real category IDs
        $posts = [
            [
                'title' => 'مقدمه‌ای بر لاراول ۱۱',
                'content' => '<h2>لاراول چیست؟</h2><p>لاراول یکی از محبوب‌ترین فریم‌ورک‌های PHP است که توسعه وب را سریع‌تر و لذت‌بخش‌تر می‌کند.</p><h3>ویژگی‌های کلیدی</h3><ul><li>سیستم مسیریابی بهبود یافته</li><li>پشتیبانی از PHP 8.2</li></ul>',
                'is_published' => true,
                'category_id' => $catTech->id,
            ],
            [
                'title' => '۱۰ نکته برای افزایش بهره‌وری برنامه‌نویسان',
                'content' => '<h2>بهره‌وری در کدنویسی</h2><p>افزایش بهره‌وری یکی از مهم‌ترین دغدغه‌های برنامه‌نویسان است.</p><ol><li>از کدهای تکراری اجتناب کنید</li><li>از ابزارهای مناسب استفاده کنید</li></ol>',
                'is_published' => true,
                'category_id' => $catBiz->id,
            ],
            [
                'title' => 'آموزش Livewire: ساخت رابط کاربری تعاملی',
                'content' => '<h2>Livewire چیست؟</h2><p>Livewire یک فریم‌ورک فول‌استک برای لاراول است.</p><h3>مزایای استفاده از Livewire</h3><ul><li>نیازی به نوشتن API نیست</li><li>سادگی در یادگیری</li></ul>',
                'is_published' => true,
                'category_id' => $catTech->id,
            ],
            [
                'title' => 'سبک زندگی مینیمال: زندگی با کمتر',
                'content' => '<h2>فلسفه مینیمالیسم</h2><p>مینیمالیسم سبکی از زندگی است که بر ساده‌سازی تمرکز دارد.</p>',
                'is_published' => true,
                'category_id' => $catLife->id,
            ],
            [
                'title' => 'راهنمای کامل تغذیه سالم',
                'content' => '<h2>تغذیه صحیح</h2><p>تغذیه سالم پایه و اساس سلامتی است.</p><h3>نکات مهم</h3><ul><li>مصرف میوه و سبزیجات تازه</li><li>کاهش مصرف قند و نمک</li></ul>',
                'is_published' => true,
                'category_id' => $catHealth->id,
            ],
            [
                'title' => 'بهترین مقاصد گردشگری ایران در تابستان',
                'content' => '<h2>گردشگری در ایران</h2><p>ایران با داشتن جاذبه‌های طبیعی و تاریخی فراوان، مقصدی عالی است.</p><ul><li>شیراز</li><li>اصفهان</li><li>کیش</li></ul>',
                'is_published' => true,
                'category_id' => $catTravel->id,
            ],
            [
                'title' => 'یادگیری ماشینی برای مبتدیان',
                'content' => '<h2>مقدمه‌ای بر Machine Learning</h2><p>یادگیری ماشینی شاخه‌ای از هوش مصنوعی است.</p>',
                'is_published' => false,
                'category_id' => $catEdu->id,
            ],
            [
                'title' => 'هنر معاصر ایران',
                'content' => '<h2>هنر نوین ایرانی</h2><p>هنر معاصر ایران ترکیبی از سنت‌های کهن و نوآوری‌های مدرن است.</p>',
                'is_published' => true,
                'category_id' => $catArt->id,
            ],
        ];

        foreach ($posts as $i => $post) {
            $post['slug'] = Str::slug($post['title']);
            $post['user_id'] = $admin->id;
            $created = Post::create($post);
            $tagIds = $tags->count() >= 2
                ? $tags->random(rand(1, 3))->pluck('id')
                : $tags->pluck('id');
            $created->tags()->attach($tagIds);
        }

        // Comments
        $firstPost = Post::first();
        if ($firstPost) {
            Comment::create([
                'post_id' => $firstPost->id,
                'author_name' => 'کاربر اول',
                'author_email' => 'user1@example.com',
                'body' => 'مقاله بسیار مفیدی بود. ممنون از اشتراک‌گذاری.',
                'is_approved' => true,
            ]);
            Comment::create([
                'post_id' => $firstPost->id,
                'author_name' => 'کاربر دوم',
                'author_email' => 'user2@example.com',
                'body' => 'لطفاً مقالات بیشتری در این زمینه بنویسید.',
                'is_approved' => true,
            ]);
        }

        // Pages (Persian)
        $pages = [
            ['title' => 'خانه', 'slug' => 'home', 'content' => '<h2>به سامانه مدیریت محتوای ما خوش آمدید</h2><p>این سایت با استفاده از لاراول و لایوایر ساخته شده است.</p><p>محتوای این صفحه را از پنل مدیریت ویرایش کنید.</p>', 'status' => 'published', 'sort' => 0],
            ['title' => 'درباره ما', 'slug' => 'about', 'content' => '<h2>درباره ما</h2><p>ما تیمی از توسعه‌دهندگان با تجربه هستیم که بهترین راه‌حل‌های وب را ارائه می‌دهیم.</p><p>هدف ما ایجاد ابزارهای ساده و کاربردی برای مدیریت محتوا است.</p>', 'status' => 'published', 'sort' => 1],
            ['title' => 'تماس با ما', 'slug' => 'contact', 'content' => '<h2>تماس با ما</h2><p>برای ارتباط با ما می‌توانید از طریق ایمیل زیر اقدام کنید:</p><p><strong>info@lacms.test</strong></p>', 'status' => 'published', 'sort' => 2],
            ['title' => 'سیاست حفظ حریم خصوصی', 'slug' => 'privacy', 'content' => '<h2>سیاست حفظ حریم خصوصی</h2><p>ما به حریم خصوصی شما احترام می‌گذاریم. اطلاعات شخصی شما نزد ما محفوظ است.</p>', 'status' => 'published', 'sort' => 3],
            ['title' => 'شرایط استفاده', 'slug' => 'terms', 'content' => '<h2>شرایط استفاده</h2><p>لطفاً قبل از استفاده از خدمات ما، شرایط را به دقت مطالعه کنید.</p>', 'status' => 'published', 'sort' => 4],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        // Settings (singleton)
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'سامانه مدیریت محتوا',
                'site_tagline' => 'مدیریت آسان محتوا',
                'contact_email' => 'admin@lacms.test',
                'comments_enabled' => true,
            ]
        );
    }
}

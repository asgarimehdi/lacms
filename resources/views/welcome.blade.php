<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت محتوا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    <!-- Navigation -->
    <nav class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex-1">
                <a href="/" class="btn btn-ghost text-xl font-bold">CMS</a>
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1 gap-2">
                    <li><a href="/admin" class="btn btn-primary">ورود به پنل</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-b from-base-100 to-base-200">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    به سامانه مدیریت محتوا خوش آمدید
                </h1>
                <p class="text-xl text-base-content/80 mb-10 leading-relaxed">
                    یک پلتفرم قدرتمند و کاربرپسند برای ساخت، مدیریت و انتشار محتوای وب‌سایت شما.
                    با ابزارهای پیشرفته و رابط کاربری ساده، وب‌سایت حرفه‌ای خود را بسازید.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="/admin" class="btn btn-primary btn-lg">شروع به کار</a>
                    <a href="/admin/pages" class="btn btn-outline btn-lg">مشاهده صفحات</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-base-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">ویژگی‌های برجسته</h2>
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Feature 1 -->
                <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="card-title text-xl">مدیریت آسان محتوا</h3>
                        <p class="text-base-content/70 mt-2">
                            ایجاد، ویرایش و حذف صفحات و پست‌ها با رابط کاربری intuitive و قدرتمند.
                        </p>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 bg-secondary/10 text-secondary rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="card-title text-xl">طراحی واکنش‌گرا</h3>
                        <p class="text-base-content/70 mt-2">
                            تمام صفحات با جدیدترین تکنولوژی‌ها به صورت responsive طراحی شده‌اند.
                        </p>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 bg-accent/10 text-accent rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="card-title text-xl">امنیت بالا</h3>
                        <p class="text-base-content/70 mt-2">
                            با احراز هویت قوی و سطوح دسترسی مختلف، امنیت داده‌های شما تضمین شده است.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-primary text-primary-content">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">۱۰۰٪</div>
                    <div class="opacity-80">رضایت کاربران</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">۲۴/۷</div>
                    <div class="opacity-80">پشتیبانی آنلاین</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">۵۰۰+</div>
                    <div class="opacity-80">صفحه فعال</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">۹۹.۹٪</div>
                    <div class="opacity-80">آپتایم</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-base-100">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">آماده شروع هستید؟</h2>
            <p class="text-lg text-base-content/70 mb-8 max-w-2xl mx-auto">
                همین امروز ثبت‌نام کنید و از تمام امکانات پیشرفته سامانه مدیریت محتوا بهره‌مند شوید.
            </p>
            <a href="/admin" class="btn btn-primary btn-lg">
                ورود به پنل مدیریت
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer footer-center p-6 bg-base-200 text-base-content mt-auto">
        <div>
            <p>تمامی حقوق محفوظ است © {{ date('Y') }} - سیستم مدیریت محتوا</p>
        </div>
    </footer>

</body>
</html>
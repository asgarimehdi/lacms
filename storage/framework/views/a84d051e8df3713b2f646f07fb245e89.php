<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم مدیریت محتوا</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <nav class="navbar bg-base-200 shadow-lg">
        <div class="container mx-auto">
            <div class="flex-1">
                <a href="/" class="btn btn-ghost text-xl">CMS</a>
            </div>
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1">
                    <li><a href="/admin/pages">مدیریت صفحات</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">به سیستم مدیریت محتوا خوش آمدید</h1>
        <p class="text-lg mb-4">این یک سیستم مدیریت محتوای فارسی با Livewire و MaryUI است.</p>
        <a href="/admin/pages" class="btn btn-primary">رفتن به مدیریت صفحات</a>
    </main>
</body>
</html><?php /**PATH C:\laragon\www\lacms\resources\views\welcome.blade.php ENDPATH**/ ?>
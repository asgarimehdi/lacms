<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل مدیریت')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-base-200">
    {{-- Top Navigation --}}
    <x-mary-nav>
        <x-slot:logo>
            <span class="text-xl font-bold">CMS فارسی</span>
        </x-slot:logo>
        
        <x-mary-menu-item title="داشبورد" icon="o-home" />
        <x-mary-menu-item title="صفحات" icon="o-document-text" link="/admin/pages" />
        
        <x-slot:actions>
            <form method="POST" action="/logout">
                @csrf
                <x-mary-button type="submit" icon="o-arrow-right-end-on-rectangle" class="btn-ghost" label="خروج" />
            </form>
        </x-slot:actions>
    </x-mary-nav>

    {{-- Main Content --}}
    <main class="container mx-auto py-6 px-4">
        @yield('content')
        {{ $slot }}
    </main>

    {{-- Toast Notifications --}}
    <x-mary-toast />
    
    @livewireScripts
</body>
</html>
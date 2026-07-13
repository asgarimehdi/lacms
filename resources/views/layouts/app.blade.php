<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() === 'fa') dir="rtl" @endif>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        @auth
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

                {{-- BRAND --}}
                <x-app-brand class="px-5 pt-4" />

                {{-- MENU --}}
                <x-menu activate-by-route>

                    {{-- User --}}
                    @if($user = auth()->user())
                        <x-menu-separator />

                        <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                            <x-slot:actions>
                                <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                            </x-slot:actions>
                        </x-list-item>

                        <x-menu-separator />
                    @endif

                    {{-- CMS Menu --}}
                    <x-menu-item title="{{ __('cms.dashboard') }}" icon="o-home" link="/admin" />
                    <x-menu-item title="{{ __('cms.posts') }}" icon="o-document" link="/admin/posts" />
                    <x-menu-item title="{{ __('cms.pages') }}" icon="o-document-text" link="/admin/pages" />
                    <x-menu-item title="{{ __('cms.categories') }}" icon="o-folder" link="/admin/categories" />
                    <x-menu-item title="Tags" icon="o-tag" link="/admin/tags" />
                    <x-menu-item title="Comments" icon="o-chat-bubble-left" link="/admin/comments" />
                    <x-menu-item title="Settings" icon="o-cog-6-tooth" link="/admin/settings" />
                    <x-menu-separator />

                    {{-- Theme Toggle --}}
                    <div class="px-5 py-2 flex items-center justify-between">
                        <span class="text-xs uppercase opacity-60">Theme</span>
                        <x-theme-toggle />
                    </div>

                    <x-menu-item title="Public Site" icon="o-globe-alt" link="/" />
                </x-menu>
            </x-slot:sidebar>
        @endauth

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

    {{-- FAB Quick Create --}}
    @auth
        <div class="fixed bottom-6 right-6 z-50" x-data="{ open: false }">
            <div x-show="open" x-transition class="mb-2 menu p-2 shadow bg-base-100 rounded-box w-52">
                <li>
                    <a href="{{ route('admin.posts.create') }}" wire:navigate class="flex items-center gap-2">
                        <x-icon name="o-document-plus" />
                        <span>{{ __('cms.new_post') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pages.create') }}" wire:navigate class="flex items-center gap-2">
                        <x-icon name="o-document-text" />
                        <span>{{ __('cms.new_page') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.create') }}" wire:navigate class="flex items-center gap-2">
                        <x-icon name="o-folder-plus" />
                        <span>{{ __('cms.new_category') }}</span>
                    </a>
                </li>
            </div>
            <x-button icon="o-plus" class="btn-circle btn-lg btn-primary shadow-lg" @click="open = !open" />
        </div>
    @endauth
</body>
</html>

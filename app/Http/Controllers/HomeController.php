<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $page = Page::where('status', 'published')
            ->where('slug', 'home')
            ->firstOr(fn () => Page::whereNull('parent_id')->first());

        if (! $page) {
            $page = Page::create([
                'title' => 'خوش آمدید به سامانه مدیریت محتوا',
                'slug' => 'home',
                'content' => '<p>این یک صفحه نمونه است که از پایگاه داده بازیابی شده است.</p><p>محتوای این صفحه را از طریق پنل مدیریت ویرایش کنید.</p>',
                'status' => 'published',
            ]);
        }

        return view('welcome', compact('page'));
    }
}

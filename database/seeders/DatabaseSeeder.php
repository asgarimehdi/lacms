<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
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
            'name' => 'Admin',
            'email' => 'admin@lacms.test',
            'password' => Hash::make('password'),
        ]);

        // Additional users
        $users = collect([
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Bob Wilson', 'email' => 'bob@example.com'],
        ])->each(fn($u) => User::create(array_merge($u, ['password' => Hash::make('password')])));

        // Categories
        $categories = collect([
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Education', 'slug' => 'education'],
        ])->each(fn($c) => Category::create($c));

        // Posts
        $posts = [
            ['title' => 'Getting Started with Laravel', 'content' => '<p>Laravel is a powerful PHP framework. In this guide, we explore its core features.</p>', 'is_published' => true, 'category_id' => 1],
            ['title' => 'Livewire vs Inertia', 'content' => '<p>A comprehensive comparison of Livewire and Inertia.js for Laravel developers.</p>', 'is_published' => true, 'category_id' => 1],
            ['title' => 'Healthy Habits for Developers', 'content' => '<p>Stay fit while coding. Tips for posture, eye health, and mental wellness.</p>', 'is_published' => true, 'category_id' => 4],
            ['title' => 'Productivity Tips for 2024', 'content' => '<p>Work smarter, not harder with these proven productivity strategies.</p>', 'is_published' => true, 'category_id' => 3],
            ['title' => 'Remote Work Best Practices', 'content' => '<p>How to stay productive and connected while working from home.</p>', 'is_published' => false, 'category_id' => 3],
            ['title' => 'Minimalist Living Guide', 'content' => '<p>Embrace minimalism and declutter your life for better focus.</p>', 'is_published' => true, 'category_id' => 2],
            ['title' => 'Modern CSS Techniques', 'content' => '<p>Explore the latest CSS features including grid, flexbox, and container queries.</p>', 'is_published' => true, 'category_id' => 1],
            ['title' => 'Online Learning Platforms Review', 'content' => '<p>A comparison of the best platforms for learning new skills.</p>', 'is_published' => false, 'category_id' => 5],
        ];

        foreach ($posts as $post) {
            $post['slug'] = Str::slug($post['title']);
            $post['user_id'] = $admin->id;
            Post::create($post);
        }

        // Pages
        $pages = [
            ['title' => 'About Us', 'content' => '<p>We are dedicated to providing quality content and services.</p>'],
            ['title' => 'Contact', 'content' => '<p>Get in touch with us at contact@lacms.test</p>'],
            ['title' => 'Privacy Policy', 'content' => '<p>Your privacy is important to us. Read our policy here.</p>'],
            ['title' => 'Terms of Service', 'content' => '<p>Please read our terms before using our services.</p>'],
        ];

        foreach ($pages as $page) {
            $page['slug'] = Str::slug($page['title']);
            Page::create($page);
        }
    }
}
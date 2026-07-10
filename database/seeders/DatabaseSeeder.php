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
            'name' => 'Admin',
            'email' => 'admin@lacms.test',
            'password' => Hash::make('password'),
        ]);

        // Additional users
        collect([
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

        // Tags
        $tags = collect([
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Livewire', 'slug' => 'livewire'],
            ['name' => 'Tips', 'slug' => 'tips'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
        ])->map(fn($t) => Tag::create($t));

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

        foreach ($posts as $i => $post) {
            $post['slug'] = Str::slug($post['title']);
            $post['user_id'] = $admin->id;
            $created = Post::create($post);
            $tagIds = $tags->count() >= 2
                ? $tags->random(2)->pluck('id')
                : $tags->pluck('id');
            $created->tags()->attach($tagIds);
        }

        // Comments
        $firstPost = Post::first();
        if ($firstPost) {
            Comment::create([
                'post_id' => $firstPost->id,
                'author_name' => 'Reader One',
                'author_email' => 'reader1@example.com',
                'body' => 'Great article! Very helpful.',
                'is_approved' => true,
            ]);
            Comment::create([
                'post_id' => $firstPost->id,
                'author_name' => 'Reader Two',
                'author_email' => 'reader2@example.com',
                'body' => 'Thanks for sharing this.',
                'is_approved' => false,
            ]);
        }

        // Pages
        foreach ([
            ['title' => 'About Us', 'content' => '<p>We are dedicated to providing quality content and services.</p>'],
            ['title' => 'Contact', 'content' => '<p>Get in touch with us at contact@lacms.test</p>'],
            ['title' => 'Privacy Policy', 'content' => '<p>Your privacy is important to us. Read our policy here.</p>'],
            ['title' => 'Terms of Service', 'content' => '<p>Please read our terms before using our services.</p>'],
        ] as $page) {
            $page['slug'] = Str::slug($page['title']);
            Page::create($page);
        }

        // Settings (singleton)
        Setting::create([
            'site_name' => 'LACMS',
            'site_tagline' => 'Lightweight Laravel CMS',
            'contact_email' => 'admin@lacms.test',
            'comments_enabled' => true,
        ]);
    }
}
<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;

it('seeds an admin user after running the seeder', function () {
    $this->seed();
    expect(User::where('email', 'admin@lacms.test')->exists())->toBeTrue();
    expect(Post::count())->toBeGreaterThan(5);
});

it('creates a post via the model', function () {
    $cat = Category::create(['name' => 'General']);
    Post::create([
        'title' => 'Hello', 'slug' => 'hello', 'content' => 'World',
        'is_published' => true, 'category_id' => $cat->id,
    ]);
    expect(Post::where('title', 'Hello')->count())->toBe(1);
});

it('attaches tags to a post with sync', function () {
    $post = Post::create(['title' => 'T', 'slug' => 't', 'content' => 'x', 'is_published' => true]);
    $tag = Tag::create(['name' => 'PHP']);
    $post->tags()->attach($tag->id);
    expect($post->tags->pluck('name')->all())->toBe(['PHP']);
});

it('cascades comments on post delete', function () {
    $post = Post::create(['title' => 'X', 'slug' => 'x', 'content' => 'x', 'is_published' => true]);
    Comment::create([
        'post_id' => $post->id,
        'author_name' => 'A', 'author_email' => 'a@b.test', 'body' => 'C',
    ]);
    expect(Comment::where('post_id', $post->id)->count())->toBe(1);
    $post->delete();
    expect(Comment::where('post_id', $post->id)->count())->toBe(0);
});

it('returns the same setting row via Setting::current()', function () {
    Setting::current()->update(['site_name' => 'TestCMS']);
    expect(Setting::current()->site_name)->toBe('TestCMS');
    expect(Setting::count())->toBe(1);
});

it('protected pages redirect unauthenticated to login', function () {
    $this->get('/admin')->assertRedirect('/login');
    $this->get('/admin/posts')->assertRedirect('/login');
    $this->get('/admin/settings')->assertRedirect('/login');
});

it('allows authenticated users to view admin pages', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/admin')->assertOk();
    $this->actingAs($user)->get('/admin/posts')->assertOk();
});

it('renders login and register pages without auth', function () {
    $this->get('/login')->assertOk();
    $this->get('/register')->assertOk();
    $this->get('/')->assertOk();
});

it('seeds tags with auto-slug generation', function () {
    $tag = Tag::create(['name' => 'Laravel']);
    expect($tag->slug)->toBe('laravel');
});
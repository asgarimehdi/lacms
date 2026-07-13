<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Public\BlogShow;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Livewire\Livewire;

beforeEach(function () {
    // Fresh database for each test
    $this->seed(DatabaseSeeder::class);

    // Get seeded entities
    $this->category = Category::first() ?? Category::create(['name' => 'تست فناوری', 'slug' => 'test-tech']);
    $this->tag = Tag::first() ?? Tag::create(['name' => 'لاراول']);
    $this->post = Post::where('is_published', true)->first() ?? Post::create([
        'title' => 'تست پست عمومی',
        'slug' => 'test-public-post',
        'content' => '<p>محتوای تست</p>',
        'is_published' => true,
        'user_id' => User::first()->id,
        'category_id' => $this->category->id,
    ]);
    if ($this->post->tags()->count() === 0) {
        $this->post->tags()->attach($this->tag);
    }

    $this->page = Page::where('slug', 'home')->first() ?? Page::create([
        'title' => 'تست صفحه عمومی',
        'slug' => 'test-public-page',
        'content' => '<p>محتوای صفحه تست</p>',
        'status' => 'published',
    ]);
});

describe('Public Homepage', function () {
    it('renders homepage without authentication', function () {
        $response = $this->get('/');
        $response->assertStatus(200);
    });

    it('shows site title in homepage', function () {
        $response = $this->get('/');
        $response->assertSee('سامانه مدیریت محتوا');
    });
});

describe('Public Blog Routes', function () {
    it('renders blog index without authentication', function () {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    });

    it('shows published posts on blog index', function () {
        $response = $this->get('/blog');
        $response->assertSee($this->post->title);
    });

    it('renders single post without authentication', function () {
        $response = $this->get('/blog/'.$this->post->slug);
        $response->assertStatus(200);
        $response->assertSee($this->post->title);
    });

    it('returns 404 for unpublished posts', function () {
        $unpublishedPost = Post::create([
            'title' => 'پست منتشر نشده',
            'slug' => 'unpublished-test',
            'content' => '<p>Test</p>',
            'is_published' => false,
        ]);

        $response = $this->get('/blog/'.$unpublishedPost->slug);
        $response->assertStatus(404);
    });

    it('shows post category and tags', function () {
        $response = $this->get('/blog/'.$this->post->slug);
        $response->assertSee($this->category->name);
        $response->assertSee($this->tag->name);
    });

    it('submits a comment that requires approval', function () {
        $post = Post::where('is_published', true)->first();
        Livewire::test(BlogShow::class, ['post' => $post])
            ->set('author_name', 'تست کامنت')
            ->set('author_email', 'commenter@example.com')
            ->set('body', 'این یک دیدگاه تستی برای بررسی عملکرد سیستم است')
            ->call('submitComment');

        $comment = Comment::where('author_name', 'تست کامنت')->first();
        expect($comment)->not->toBeNull();
        expect($comment->is_approved)->toBeFalse();
    });
});

describe('Public Category Routes', function () {
    it('renders category page without authentication', function () {
        $response = $this->get('/category/'.$this->category->slug);
        $response->assertStatus(200);
    });

    it('shows category name on category page', function () {
        $response = $this->get('/category/'.$this->category->slug);
        $response->assertSee($this->category->name);
    });
});

describe('Public Tag Routes', function () {
    it('renders tag page without authentication', function () {
        $response = $this->get('/tag/'.$this->tag->slug);
        $response->assertStatus(200);
    });

    it('shows tag name on tag page', function () {
        $response = $this->get('/tag/'.$this->tag->slug);
        $response->assertSee($this->tag->name);
    });
});

describe('Public Page Routes', function () {
    it('renders public page without authentication', function () {
        $response = $this->get('/p/'.$this->page->slug);
        $response->assertStatus(200);
    });

    it('shows page content', function () {
        $response = $this->get('/p/'.$this->page->slug);
        // Seeded 'home' page has content about CMS
        $response->assertSee('سامانه مدیریت محتوا');
    });

    it('returns 404 for unpublished pages', function () {
        $unpublishedPage = Page::create([
            'title' => 'صفحه منتشر نشده',
            'slug' => 'unpublished-page',
            'content' => '<p>Test</p>',
            'status' => 'draft',
        ]);

        $response = $this->get('/p/'.$unpublishedPage->slug);
        $response->assertStatus(404);
    });
});

describe('Admin Authentication', function () {
    it('redirects unauthenticated users to login', function () {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/posts')->assertRedirect('/login');
        $this->get('/admin/pages')->assertRedirect('/login');
        $this->get('/admin/settings')->assertRedirect('/login');
    });

    it('allows authenticated users to access admin', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
    });
});

describe('Database Models', function () {
    it('creates post via model with tags', function () {
        $post = Post::create([
            'title' => 'مدل تست',
            'slug' => 'model-test',
            'content' => '<p>Test content</p>',
            'is_published' => true,
            'user_id' => User::first()->id,
            'category_id' => $this->category->id,
        ]);
        expect(Post::where('title', 'مدل تست')->exists())->toBeTrue();
    });

    it('attaches tags to post', function () {
        $post = Post::create([
            'title' => 'تگ تست',
            'slug' => 'tag-test',
            'content' => '<p>Test</p>',
            'is_published' => true,
            'user_id' => User::first()->id,
            'category_id' => $this->category->id,
        ]);
        $tag = Tag::create(['name' => 'پی اچ پی']);
        $post->tags()->attach($tag->id);

        expect($post->tags->pluck('name')->all())->toContain('پی اچ پی');
    });

    it('has category relationship', function () {
        expect($this->post->category->name)->toBe($this->category->name);
    });

    it('has tags relationship', function () {
        expect($this->post->tags)->not->toBeEmpty();
    });

    it('generates slug automatically', function () {
        $tag = Tag::create(['name' => 'Test Slug Tag']);
        expect($tag->slug)->toBe('test-slug-tag');
    });

    it('settings returns single row', function () {
        Setting::current()->update(['site_name' => 'تست CMS']);
        expect(Setting::current()->site_name)->toBe('تست CMS');
        expect(Setting::count())->toBe(1);
    });

    it('creates and retrieves pages', function () {
        $page = Page::create([
            'title' => 'صفحه جدید',
            'slug' => 'new-page',
            'content' => '<p>Test</p>',
            'status' => 'published',
        ]);
        expect(Page::where('id', $page->id)->exists())->toBeTrue();
    });
});

describe('Login Form', function () {
    it('validates required fields', function () {
        Livewire::test(Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    });

    it('shows error with invalid credentials', function () {
        Livewire::test(Login::class)
            ->set('email', 'nobody@example.com')
            ->set('password', 'wrong')
            ->call('login')
            ->assertSee('credentials do not match');
    });
});

describe('Login via HTTP', function () {
    it('authenticated user can access admin', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
    });
});

describe('Register Form', function () {
    it('validates required fields', function () {
        Livewire::test(Register::class)
            ->call('register')
            ->assertHasErrors(['name', 'email', 'password']);
    });

    it('validates unique email', function () {
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::test(Register::class)
            ->set('name', 'Test')
            ->set('email', 'taken@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('validates password confirmation', function () {
        Livewire::test(Register::class)
            ->set('name', 'Test')
            ->set('email', 'test@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'different')
            ->call('register')
            ->assertHasErrors(['password']);
    });
});

describe('Logout', function () {
    it('logs out and redirects to home', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    });
});

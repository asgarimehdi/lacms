# Plan 008: Create PostFactory and CategoryFactory
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- database/factories/`

## Status
- **Priority**: P2
- **Effort**: M
- **Risk**: LOW
- **Depends on**: none
- **Category**: tests
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
`tests/Feature/CmsFeatureTest.php:21-28` creates posts inline in every test's
`beforeEach`. This is fragile: if the Post model changes (new required field,
column rename), every inline creation breaks. `PostFactory` centralizes test
data creation. `CategoryFactory` supports factory-based post creation (posts
require a `category_id`). This also enables Pest's `Post::factory()` in new
tests without duplication.

## Current state
- `database/factories/UserFactory.php` — the only factory. Follows Laravel 11
  `extends Factory<User>` pattern with `definition()` returning an array.
- `app/Models/Post.php` — `$fillable` is `['category_id', 'title', 'slug',
  'content', 'is_published', 'featured_image']`. Auto-slugs from title on create.
- `app/Models/Category.php` — `$fillable` is `['name', 'slug']`. Auto-slugs from
  name on create.
- `tests/Feature/CmsFeatureTest.php:21-28` — inline post creation:
  `Post::create(['title' => '...', 'slug' => '...', 'content' => '...', ...])`

Repo conventions: factories use `fake()->...()` for generated data; `#[Locked` attributes absent (no locked factories).

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:**
- `database/factories/PostFactory.php` (create)
- `database/factories/CategoryFactory.php` (create)
**Out of scope:** Any other factory; changes to test files (tests will work
without changes once factories exist, since `Post::create()` calls still work).

## Git workflow
- Branch: `advisor/008-post-category-factories`
- Commit style: `tests: add PostFactory and CategoryFactory`

## Steps

### Step 1: Create CategoryFactory

```bash
php artisan make:factory CategoryFactory --model=Category
```

Edit `database/factories/CategoryFactory.php`:
```php
<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category;

    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
```

**Verify**: `grep -n "extends Factory" database/factories/CategoryFactory.php`

### Step 2: Create PostFactory

```bash
php artisan make:factory PostFactory --model=Post
```

Edit `database/factories/PostFactory.php`:
```php
<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post;

    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => '<p>'.fake()->paragraphs(2, true).'</p>',
            'is_published' => true,
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
```

**Verify**: `grep -n "function definition" database/factories/PostFactory.php`

### Step 3: Wire PostFactory to Post model

In `app/Models/Post.php`, add:
```php
use Illuminate\Database\Eloquent\Factories\Factory;

/** @use HasFactory<PostFactory> */
use HasFactory;
```

And in `app/Livewire/Pages/Posts/Index.php:41`, verify that
`$this->tag_ids = $post->tags->pluck('id')->all();` is compatible with
`HasFactory` — it is, tags are a relationship not a factory concern.

### Step 4: Tests

```bash
php artisan test --compact
```

All existing tests pass. The new factories don't change behavior of existing
tests (tests still use `Post::create()` inline).

**Verify**: `php artisan test --compact` → exit 0

### Step 5: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
Add a smoke test in `tests/Feature/CmsFeatureTest.php`:
```php
it('PostFactory creates a published post', function () {
    $post = \App\Models\Post::factory()->create();
    expect($post->title)->not->toBeEmpty();
    expect($post->is_published)->toBeTrue();
});

it('PostFactory unpublished state works', function () {
    $post = \App\Models\Post::factory()->unpublished()->create();
    expect($post->is_published)->toBeFalse();
});
```

Pattern: follow `tests/Feature/CmsFeatureTest.php` style (Pest `it()`).

## Done criteria
- [ ] `database/factories/PostFactory.php` exists with `definition()` and `unpublished()` state
- [ ] `database/factories/CategoryFactory.php` exists with `definition()`
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only new factory files created (`git status`)

## STOP conditions
If `PostFactory.php` already exists, check its content — the plan should adapt rather than duplicate.

## Maintenance notes
- When the `featured_image` migration lands (plan 007), add it to the factory's
  `definition()` array: `'featured_image' => null,`.
- When `TagFactory` is needed, follow the same pattern — `Tag` needs no
  dependencies (just `name`).
- The `unpublished()` named constructor on PostFactory is useful for tests that
  need to verify 404 on unpublished posts.
# Plan 007: Add featured_image column to posts table
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- database/migrations/`
> If the migration was already added, treat as STOP — the finding may be stale.

## Status
- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: correctness
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
Templates in `resources/views/livewire/public/blog-show.blade.php:82-86` and
`blog-index.blade.php:83-84` reference `$post->featured_image` to render a
hero image above each post. The database migration never added this column, so
every post returns `null` and the feature never activates. Creating the column
and wiring EditorJS to populate it enables the feature end-to-end.

## Current state
- `database/migrations/2026_07_10_111736_create_posts_table.php` — posts table
  without `featured_image` column.
- `resources/views/livewire/public/blog-show.blade.php:82-86` — renders
  `<img src="{{ $post->featured_image }}">` with a gradient fallback.
- `app/Livewire/Pages/Posts/CreateEdit.php` — post editor; `data-editorjs` on
  the content field. EditorJS image tool configured in `resources/js/app.js:35-48`
  uploads to `/admin/upload-image` and returns `{ url }`. The `featured_image`
  field is not yet wired — post creation/update saves content JSON only.
- `app/Models/Post.php` — no `featured_image` in `$fillable`.

Repo conventions: use `php artisan make:migration` for migrations; `$table->string()`
for nullable URL columns.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Migrate | `php artisan migrate` | exit 0 |
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:**
- `database/migrations/YYYY_MM_DD_HHMMSS_add_featured_image_to_posts_table.php`
- `app/Models/Post.php`
**Out of scope:**
- EditorJS image integration (that's a separate feature — wire separately)
- Any change to views or JS

## Git workflow
- Branch: `advisor/007-add-featured-image-column`
- Commit style: `feat: add featured_image column to posts table`

## Steps

### Step 1: Create migration

```bash
php artisan make:migration add_featured_image_to_posts_table
```

Edit the generated migration file (`database/migrations/YYYY_MM_DD_*
_add_featured_image_to_posts_table.php`):

```php
public function up(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->string('featured_image')->nullable()->after('content');
    });
}
```

**Verify**: `grep -n "featured_image" database/migrations/*.php` → 1 match

### Step 2: Add to Post fillable

In `app/Models/Post.php`, add `'featured_image'` to `$fillable`:

```php
protected $fillable = ['category_id', 'title', 'slug', 'content', 'is_published', 'featured_image'];
```

**Verify**: `grep featured_image app/Models/Post.php`

### Step 3: Run migrations

```bash
php artisan migrate
```

**Verify**: `php artisan schema:dump --only-schema 2>&1 | grep featured_image` or
`php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasColumn('posts','featured_image') ? 'yes' : 'no';"`

### Step 4: Tests

```bash
php artisan test --compact
```

Existing tests should still pass (the column is nullable, no code relies on it yet).

**Verify**: `php artisan test --compact` → exit 0

### Step 5: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
Add a new test in `tests/Feature/CmsFeatureTest.php`:
```php
it('can create post with featured_image', function () {
    $user = \App\Models\User::factory()->create();
    $post = \App\Models\Post::factory()->create([
        'featured_image' => 'https://example.com/image.jpg',
        'user_id' => $user->id,
    ]);
    expect($post->featured_image)->toBe('https://example.com/image.jpg');
});
```
Note: requires `PostFactory` — if it doesn't exist yet, create it first per
`database/factories/UserFactory.php` as the pattern.

## Done criteria
- [ ] Migration adds nullable `featured_image` string column
- [ ] `Post` model `$fillable` includes `featured_image`
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only the migration and Post.php are modified

## STOP conditions
If the `featured_image` column already exists in the database (migration was
run independently), mark REJECTED — finding is stale.

## Maintenance notes
- The blade views already handle the nullable field with `<?if($post->featured_image)?>` — no view changes needed.
- If EditorJS image upload wiring is added later, populate `featured_image` from
  the first image block in the EditorJS JSON. See `resources/js/app.js:41` for
  the upload endpoint.
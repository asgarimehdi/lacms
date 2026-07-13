# Plan 009: Add compound index on comments(post_id, is_approved)
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- database/migrations/`

## Status
- **Priority**: P3
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: perf
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
`app/Livewire/Public/BlogShow.php:22` eager-loads comments filtered by
`is_approved = true` for every post page view:
```php
$this->post->load(['category', 'tags', 'user',
    'comments' => fn ($q) => $q->where('is_approved', true)->latest()]);
```
Without a compound index on `(post_id, is_approved)`, the query does a full
table scan on `comments` filtered by `is_approved`. On a table with thousands
of comments, this adds measurable latency per page load. A compound index
eliminates the scan.

## Current state
`database/migrations/2026_07_10_174723_create_comments_table.php` — defines
`$table->id()`, `$table->foreignId('post_id')`, `$table->boolean('is_approved')`.
Only the primary key and `post_id` foreign key are indexed.
`app/Livewire/Public/BlogShow.php:22` — eager-loads `comments` with where clause.

Repo conventions: use `php artisan make:migration` and `$table->index()` for
compound indexes.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Migrate | `php artisan migrate` | exit 0 |
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:** one new migration file
**Out of scope:** Any model, controller, or view changes

## Git workflow
- Branch: `advisor/009-comments-index`
- Commit style: `perf: add compound index on comments(post_id, is_approved)`

## Steps

### Step 1: Create migration

```bash
php artisan make:migration add_index_to_comments_table
```

Edit the generated migration:
```php
public function up(): void
{
    Schema::table('comments', function (Blueprint $table) {
        $table->index(['post_id', 'is_approved'], 'comments_post_approved_index');
    });
}

public function down(): void
{
    Schema::table('comments', function (Blueprint $table) {
        $table->dropIndex('comments_post_approved_index');
    });
}
```

**Verify**: `grep -n "comments_post_approved_index" database/migrations/*.php`

### Step 2: Run migration

```bash
php artisan migrate
```

**Verify**: `php artisan tinker --execute="echo \Illuminate\Support\Facades\Schema::hasIndex('comments', 'comments_post_approved_index') ? 'yes' : 'no';"`

### Step 3: Tests

**Verify**: `php artisan test --compact` → exit 0

### Step 4: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
No new tests — this is an index-only change. Existing `comment` tests in
`CmsFeatureTest.php` verify the approval workflow and implicitly exercise the
query path. Run the full suite.

## Done criteria
- [ ] Migration adds `comments(post_id, is_approved)` compound index
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only one new migration file added

## STOP conditions
If the index already exists, mark REJECTED.

## Maintenance notes
- The index also benefits any query filtering comments by both post and approval
  status (e.g. admin comment list with filters).
- If a future plan adds `approved_at` timestamp, add it as a third column to the
  index: `(post_id, is_approved, created_at)` — covers both the existing filter
  and a time-range query.
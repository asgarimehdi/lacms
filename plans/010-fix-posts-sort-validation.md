# Plan 010: Fix sortBy column validation in Posts Index
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- app/Livewire/Pages/Posts/Index.php`

## Status
- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: correctness
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
`app/Livewire/Pages/Posts/Index.php:71` uses `array_values($this->sortBy)` to spread
`$sortBy` into `orderBy(...)`. `$sortBy` is `['column' => 'title', 'direction' => 'asc']`
— array_values strips the keys, producing `[...array_values]` which correctly
produces `[...]. But if an attacker or a misbehaving Livewire dev-tool sends a
tampered `$sortBy` payload with a non-existent column name (e.g. `'column' => 'sql_injection'`),
Laravel throws a `QueryException` with a 500 error and an SQL error message potentially
leaking the query structure.

Additionally, a secondary typo exists on line 19: `published__filter` (double underscore).
The `updated()` listener at line 42 checks for `'published_filter'` (single underscore), so
changing `category_filter`/`published_filter` never invalidates the `$cachedPosts` cache,
leaking stale data between filter changes.

## Current state
`app/Livewire/Pages/Posts/Index.php:19`:
```php
public bool $published__filter = false;  // double underscore — typo
```
Line 42:
```php
if (in_array($name, ['search', 'category_filter', 'published_filter', 'sortBy'])) {
```
The listener checks `'published_filter'` but the property is `published__filter` — never matches.

Line 71:
```php
->orderBy(...array_values($this->sortBy))
```
If `sortBy.column` is not a valid posts column, this throws a 500.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:** `app/Livewire/Pages/Posts/Index.php`
**Out of scope:** Any other file

## Git workflow
- Branch: `advisor/010-fix-posts-sort-validation`
- Commit style: `fix: validate sort column and fix typo in Posts Index`

## Steps

### Step 1: Fix the double-underscore property name

Change line 19 from:
```php
public bool $published__filter = false;
```
To:
```php
public bool $published_filter = false;
```

### Step 2: Add column validation in posts()

After `$this->cachedPosts = null;` check in `posts()`, validate the column:

```php
$allowedColumns = ['id', 'title', 'is_published', 'updated_at', 'created_at'];
$column = $this->sortBy['column'] ?? 'title';
if (! in_array($column, $allowedColumns, true)) {
    $column = 'title';
}
```

Then replace the raw `orderBy(...)` call with validated values:

```php
->orderBy($column, $this->sortBy['direction'] ?? 'asc')
```

Full `posts()` method should be:
```php
public function posts(): Collection
{
    if ($this->cachedPosts !== null) {
        return $this->cachedPosts;
    }

    $allowedColumns = ['id', 'title', 'is_published', 'updated_at', 'created_at'];
    $column = $this->sortBy['column'] ?? 'title';
    if (! in_array($column, $allowedColumns, true)) {
        $column = 'title';
    }

    $this->cachedPosts = Post::query()
        ->with(['category', 'tags'])
        ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->when($this->category_filter, fn ($q) => $q->where('category_id', $this->category_filter))
        ->when($this->published_filter !== null, fn ($q) => $q->where('is_published', $this->published_filter))
        ->orderBy($column, $this->sortBy['direction'] ?? 'asc')
        ->get();

    return $this->cachedPosts;
}
```

**Verify**: `grep -n "allowedColumns" app/Livewire/Pages/Posts/Index.php`

### Step 3: Tests

```bash
php artisan test --compact
```

**Verify**: `php artisan test --compact` → exit 0

### Step 4: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
Add tests in `tests/Feature/CmsFeatureTest.php`:
```php
it('sorting by invalid column falls back to title', function () {
    Livewire::test(\App\Livewire\Pages\Posts\Index::class)
        ->set('sortBy', ['column' => 'nonexistent', 'direction' => 'asc'])
        ->assertSee('title'); // should not error
});
```

Note: requires Livewire component testing. If Livewire tests prove too
complex for this plan, omit the component test and rely on the manual
verification that an invalid column does not throw.

## Done criteria
- [ ] `published__filter` → `published_filter` (single underscore)
- [ ] `updated()` listener correctly matches `published_filter`
- [ ] `posts()` has `$allowedColumns` whitelist and falls back to `'title'`
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only `app/Livewire/Pages/Posts/Index.php` modified

## STOP conditions
If the typo was already fixed and column validation was added, mark REJECTED.

## Maintenance notes
- The `allowedColumns` list must be updated when new sortable columns are
  added (e.g. `category.name` — but note that `category.name` requires a join
  and would need `orderBy('category', ...)` + explicit join, not simple column).
  Stick to columns that exist directly on the `posts` table.
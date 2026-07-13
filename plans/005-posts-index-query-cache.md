# Plan 005: Fix double query in Posts admin Index component
> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README. md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**: `git diff --stat 8423024..HEAD -- app/ Livewire/Pages/Posts/Index. php` → if non-empty, compare "Current state" excerpts
> against the live code before proceeding; on mismatch, treat as STOP condition.
## Status
- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: perf
- **Planned at**: commit `8423024`, 2026-07-13
- **Issue**: (to be created)
## Why this matters
`app/Livewire/Pages/Posts/Index. php` defines `posts()` and `with()` on the same
component. Livewire calls `with()` on every render to merge data into the view.
Inside `with()`, `$this->posts()` re-executes the query on every render cycle.
For a page with 20 posts, this means 2 queries per page load instead of 1 — and
if the component re-renders (e.g. Livewire polling or a user interaction),
the query fires again. The fix: cache the result of `posts()` so it only
queries once per component lifecycle.
## Current state
`app/Livewire/Pages/Posts/Index. php:47-56`: `posts()` method calls `Post:: query()->with([...])->get()`. No memoization.
`app/Livewire/Pages/Posts/Index. php:58-65`: `with()` returns `'posts' => $this->posts()`, `'headers' => $this->headers()`, `'categories' => Category::all()->map(...)`.
This means `posts()` runs on every render when `with()` is called.
Repo conventions: `Posts/Index. php` is a named Livewire class component
(the `. php` file, not the blade file). See `app/Livewire/Pages/Tags/Index. php`
for a similar pattern (also has `tags()` + `with()` without memoization —
this plan fixes only `Posts/Index. php`).
## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0, all pass |
| Pint | `vendor/ bin/ pint --dirty --format agent` | exit 0 |
## Scope
**In scope:** `app/Livewire/Pages/Posts/Index. php`
**Out of scope:** Other Index components (Tags, Pages, Categories) — fix those
separately if needed.
## Git workflow
- Branch: `advisor/005-posts-index-query-cache`
- Commit style: `perf: cache posts query in Posts Index component`
## Steps
### Step 1: Add posts cache
In `app/Livewire/Pages/Posts/Index. php`, add a `?Collection $cachedPosts = null`
property and modify `posts()` to use it:
```php
public array $selected = [];
public array $sortBy = ['column' => 'title', 'direction' => 'asc'];
private ?Collection $cachedPosts = null;
```
Then change the `posts()` method:
```php
public function posts(): Collection
{
    if ($this->cachedPosts !== null) {
        return $this->cachedPosts;
    }

    $this->cachedPosts = Post::query()
        ->with(['category', 'tags'])
        ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
        ->when($this->category_ filter, fn($q) => $q->where('category_ id', $this->category_ filter))
        ->when($this->published_ filter !== null, fn($q) => $q->where('is_ published', $this->published_ filter))
        ->orderBy(...array_ values($this->sortBy))
        ->get();

    return $this->cachedPosts;
}
```
Note: the trailing semicolons and underscores in the code above must match the
ACTUAL file — use the existing code from `app/Livewire/Pages/Posts/Index. php`
and only add the cache logic around it. The property name must not conflict
with Livewire's managed properties — use a private property.
**Verify**: `grep -n "cachedPosts" app/ Livewire/Pages/Posts/Index. php` → 3 matches
### Step 2: Invalidate cache on filter changes
Livewire re-renders on property changes. When `search`, `category_ filter`,
`published_ filter`, or `sortBy` changes, the cache must be invalidated.
Add `updated*` method to `Index. php`:
```php
public function updated(string $name): void
{
    if (in_array($name, ['search', 'category_ filter', 'published_ filter', 'sortBy'])) {
        $this->cachedPosts = null;
    }
}
```
**Verify**: `grep -n "updated" app/Livewire/Pages/Posts/Index. php` → matches the new method
### Step 3: Run tests
**Verify**: `php artisan test --compact` → exit 0, all pass
### Step 4: Format
**Verify**: `vendor/ bin/ pint --dirty --format agent` → exit 0
## Test plan
No new tests needed — existing `CmsFeatureTest. php` has tests for the
posts admin page. The fix is transparent to tests. However, add a query-count
assertion as a regression guard: in `CmsFeatureTest. php`, inside the admin
tests group, add:
```php
it('admin posts index queries posts only once', function () {
    $user = User::factory()->create();
    Post::factory()->count(3)->create(['user_ id' => $user->id]);

    DB::listen(fn($e) => $queries[] = $e->sql);
    $queries = [];

    $this->actingAs($user)->get('/admin/posts');

    $postQueries = array_filter($queries, fn($q) => str_ contains($q, 'select') && str_ contains($q, 'posts'));
    expect(count($postQueries))->toBeLessThanOrEqual(2); // one for posts + one for count, acceptable
});
```
Pattern: `DB::listen` is used in Pest via a callback inside `beforeEach`. Check
`tests/Feature/CmsFeatureTest. php` for existing DB listening patterns — if none
exist, omit the query-count test and rely on the existing functional tests.
## Done criteria
- [ ] `app/Livewire/Pages/Posts/Index. php` has `$cachedPosts` private property
- [ ] `app/Livewire/Pages/Posts/Index. php` has `updated()` method that resets cache
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/ bin/ pint --dirty --format agent` exits 0
- [ ] Only `app/Livewire/Pages/Posts/Index. php` is modified (`git status`)
## STOP conditions
Stop and report back if: any test fails. The cache logic may interact with
Livewire's property hydration in unexpected ways.
## Maintenance notes
- The same pattern applies to `Tags/Index. php`, `Categories/Index. php`,
  and `Pages/Index. php` — they all have the same `with()` + `*()` pattern.
  Apply this same fix to them as a follow-up if performance is observed.
- If Livewire's built-in `with()` caching (`->cacheFields([...])` in Livewire 3+)
  is used elsewhere in the codebase, prefer that instead of a manual cache.
  Check if any other component uses `->cacheFields()` or `#[Cache]` attribute.
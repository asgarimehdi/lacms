# Plan 003: Add rate limiting to login, register, and comment submission
> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**: `git diff --stat 8423024..HEAD -- routes/auth.php routes/web.php`
> If any in-scope file changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status
- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: security
- **Planned at**: commit `8423024`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
Login form, register form, and public comment submission have no rate limiting.
An attacker can brute-force credentials on `/login` or spam comment submissions
on any public post. Laravel's built-in `throttle:` middleware handles this cheaply.
Adding it to the Livewire route (which handles both GET mount and POST actions
via a single endpoint) covers the entire interaction surface.

## Current state
- `routes/auth.php:5-6`:
  ```php
  Route::livewire('/login', 'pages::auth.login')->name('login');
  Route::livewire('/register', 'pages::auth.register')->name('register');
  ```
- `routes/web.php:13`:
  ```php
  Route::livewire('/blog/{post:slug}', 'public.blog-show')->name('public.posts.show');
  ```
  This is the comment submission route.

No throttle middleware is present on any of these.

Repo conventions: route middleware is chained with `->middleware(...)`.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Route list | `php artisan route:list --path=login` | shows route with middleware |
| Tests | `php artisan test --compact` | exit 0, all pass |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:**
- `routes/auth.php` — add throttle to login and register routes
- `routes/web.php` — add throttle to blog post show route

**Out of scope:**
- Any changes to `app/Livewire/` component files
- Rate limit config changes (`config/routing.php` or `.env`)
- Adding captcha or IP-based blocking

## Git workflow
- Branch: `advisor/003-rate-limit-auth-comments`
- Commit style: `security: add rate limiting to login, register, and comment submission`
- Do NOT push or open a PR.

## Steps

### Step 1: Add throttle to login and register routes

In `routes/auth.php`, change:
```php
Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');
```
To:
```php
Route::livewire('/login', 'pages::auth.login')->middleware('throttle:5,1')->name('login');
Route::livewire('/register', 'pages::auth.register')->middleware('throttle:5,1')->name('register');
```
`throttle:5,1` = 5 requests per minute per IP to the Livewire component endpoint.
This covers both the page load (GET) and form submission (POST) since Livewire
uses a single endpoint for both.

**Verify**: `php artisan route:list --path=login` shows `throttle:5,1` in middleware column.
**Verify**: `php artisan route:list --path=register` shows `throttle:5,1` in middleware column.

### Step 2: Add throttle to comment submission route

In `routes/web.php`, change:
```php
Route::livewire('/blog/{post:slug}', 'public.blog-show')->name('public.posts.show');
```
To:
```php
Route::livewire('/blog/{post:slug}', 'public.blog-show')
    ->middleware('throttle:3,1')
    ->name('public.posts.show');
```
`throttle:3,1` = 3 requests per minute per IP for the blog post page. This
covers all interactions including comment submissions.

**Verify**: `php artisan route:list --path=blog/` shows `throttle:3,1` in middleware column.

### Step 3: Run tests

**Verify**: `php artisan test --compact` → exit 0, all pass

### Step 4: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan

Add a middleware presence check to `tests/Feature/CmsFeatureTest.php`:
```php
it('login route has throttle middleware', function () {
    $route = app('router')->getRoutes()->getByName('login');
    expect($route->middleware())->toContain('throttle');
});
it('register route has throttle middleware', function () {
    $route = app('router')->getRoutes()->getByName('register');
    expect($route->middleware())->toContain('throttle');
});
it('blog post route has throttle middleware', function () {
    $route = app('router')->getRoutes()->getByName('public.posts.show');
    expect($route->middleware())->toContain('throttle');
});
```
Pattern: check existing `describe('Admin Authentication')` tests in `CmsFeatureTest.php`
for how to reference named routes.

**Verify**: `php artisan test --compact --filter="throttle middleware"` → 3 pass

## Done criteria
Machine-checkable. ALL must hold:
- [ ] `php artisan route:list --path=login` output contains `throttle:5,1`
- [ ] `php artisan route:list --path=register` output contains `throttle:5,1`
- [ ] `php artisan route:list --path=blog/` output contains `throttle:3,1`
- [ ] `php artisan test --compact --filter="throttle middleware"` → 3 pass
- [ ] `php artisan test --compact` (full suite) → exit 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only `routes/auth.php` and `routes/web.php` are modified (`git status`)

## STOP conditions

Stop and report back if:
- The route names change (`login`, `register`, `public.posts.show` no longer exist).
- A step's verification fails after two fix attempts.

## Maintenance notes
- `throttle:5,1` = 5 attempts/minute for login/register. Reduce to `3,1` if
  credential stuffing is observed.
- `throttle:3,1` = 3 page loads/minute for blog posts. Prevents spam of comment
  submissions. If comment submission is separated into its own API route later,
  that route needs its own throttle config.
- Laravel auto-adds `X-RateLimit-*` response headers. No custom response
  handling needed.
- If the app adds a REST API later, those routes should have separate throttle
  configs tuned for API clients.
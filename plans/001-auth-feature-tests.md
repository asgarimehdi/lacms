# Plan 001: Add login/register/logout feature tests

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**: `git diff --stat 8423024..HEAD -- tests/Feature/CmsFeatureTest.php`
> If any in-scope file changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: tests
- **Planned at**: commit `8423024`, 2026-07-13
- **Issue**: (to be created)

## Why this matters

`CmsFeatureTest.php` has 28 passing tests but zero tests that submit the login
form, register a new user, or verify logout. A regression in auth — wrong
redirect, session not regenerated, validation message missing — goes undetected.
The tests that exist (`renders login page without auth`) only check HTTP 200,
not behaviour.

## Current state

Relevant files:

- `tests/Feature/CmsFeatureTest.php` — existing test suite with `beforeEach`
  seeding `DatabaseSeeder`, authenticating via `$this->actingAs()`, and
  testing public routes. Last test group is `Login & Register` with only
  `assertOk()` checks.
- `app/Livewire/Pages/Auth/Login.php` — `login()` action calls
  `Auth::attempt()`, regenerates session, redirects to `/admin`, and adds
  error on failure (`app/Livewire/Pages/Auth/Login.php:32-43`).
- `app/Livewire/Pages/Auth/Register.php` — `register()` action validates,
  creates user, logs in, regenerates session, redirects to `/admin`
  (`app/Livewire/Pages/Auth/Register.php:35-44`).
- `routes/auth.php:8-13` — logout route calls `auth()->logout()`,
  invalidates + regenerates session, redirects to `/`.

Repo conventions (from `tests/Feature/CmsFeatureTest.php`):
- Uses Pest `describe/it` syntax.
- `beforeEach` seeds the DB and creates `$this->post`, `$this->category`,
  `$this->tag`, `$this->page` from seeded data.
- `User::factory()->create()` for test-only users.
- `actingAs($user)` for authenticated requests.
- Assertions use `$response->assertRedirect()` and `$response->assertSessionHas()`.

## Commands you will need

| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0, all pass |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Suggested executor toolkit

- Use `php artisan make:test --pest AuthFeatureTest` per convention.

## Scope

**In scope:**
- `tests/Feature/CmsFeatureTest.php` (add test groups)

**Out of scope:**
- Any changes to auth components (`app/Livewire/Pages/Auth/*.php`)
- Any changes to routes
- Any changes to `User` model

## Git workflow

- Branch: `advisor/001-auth-tests`
- Commit style: `tests: add login/register/logout feature tests` per
  `git log --oneline` convention (lower-case imperative).
- Do NOT push or open a PR unless the operator instructed it.

## Steps

### Step 1: Add login form submission tests

In `tests/Feature/CmsFeatureTest.php`, add a new `describe('Login Form')` block
inside the existing `describe('Login & Register')` group (or as a standalone
group above it):

```php
describe('Login Form', function () {
    it('logs in with valid credentials and redirects to admin', function () {
        $user = User::factory()->create();

        Livewire::test(\App\Livewire\Pages\Auth\Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect('/admin');
    });

    it('shows error with invalid credentials', function () {
        Livewire::test(\App\Livewire\Pages\Auth\Login::class)
            ->set('email', 'nobody@example.com')
            ->set('password', 'wrong')
            ->call('login')
            ->assertSee('credentials do not match');
    });

    it('validates required fields', function () {
        Livewire::test(\App\Livewire\Pages\Auth\Login::class)
            ->call('login')
            ->assertHasErrors(['email', 'password']);
    });
});
```

**Verify**: `php artisan test --compact --filter="Login Form"` → 3 pass

### Step 2: Add register tests

After the Login Form block, add:

```php
describe('Register Form', function () {
    it('creates account, logs in, and redirects to admin', function () {
        Livewire::test(\App\Livewire\Pages\Auth\Register::class)
            ->set('name', 'New User')
            ->set('email', 'newuser@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertRedirect('/admin');

        expect(User::where('email', 'newuser@example.com')->exists())->toBeTrue();
    });

    it('validates unique email', function () {
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::test(\App\Livewire\Pages\Auth\Register::class)
            ->set('name', 'Test')
            ->set('email', 'taken@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('register')
            ->assertHasErrors(['email']);
    });

    it('validates password confirmation', function () {
        Livewire::test(\App\Livewire\Pages\Auth\Register::class)
            ->set('name', 'Test')
            ->set('email', 'test@example.com')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'different')
            ->call('register')
            ->assertHasErrors(['password']);
    });
});
```

**Verify**: `php artisan test --compact --filter="Register Form"` → 3 pass

### Step 3: Add logout test

Add a standalone `describe('Logout')` group:

```php
describe('Logout', function () {
    it('logs out and redirects to home', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    });
});
```

**Verify**: `php artisan test --compact --filter="Logout"` → 1 pass

### Step 4: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan

- New tests: login with valid credentials, login with invalid credentials,
  login validation, register creates account, register validates unique email,
  register validates password confirmation, logout.
- Pattern to follow: model after `describe('Public Blog Routes')` in
  `tests/Feature/CmsFeatureTest.php` — same Pest `describe/it` structure,
  same `beforeEach` seeding.
- Verification: `php artisan test --compact` → all pass including the 6 new tests
  (28 existing + 6 new = 34 total).

## Done criteria

Machine-checkable. ALL must hold:

- [ ] `php artisan test --compact` exits 0; output shows 34 tests
- [ ] `php artisan test --compact --filter="Login Form"` → 3 pass
- [ ] `php artisan test --compact --filter="Register Form"` → 3 pass
- [ ] `php artisan test --compact --filter="Logout"` → 1 pass
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] No files outside `tests/Feature/CmsFeatureTest.php` are modified
  (`git status`)

## STOP conditions

Stop and report back if:

- A test fails with a genuine auth bug (not a test mistake). In that case,
  record the finding and STOP — do not fix the bug in this plan.
- The `App\Livewire\Pages\Auth\Login` or `Register` class paths have changed.

## Maintenance notes

- Future auth changes (redirect path, validation rules) should have a
  corresponding test added here.
- If `Login` or `Register` are converted to anonymous Livewire components
  (`new class extends Component`), the `Livewire::test(...)` call needs the
  class reference updated.
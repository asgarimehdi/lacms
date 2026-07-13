# Plan 002: Guard file upload endpoint with CSRF and rate limiting
> **Drift check (run first)**: `git diff --stat 8423024..HEAD -- routes/auth.php`
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
`POST /admin/upload-image` is gated only by `auth()` middleware. Any authenticated
user can upload arbitrarily many files with no type or size checks. In practice,
the JS client (`resources/js/app.js:38-46`) sends a CSRF token and is the only
intended caller — but the endpoint is a public-facing attack surface on the
authenticated path. Adding validation and rate limiting makes it match Laravel
best practices.

## Current state

Relevant file:
- `routes/auth.php:16-22` — the current upload route:
```php
Route::post('/admin/upload-image', function () {
    $file = request()->file('image');
    abort_unless($file, 400);
    $path = $file->store('uploads', 'public');

    return response()->json(['success' => true, 'url' => asset('storage/'.$path)]);
})->middleware('auth');
```

Repo conventions:
- Routes defined in `routes/auth.php` using anonymous functions.
- Response uses `response()->json(...)` for JSON endpoints.
- `abort_unless($file, 400)` used for input validation (not a pattern — just
  current state).

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |
| Tests | `php artisan test --compact` | exit 0 |

## Scope
**In scope:**
- `routes/auth.php` (edit the upload route block)

**Out of scope:**
- Any changes to `resources/js/app.js` (JS is already correct with CSRF header)
- Any changes to `config/filesystems.php` or storage config
- File system changes outside this route

## Git workflow
- Branch: `advisor/002-secure-file-upload`
- Commit style: `security: validate and rate-limit image upload endpoint`

## Steps

### Step 1: Add file validation and move to controller
Do NOT add logic inside the route closure. Per Laravel conventions (see
`routes/web.php` for examples of clean separation), extract to a controller.

Create `app/Http/Controllers/ImageUploadController.php`:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->file('image');

        if (! $file) {
            abort(400, 'No file provided');
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            abort(422, 'Invalid file type');
        }

        if ($file->getSize() > 2 * 1024 * 1024) {
            abort(422, 'File too large (max 2MB)');
        }

        $path = $file->store('uploads', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path),
        ]);
    }
}
```

**Verify**: file exists at `app/Http/Controllers/ImageUploadController.php`

### Step 2: Update the route
Replace the anonymous function in `routes/auth.php:16-22`:
```php
use App\Http\Controllers\ImageUploadController;
// ...
Route::post('/admin/upload-image', ImageUploadController::class)
    ->middleware(['auth', 'throttle:10,1']);
```
The `throttle:10,1` allows 10 uploads per minute per user.

**Verify**: `php artisan route:list --path=upload` → shows `ImageUploadController`

### Step 3: Format
**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
- Add `ImageUploadControllerTest.php` in `tests/Feature/`:
```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('ImageUploadController', function () {
    beforeEach(fn () => Storage::fake('public'));

    it('rejects unauthenticated requests', function () {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->post('/admin/upload-image', ['image' => $file]);
        $response->assertRedirect('/login');
    });

    it('accepts image upload for authenticated user', function () {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this->actingAs($user)->post('/admin/upload-image', ['image' => $file]);
        $response->assertJson(['success' => true]);
    });

    it('rejects non-image files', function () {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post('/admin/upload-image', ['image' => $file]);
        $response->assertStatus(422);
    });

    it('rejects files over 2MB', function () {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('large.jpg', 3000, 'image/jpeg'); // >2MB

        $response = $this->actingAs($user)->post('/admin/upload-image', ['image' => $file]);
        $response->assertStatus(422);
    });

    it('rate limits by user', function () {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        for ($i = 0; $i < 10; $i++) {
            $this->actingAs($user)->post('/admin/upload-image', ['image' => $file]);
        }

        $response = $this->actingAs($user)->post('/admin/upload-image', ['image' => $file]);
        $response->assertStatus(429);
    });
});
```

Use `php artisan make:test --pest ImageUploadControllerTest` to create the file.

**Verify**: `php artisan test --compact --filter="ImageUploadController"` → 5 pass

## Done criteria
Machine-checkable. ALL must hold:
- [ ] `app/Http/Controllers/ImageUploadController.php` exists
- [ ] `php artisan route:list --path=upload` shows `ImageUploadController` with
  `auth,throttle:10,1` middleware
- [ ] `php artisan test --compact --filter="ImageUploadController"` → 5 pass
- [ ] `php artisan test --compact` (full suite) → exit 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only `routes/auth.php` and `app/Http/Controllers/ImageUploadController.php`
  and the new test file are modified (`git status`)

## STOP conditions
Stop and report back if:
- The upload endpoint signature changes significantly (different param names,
  different response shape) — the plan needs updating.
- The JS client in `resources/js/app.js` would need changes to match a new
  endpoint response — note it in the report but do NOT modify the JS.

## Maintenance notes
- If image types need to expand (WebP, AVIF), update `$allowed` in the controller.
- The `throttle:10,1` limit: 10 uploads/minute is generous for EditorJS usage.
  Reduce if abuse is observed. Rate limit response is Laravel's default 429.
- If a file size config from `config/upload.php` is added later, the controller
  should read from it instead of the hardcoded 2MB.
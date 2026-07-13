# Plan 006: Remove SVG from allowed upload types
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- app/Http/Controllers/ImageUploadController.php`
> If the file changed since this plan was written, compare the excerpts against
> the live code before proceeding; on mismatch, treat as STOP condition.

## Status
- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: security
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
`app/Http/Controllers/ImageUploadController.php:18` allows `image/svg+xml` uploads.
SVG files execute JavaScript when loaded in a browser from the app's origin. Any
authenticated user can upload a file containing `<script>alert(document.domain)</script>`
and embed it as a post's featured image. The script runs in the session of any
visitor viewing the post. Removing SVG from the allowed list closes this attack
surface entirely with a one-line change.

## Current state
`app/Http/Controllers/ImageUploadController.php:18`:
```php
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
```
Remove `'image/svg+xml'` from the array.

Repo conventions: controller uses `abort(code, message)` for validation errors.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:** `app/Http/Controllers/ImageUploadController.php`
**Out of scope:** Any other file; do not add SVG sanitization libraries

## Git workflow
- Branch: `advisor/006-remove-svg-upload`
- Commit style: `security: remove SVG from allowed upload types`

## Steps

### Step 1: Remove SVG from allowed types

Change `app/Http/Controllers/ImageUploadController.php:18` from:
```php
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
```
To:
```php
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
```

**Verify**: `grep -n "svg" app/Http/Controllers/ImageUploadController.php` → no matches

### Step 2: Tests

Run the existing suite — no new tests needed for a type removal. The existing
`ImageUploadController` tests (from plan 002) already test rejection of non-image
files; verify they still pass.

**Verify**: `php artisan test --compact` → exit 0

### Step 3: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
No new tests. Existing plan-002 tests (`it('rejects non-image files')`) verify
the 422 response path. Run the full suite.

## Done criteria
- [ ] `'image/svg+xml'` absent from `$allowed` array
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only `app/Http/Controllers/ImageUploadController.php` modified (`git status`)

## STOP conditions
Stop and report if: the allowed array was already changed.

## Maintenance notes
- If SVG support is genuinely needed later, serve uploaded SVGs with
  `Content-Security-Policy: default-src 'none'` and a `Content-Type: text/plain`
  header to disable script execution. A separate plan would be needed for that.
- The `image/webp` entry is fine — WebP is a proper raster format with no scripting.
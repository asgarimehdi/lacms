# Plan 004: Replace User model #[Fillable] attribute with $fillable array
> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/READMB.md` — unless a reviewer dispatched you and told you they
> maintain the index.
> **Drift check (run first)**: `git diff --stat 8423024..HEAD -- app/Models/User.php`
> If the file changed, compare against the "Current state" excerpt before
> proceeding; on mismatch, treat as STOP condition.
## Status
- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: tech- debt
- **Planned at**: commit `8423024`, 2026-07-13
- **Issue**: (to be created)
## Why this matters
`app/Models/User. php:13` uses `#[Fillable(['name', 'email', 'password'])` as a PHP
attribute — non-standard. All other models use `protected $fillable = [...]`
array syntax. Using attributes here means: (1) inconsistency with the rest of
the codebase, (2) potential unexpected interaction with `HasFactory` trait, (3)
developer confusion when copying the pattern. Replacing with the standard array
is a one- line change that removes ambiguity.
## Current state
`app/Models/User. php:13-14`:
```php
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
```
Repo conventions: `Category. php`, `Post. php`, `Comment. php`, `Page. php` all use
`protected $fillable = [...]` array. Keep `#[Hidden]` attribute (standard in
Laravel 11+).
## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0, all pass |
| Verify fillable | `php artisan tinker --execute="echo json_encode((new \App\Models\User())->getFillable());"` | `["name","email","password"]` |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |
## Scope
**In scope:** `app/Models/User.php`
**Out of scope:** Any other model, factory, or test files
## Git workflow
- Branch: `advisor/004-user-fillable-array`
- Commit style: `refactor: replace User #[Fillable] attribute with $fillable array`
## Steps
### Step 1: Replace attribute with $fillable array
In `app/Models/User.php`, remove `#[Fillable([...])` and add inside the class:
```php
protected $fillable = [
    'name',
    'email',
    'password',
];
```
Keep `#[Hidden(['password', 'remember_token'])]` as-is on the class.
**Verify**: `grep -n "Fillable" app/Models/User.php` → no matches
### Step 2: Verify factory still works
**Verify**: `php artisan tinker --execute="echo json_encode((new \App\Models\User())->getFillable());"` → `["name","email","password"]`
### Step 3: Run full test suite
**Verify**: `php artisan test --compact` → exit 0, all pass
### Step 4: Format
**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0
## Test plan
No new tests needed — existing `describe('Database Models')` tests verify User
creation and factory usage.
## Done criteria
- [ ] `grep -n "Fillable" app/Models/User.php` returns no matches
- [ ] `app/Models/User.php` has `protected $fillable = ['name', 'email', 'password']`
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only `app/Models/User.php` is modified (`git status`)
## STOP conditions
Stop and report back if: any test fails after the change.
## Maintenance notes
`#[Hidden]` is kept as-is. Future fields added to mass-assignment whitelist
go in the `$fillable` array. If the team later prefers attributes for all
models, this plan's approach is reversible.
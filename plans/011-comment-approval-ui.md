# Plan 011: Add comment approval queue admin UI
> **Drift check (run first)**: `git diff --stat df55ab9..HEAD -- app/Livewire/Pages/`

## Status
- **Priority**: P2
- **Effort**: M
- **Risk**: LOW
- **Depends on**: none
- **Category**: direction
- **Planned at**: commit `df55ab9`, 2026-07-13
- **Issue**: (to be created)

## Why this matters
The comment submission workflow (via `BlogShow::submitComment()`) creates comments
with `is_approved = false`. These comments are invisible until approved. The admin
sidebar has no menu entry for comments, no UI to list pending comments, and no
approve/reject action. Approved comments exist in the seed data, but the full
moderation loop is incomplete.

## Current state
- `app/Models/Comment.php` — `$fillable = ['post_id', 'author_name', 'author_email',
  'body', 'is_approved']`, `is_approved` cast to boolean.
- `app/Livewire/Public/BlogShow.php` — `submitComment()` creates `is_approved => false`
  comments; approved comments are eager-loaded on posts.
- `routes/web.php` — no admin comments route.
- `app/Livewire/Pages/` — no Comments subdirectory.
- `resources/views/layouts/app.blade.php` — admin sidebar at line 54 has no
  "Comments" menu item.
- `database/seeders/DatabaseSeeder.php` — creates approved comments with
  `is_approved => true` (seeding is not the moderation workflow).

Repo conventions: use MaryUI table pattern (`x-table` + `headers()` + data collection
as in `Tags/Index.php`); use `Toast` trait for actions.

## Commands you will need
| Purpose | Command | Expected on success |
|---------|---------|---------------------|
| Tests | `php artisan test --compact` | exit 0 |
| Pint | `vendor/bin/pint --dirty --format agent` | exit 0 |

## Scope
**In scope:**
- `app/Livewire/Pages/Comments/Index.php` — new Livewire component
- `app/Livewire/Pages/Comments/CreateEdit.php` — single comment view/approval
- `resources/views/pages/comments/index.blade.php` — new view
- `resources/views/pages/comments/create-edit.blade.php` — new view
- `routes/web.php` — new admin route for comments
- `resources/views/layouts/app.blade.php` — add "Comments" menu item

**Out of scope:**
- Email notification on comment submission
- Bulk approve/reject
- Spam filtering

## Git workflow
- Branch: `advisor/011-comment-approval-ui`
- Commit style: `feat: add comment approval queue admin UI`

## Steps

### Step 1: Create Comments/Index Livewire component

Create `app/Livewire/Pages/Comments/Index.php`:
```php
<?php

namespace App\Livewire\Pages\Comments;

use App\Models\Comment;
use Illuminate\Support\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public string $search = '';
    public ?string $filter_status = 'pending'; // 'pending' | 'approved' | null (all)
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function approve($id): void
    {
        Comment::find($id)?->update(['is_approved' => true]);
        $this->success('Comment approved.');
    }

    public function reject($id): void
    {
        Comment::find($id)?->delete();
        $this->success('Comment rejected and deleted.');
    }

    public function approveAll(): void
    {
        Comment::where('is_approved', false)->update(['is_approved' => true]);
        $this->success('All pending comments approved.');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'post.title', 'label' => 'Post', 'sortable' => false],
            ['key' => 'author_name', 'label' => 'Author'],
            ['key' => 'body', 'label' => 'Comment', 'sortable' => false, 'class' => 'w-64'],
            ['key' => 'is_approved', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Submitted'],
        ];
    }

    public function comments(): Collection
    {
        return Comment::query()
            ->with('post')
            ->when($this->filter_status === 'pending', fn($q) => $q->where('is_approved', false))
            ->when($this->filter_status === 'approved', fn($q) => $q->where('is_approved', true))
            ->when($this->search, fn($q) => $q->where('author_name', 'like', "%{$this->search}%"))
            ->orderBy(...array_values($this->sortBy))
            ->get();
    }

    public function with(): array
    {
        return [
            'comments' => $this->comments(),
            'headers' => $this->headers(),
            'pending_count' => Comment::where('is_approved', false)->count(),
        ];
    }

    public function render()
    {
        return view('pages.comments.index');
    }
}
```

### Step 2: Create view resources/views/pages/comments/index.blade.php

Use MaryUI table pattern, matching `resources/views/pages/tags/index.blade.php`:
- Header with "Comments" title and pending count badge
- Filter tabs: All / Pending / Approved
- Search input
- `x-table` with approve/reject action buttons
- "Approve All" button when pending count > 0

Key elements:
```blade
<div>
    <x-header title="Comments" separator>
        <x-slot:middle>
            @if($pending_count > 0)
                <x-badge value="{{ $pending_count }} pending" class="badge-warning" />
            @endif
        </x-slot:middle>
        <x-slot:actions>
            @if($pending_count > 0)
                <x-button label="Approve All" icon="o-check" wire:click="approveAll" />
            @endif
            <x-button label="{{ __('cms.create') }}" link="/admin/tags/create" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <x-table :headers="$headers" :rows="$comments" :sort-by="$sortBy" link="comments/{id}/edit">
            @scope('cell_is_approved', $comment)
                @if($comment->is_approved)
                    <x-badge value="Approved" class="badge-success" />
                @else
                    <x-badge value="Pending" class="badge-warning" />
                @endif
            @endscope
            @scope('actions', $comment)
                @unless($comment->is_approved)
                    <x-button icon="o-check" wire:click="approve({{ $comment['id'] }})" spinner class="btn-ghost btn-sm text-success" />
                @endunless
                <x-button icon="o-trash" wire:click="reject({{ $comment['id'] }})" wire:confirm="Reject and delete?" spinner class="btn-ghost btn-sm text-error" />
            @endscope
        </x-table>
    </x-card>
</div>
```

### Step 3: Add admin route

In `routes/web.php`, inside the `admin` group, add:
```php
Route::livewire('/comments', 'pages::comments.index')->name('comments.index');
```

Also add to the sidebar menu in `resources/views/layouts/app.blade.php`
(line 54 area — add before the separator):
```blade
<x-menu-item title="Comments" icon="o-chat-bubble-left" link="/admin/comments" />
```

### Step 4: Tests

```bash
php artisan test --compact
```

Add tests in `tests/Feature/CmsFeatureTest.php`:
```php
it('admin comments page renders for authenticated user', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user)->get('/admin/comments')->assertOk();
});

it('commenter can submit comment that is pending approval', function () {
    // covered by existing it('submits a comment that requires approval') test
    $pending = \App\Models\Comment::where('is_approved', false)->count();
    expect($pending)->toBeGreaterThan(0);
});
```

**Verify**: `php artisan test --compact` → exit 0

### Step 5: Format

**Verify**: `vendor/bin/pint --dirty --format agent` → exit 0

## Test plan
- `it('admin comments page renders')` — HTTP test, authenticated.
- `it('comments page shows pending count')` — Livewire component test.
- `it('approve action changes is_approved to true')` — Livewire component test.
- `it('reject action deletes comment')` — Livewire component test.

Follow pattern from `tests/Feature/CmsFeatureTest.php`.

## Done criteria
- [ ] `admin/comments` route exists and returns 200 for authenticated users
- [ ] `admin/comments` redirects to login for unauthenticated users
- [ ] Pending comments display with approve/reject actions
- [ ] Approved comments display with reject action
- [ ] `approveAll` button approves all pending comments
- [ ] `php artisan test --compact` exits 0
- [ ] `vendor/bin/pint --dirty --format agent` exits 0
- [ ] Only new Comments component, views, route addition, and sidebar menu added

## STOP conditions
If the Comments admin UI already exists, mark REJECTED.

## Maintenance notes
- If spam becomes a problem, add `rejected` state (`is_approved = null`) and
  filter by `null` for a separate "Rejected" tab.
- Email notification on approval can be added by hooking into the `approve()`
  action to dispatch a job.
- The `approveAll` batch operation is O(n) queries. If pending count grows
  large, replace with a single `whereIn` update.
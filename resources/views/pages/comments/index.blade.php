<div>
    <x-header title="{{ __('cms.comments') ?? 'Comments' }}" separator>
        <x-slot:middle>
            @if($pending_count > 0)
                <x-badge value="{{ $pending_count }} pending" class="badge-warning" />
            @endif
        </x-slot:middle>
        <x-slot:actions>
            @if($pending_count > 0)
                <x-button label="Approve All" icon="o-check" wire:click="approveAll" class="btn-success" />
            @endif
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        {{-- Filter tabs --}}
        <div class="flex gap-2 mb-4">
            <x-button
                label="All"
                size="sm"
                wire:click="$set('filter_status', null)"
                class="{{ $filter_status === null ? 'btn-primary' : 'btn-ghost' }}"
            />
            <x-button
                label="Pending"
                size="sm"
                wire:click="$set('filter_status', 'pending')"
                class="{{ $filter_status === 'pending' ? 'btn-warning' : 'btn-ghost' }}"
            />
            <x-button
                label="Approved"
                size="sm"
                wire:click="$set('filter_status', 'approved')"
                class="{{ $filter_status === 'approved' ? 'btn-primary' : 'btn-ghost' }}"
            />
        </div>

        {{-- Search --}}
        <x-input
            placeholder="Search by author..."
            wire:model.live.debounce="search"
            clearable
            icon="o-magnifying-glass"
            class="mb-4"
        />

        {{-- Table --}}
        <x-table :headers="$headers" :rows="$comments" :sort-by="$sortBy">
            @scope('cell_is_approved', $comment)
                @if($comment->is_approved)
                    <x-badge value="Approved" class="badge-success" />
                @else
                    <x-badge value="Pending" class="badge-warning" />
                @endif
            @endscope

            @scope('cell_post.title', $comment)
                @if($comment->post)
                    <a href="{{ route('public.posts.show', $comment->post->slug) }}" wire:navigate class="text-primary hover:underline">
                        {{ $comment->post->title }}
                    </a>
                @else
                    <span class="text-base-content/40">—</span>
                @endif
            @endscope

            @scope('actions', $comment)
                @unless($comment->is_approved)
                    <x-button
                        icon="o-check"
                        wire:click="approve({{ $comment['id'] }})"
                        spinner
                        class="btn-ghost btn-sm text-success"
                    />
                @endunless
                <x-button
                    icon="o-trash"
                    wire:click="reject({{ $comment['id'] }})"
                    wire:confirm="Reject and delete this comment?"
                    spinner
                    class="btn-ghost btn-sm text-error"
                />
            @endscope
        </x-table>
    </x-card>
</div>
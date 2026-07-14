<div>
    <x-header title="Comment Details" separator>
        <x-slot:actions>
            <x-button label="{{ __('cms.back') }}" link="/admin/comments" />
        </x-slot:actions>
    </x-header>

    <x-card shadow>
        <div class="space-y-4">
            <div>
                <span class="text-sm font-medium text-base-content/60">Post</span>
                @if($comment->post)
                    <a href="{{ route('public.posts.show', $comment->post->slug) }}" wire:navigate class="text-primary hover:underline font-medium">
                        {{ $comment->post->title }}
                    </a>
                @else
                    <span class="text-base-content/40">—</span>
                @endif
            </div>

            <div>
                <span class="text-sm font-medium text-base-content/60">Author</span>
                <span>{{ $comment->author_name }} <{{ $comment->author_email }}></span>
            </div>

            <div>
                <span class="text-sm font-medium text-base-content/60">Comment</span>
                <div class="bg-base-200 rounded-lg p-4">
                    {!! nl2br(e($comment->body)) !!}
                </div>
            </div>

            <div>
                <span class="text-sm font-medium text-base-content/60">Status</span>
                @if($comment->is_approved)
                    <x-badge value="Approved" class="badge-success" />
                @else
                    <x-badge value="Pending" class="badge-warning" />
                @endif
            </div>

            <div>
                <span class="text-sm font-medium text-base-content/60">Submitted</span>
                <span>{{ $comment->created_at->format('Y-m-d H:i') }}</span>
            </div>

            <div class="pt-4 border-t border-base-300 flex gap-2">
                @unless($comment->is_approved)
                    <x-button label="Approve" icon="o-check" wire:click="approve" class="btn-success" spinner />
                @endif
                <x-button label="Reject & Delete" icon="o-trash" wire:click="reject" wire:confirm="Reject and delete this comment?" class="btn-error" spinner />
            </div>
        </div>
    </x-card>
</div>
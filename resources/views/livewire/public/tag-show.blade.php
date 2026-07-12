<div>
    @if($tag)
        <h2 class="text-2xl font-bold mb-4">Posts tagged: {{ $tag->name }}</h2>

        <ul>
            @forelse($this->posts as $p)
                <li><a href="{{ route('public.posts.show', $p->slug) }}" wire:navigate>{{ $p->title }}</a></li>
            @empty
                <li>No posts found for this tag.</li>
            @endforelse
        </ul>

        {{ $this->posts->links() }}
    @endif
</div>
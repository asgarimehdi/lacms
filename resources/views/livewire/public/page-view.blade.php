<div>
    @if($page)
        <h1 class="text-3xl font-bold mb-4">{{ $page->title }}</h1>
        <article class="prose prose-lg max-w-none prose RTL">
            {!! $page->content !!}
        </article>
    @endif
</div>
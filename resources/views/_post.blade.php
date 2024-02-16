<div class="card mt-3 mb-2 blog_post">
    <div class="flex">
        @can('update', $post)
            <div class="float-right">
                @if($post->isDraft())
                    <form method="POST" class="form-horizontal" action="{{ route('blog.publish', ['post' => $post->id]) }}">
                        @csrf
                        <input type="submit" class="btn btn-primary btn-sm" value="Publish">
                    </form>
                <a href="/Blog/{{ $post->id }}/email" class="btn btn-secondary btn-sm">Test Email</a>
                @endif
                <a href="/Blog/{{ $post->id }}/edit" class="btn btn-secondary btn-sm">Edit</a>
            </div>
        @endcan

        <h1><a href="{{ route('blog.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a></h1>
    </div>
    <div class="card-body">
        @can('update', $post)
            @if($post->isDraft() && $post->prologue)
                <h2>Prologue</h2>
                {!! $post->prologueHtml() !!}
            @endif
        @endcan

        @if($post->isDraft())
            <h2>Body</h2>
        @endif

        {!! $post->bodyHtml() !!}

        @can('update', $post)
            @if($post->isDraft() && $post->epilogue)
                <h2>Epilogue</h2>
                {!! $post->epilogueHtml() !!}
            @endif
        @endcan

        @if($post->isPublished())
            <small class="float-right">{{ $post->published->format('M d') }}</small>
        @elseif($post->isScheduled())
            <small class="float-right">Scheduled: {{ $post->published->format('M d @ G:i') }}</small>
        @elseif($post->isDraft())
            <small class="float-right">Updated: {{ $post->updated_at->format('M d') }}</small>
        @endif
    </div>
</div>

<div class="card mt-3 mb-2">
    <div class="post card-body">
        <div class="float-right">
            @if($post->isDraft())
                <form method="POST" class="form-horizontal" action="{{ route('blog.publish', ['post' => $post->id]) }}">
                    @csrf
                    <input type="submit" class="btn btn-primary btn-sm" value="Publish">
                </form>
            @endif
            @can('update', $post)
                <a href="/Blog/{{ $post->id }}/edit" class="btn btn-secondary btn-sm">Edit</a>
            @endcan
        </div>

        <h1>{{ $post->title }}</h1>

        {!! $post->bodyHtml() !!}

        @if($post->isPublished())
            <small class="float-right">{{ $post->published->format('M d') }}</small>
        @elseif($post->isScheduled())
            <small class="float-right">Scheduled: {{ $post->published->format('M d') }}</small>
        @elseif($post->isDraft())
            <small class="float-right">Updated: {{ $post->updated_at->format('M d') }}</small>
        @endif
    </div>
</div>

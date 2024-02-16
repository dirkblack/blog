<div class="flex gap-6">
    <div class="mr-auto">
        <h2>{{ isset($title) ? $title : 'Blog' }}</h2>
    </div>
    <ul class="list-none flex gap-4">
        <li>
            <a href="{{ route('blog.admin') }}" class="flex gap-1">Admin</a>
        </li>
        <li>
            <a href="{{ route('blog.create') }}" class="flex gap-1">New Post</a>
        </li>
        <li>
            <a href="{{ route('blog.drafts') }}" class="flex gap-1">
                <span class="badge badge-light">{{ $draft_count }}</span>
                Drafts</a>
        </li>
        <li>
            <a href="{{ route('blog.scheduled') }}" class="flex gap-1">
                <span class="badge badge-light">{{ $scheduled_count }}</span>
                Scheduled</a>
        </li>
        <li>
            <a href="{{ route('blog.published') }}" class="flex gap-1">
                <span class="badge badge-light">{{ $published_count }}</span>
                Published</a>
        </li>
        <li>
            <a href="{{ route('blog.subscribers') }}" class="flex gap-1">
                <span class="badge badge-light">{{ $subscriber_count }}</span>
                Subscribers</a>
        </li>
    </ul>

</div>

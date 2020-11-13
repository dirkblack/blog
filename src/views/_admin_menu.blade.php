<div class="card">
    <div class="card-header">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    Blog <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a href="{{ route('blog.admin') }}" class="dropdown-item">Admin</a>
                    <a href="{{ route('blog.create') }}" class="dropdown-item">New Post</a>
                    <a href="{{ route('blog.drafts') }}" class="dropdown-item">
                        <span class="badge badge-light">{{ $draft_count }}</span>
                        Drafts</a>
                    <a href="{{ route('blog.scheduled') }}" class="dropdown-item">
                        <span class="badge badge-light">{{ $scheduled_count }}</span>
                        Scheduled</a>
                    <a href="{{ route('blog.published') }}" class="dropdown-item">
                        <span class="badge badge-light">{{ $published_count }}</span>
                        Published</a>
                    <a href="{{ route('blog.subscribers') }}" class="dropdown-item">
                        <span class="badge badge-light">{{ $subscriber_count }}</span>
                        Subscribers</a>
                </div>
            </li>
        </ul>

    </div>
    <div class="card-body">

    </div>
</div>

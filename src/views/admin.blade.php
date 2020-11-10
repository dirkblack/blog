@extends('layouts.app')

@section('content')
    <div class="card">
        <span class="card-body">
            <a href="{{ route('blog.create') }}" class="btn btn-primary">New Post</a>
            <a href="{{ route('blog.drafts') }}" class="btn btn-primary">
                <span class="badge badge-light">{{ $draft_count }}</span>
                Drafts
            </a>
            <a href="{{ route('blog.published') }}" class="btn btn-primary">
                <span class="badge badge-light">{{ $published_count }}</span>
                Published
            </a>
        </div>
    </div>
@endsection

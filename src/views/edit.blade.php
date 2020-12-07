@extends('layouts.blog')

@push('scripts')
    <script src="/js/blog.js" type="module"></script>
@endpush

@section('content')
    <div id="blog-app">
        <tags></tags>
    </div>
    <form method="POST" class="form-horizontal" action="/Blog/{{ $post->id }}">

        {!! csrf_field() !!}

        <div class="form-row mt-3">
            <div class="col">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="published">Published</label>
                    <input type="datetime-local" class="form-control" id="published"
                           name="published"
                           value="{{ $post->published ? $post->published->toDateTimeLocalString() : '' }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" name="body" cols="30" rows="10">{{ $post->body }}</textarea>
        </div>
        <div class="form-group">
            <label for="body">Prologue</label>
            <textarea class="form-control" name="prologue" cols="30" rows="5">{{ $post->prologue }}</textarea>
        </div>
        <div class="form-group">
            <label for="body">Epilogue</label>
            <textarea class="form-control" name="epilogue" cols="30" rows="5">{{ $post->epilogue }}</textarea>
        </div>
        <div class="form-group">
            <label for="body">Preview Text</label>
            <textarea class="form-control" name="preview" cols="30" rows="5">{{ $post->preview }}</textarea>
        </div>

        <div class="form-row">
            <div class="col">
                <div class="form-group">

                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" id="create" name="create" value="Update">
                </div>
            </div>
        </div>

    </form>
@endsection

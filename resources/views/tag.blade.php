@extends(config('blog.layout'))

@section('content')
    <h1>Tag: {{ $tag }}</h1>

    @foreach($posts as $post)
        @include('blog::_post', ['post' => $post])
    @endforeach
@endsection

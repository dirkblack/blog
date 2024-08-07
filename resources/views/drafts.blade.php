@extends(config('blog.layout'))

@section('content')

    @include('blog::_admin_menu')

    @foreach($posts as $post)
        @include('blog::_post', ['post' => $post])
    @endforeach

@endsection

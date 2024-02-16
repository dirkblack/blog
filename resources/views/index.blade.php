@extends(config('blog.layout'))

@section('content')

    @auth
        <a href="{{route('blog.admin')}}">Admin</a>
    @endauth

    <div class="w-full max-w-screen-md bg-gray-100">
        <div class="px-6 py-8">
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    @include('blog::_post', ['post' => $post])
                @endforeach
            @else
                <p>No Posts have been published</p>
            @endif
        </div>
    </div>
@endsection

@extends(config('blog.layout'))

@section('content')

    @auth
        <a href="{{route('blog.admin')}}">Admin</a>
    @endauth

    <div class="flex justify-center">
        <div class="w-full max-w-screen-md bg-gray-100">
            {{ $posts->links() }}

            <div class="flex flex-col">
                @if(count($posts) > 0)
                    @foreach($posts as $post)
                        @include('blog::_post', ['post' => $post])
                    @endforeach
                @else
                    <p>No Posts have been published</p>
                @endif
            </div>

            {{ $posts->links() }}
        </div>
    </div>
@endsection

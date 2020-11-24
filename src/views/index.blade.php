@extends('layouts.blog')

@section('content')

    @auth
        <a href="{{route('blog.admin')}}">Admin</a>
    @endauth

    @if(count($posts) > 0)
        @foreach($posts as $post)
            @include('darkblog::_post', ['post' => $post])
        @endforeach
    @else
        <p>No Posts have been published</p>
    @endif
@endsection

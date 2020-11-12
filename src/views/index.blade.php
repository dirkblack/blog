@extends('layouts.app')

@section('content')

    @auth
        <a href="{{route('blog.admin')}}">Admin</a>
    @endauth

    @foreach($posts as $post)
        @include('darkblog::_post', ['post' => $post])
    @endforeach

@endsection

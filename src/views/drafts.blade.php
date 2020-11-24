@extends('layouts.blog')

@section('content')

    @include('darkblog::_admin_menu')

    @foreach($posts as $post)
        @include('darkblog::_post', ['post' => $post])
    @endforeach

@endsection

@extends('layouts.blog')

@section('content')
    <h1>Tag: {{ $tag }}</h1>

    @foreach($posts as $post)
        @include('darkblog::_post', ['post' => $post])
    @endforeach
@endsection

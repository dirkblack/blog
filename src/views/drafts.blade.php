@extends('layouts.app')

@section('content')

    @foreach($posts as $post)
        @include('blog._post', ['post' => $post])
    @endforeach

@endsection
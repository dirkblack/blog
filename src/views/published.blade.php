@extends('layouts.app')

@section('content')

    @foreach($posts as $post)
        @include('darkblog::_post', ['post' => $post])
    @endforeach

@endsection

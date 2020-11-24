@extends('layouts.blog')

@section('content')
    @include('darkblog::_post', ['post' => $post])
@endsection

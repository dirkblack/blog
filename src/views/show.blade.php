@extends('layouts.app')

@section('content')
    @include('blog._post', ['post' => $post])
@endsection
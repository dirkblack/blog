@extends('layouts.app')

@section('content')
    @include('darkblog::_post', ['post' => $post])
@endsection

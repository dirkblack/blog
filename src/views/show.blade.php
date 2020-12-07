@extends('layouts.blog')

@push('head')
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="{{ url()->full() }}"/>
    <meta property="og:title" content="{{ $post->title }}"/>
    <meta property="og:description" content="{{ $post->preview }}"/>
{{--    <meta property='og:image' content='//media.example.com/1234567.jpg'/>--}}
@endpush

@section('content')
    @include('darkblog::_post', ['post' => $post])
@endsection

@extends('layouts.app')

@section('content')
    @include('darkblog::_admin_menu')
    <h1>Subscribers</h1>
    <a href="{{ route('blog.subscribe') }}" class="btn btn-primary">Create</a>
    @foreach($subscribers as $subscriber)
        <p>{{ $subscriber->first_name }}</p>
    @endforeach
@endsection

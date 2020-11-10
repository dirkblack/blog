@extends('layouts.app')

@section('content')
    <h1>Subscribers</h1>
    @foreach($subscribers as $subscriber)
        <p>{{ $subscriber->first_name }}</p>
    @endforeach
@endsection
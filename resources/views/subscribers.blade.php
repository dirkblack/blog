@extends('layouts.blog')

@section('content')
    @include('blog::_admin_menu', ['title' => 'Subscribers'])
    <a href="{{ route('blog.subscribe.force') }}" class="btn btn-primary">Add Subscriber</a>
    <p>Count: {{ count($subscribers) }}</p>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>email</th>
            <th>Since</th>
        </tr>
        @foreach($subscribers as $subscriber)
            <tr>
                <td>{{ $subscriber->name }}</td>
                <td>{{ $subscriber->email }}</td>
                <td>{{ $subscriber->created_at }}</td>
            </tr>
        @endforeach
    </table>
@endsection

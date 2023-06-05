@extends('layouts.blog')

@section('content')
    @include('blog::_admin_menu', ['title' => 'subscribe'])
    <p>New blog posts will be delivered by email.</p>

    <form method="POST" class="form-horizontal" action="{{ route('subscribe') }}">

        {!! csrf_field() !!}

        <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" id="email" placeholder="Required">
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Required">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" id="create" name="create" value="Create">
        </div>

    </form>
@endsection

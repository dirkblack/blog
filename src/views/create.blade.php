@extends('layouts.app')

@section('content')
    @include('darkblog::_admin_menu')
    <form method="POST" class="form-horizontal" action="/Blog">
        {!! csrf_field() !!}
        <div class="card">
            <div class="card-header">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Make it good!">
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="body">Body</label>
                    <textarea class="form-control" name="body" id="body" cols="30" rows="10"></textarea>
                </div>
                <div class="form-group">
                    <label for="body">Prologue</label>
                    <textarea class="form-control" name="prologue" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="body">Epilogue</label>
                    <textarea class="form-control" name="epilogue" cols="30" rows="5"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" id="create" name="create" value="Create">
                </div>
            </div>
        </div>

    </form>
@endsection

@extends('layouts.blog')

@section('content')
    @include('blog::_admin_menu')
    <form method="POST" class="form-horizontal" action="{{ route('blog.store.file') }}" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" id="file_name" name="file_name" value="{{ $file }}">
        <div class="card">
            <div class="card-header">
                Upload File: {{ $file }}
            </div>
            <div class="card-body">
                    <input type="file" id="file_upload" name="file_upload">
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="upload" value="Upload">
                </div>
            </div>
        </div>

    </form>
@endsection

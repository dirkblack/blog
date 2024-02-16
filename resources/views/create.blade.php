@extends(config('blog.layout'))

@section('content')
    @include('blog::_admin_menu')
    <div class="w-full">
        <form method="POST" class="w-full" action="/Blog">
            @csrf
            <div class="flex flex-col gap-4">
                <div class="flex flex-col gap-2">
                    <label class="font-bold" for="title">Title</label>
                    <input type="text" id="title" name="title" placeholder="Make it good!">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-bold" for="body">Body</label>
                    <textarea name="body" id="body" cols="30" rows="10"></textarea>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-bold" for="body">Prologue</label>
                    <textarea name="prologue" cols="30" rows="5"></textarea>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-bold" for="body">Epilogue</label>
                    <textarea name="epilogue" cols="30" rows="5"></textarea>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-bold" for="body">Preview Text</label>
                    <textarea name="preview" cols="30" rows="5"></textarea>
                </div>
                <div class="flex flex-col gap-2">
                    <input type="submit" class="btn btn-primary" id="create" name="create" value="Create">
                </div>
            </div>
        </form>
    </div>
@endsection

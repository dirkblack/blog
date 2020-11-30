@component('mail::message')

@if($post->prologue)
<div style="font-style:italic;">

Prologue:

{!! $post->prologue !!}

</div>

@endif

# {{ $post->title }}

{!! $post->body !!}

@if($post->epilogue)
<div style="font-style:italic;">

Epilogue:

{!! $post->epilogue !!}

</div>

@endif

~ {{ config('app.name') }}
@endcomponent

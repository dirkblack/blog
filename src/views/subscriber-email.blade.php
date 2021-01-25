@component('mail::message')

@if($post->prologue)
<div style="font-style:italic;">

Prologue:

{!! $post->prologueHtml() !!}

</div>

@endif

# {{ $post->title }}

{!! $post->bodyHtml() !!}

@if($post->epilogue)
<div style="font-style:italic;">

Epilogue:

{!! $post->epilogueHtml() !!}

</div>

@endif

~ {{ config('app.name') }}
@endcomponent

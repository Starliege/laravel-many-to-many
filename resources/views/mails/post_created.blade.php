@component('mail::message')
# nuovo post

un nuovo post
    
@component('mail::button', ['url' => route('admin.posts.show', $post)])
{{ $post->title }}
@endcomponent
Grazie, <br>
{{ config('app.name') }}
@endcomponent
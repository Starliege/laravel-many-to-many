@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ $post->title }}</div>

          <div class="card-body">

            {{ $post->content }}
            <br>
            <br>
            @foreach ($post->tags as $tag)
              {{ $tag->name }}
            @endforeach

          </div>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-info mt-4">Indietro</a>
      </div>
    </div>
  </div>
@endsection

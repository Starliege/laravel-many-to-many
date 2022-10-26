@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <p>Cover:</p>
          @if ($post->cover)
              <img src="{{asset('storage/' . $post->cover)}}" class="img-fluid"/>
          @else
              <img src="https://upload.wikimedia.org/wikipedia/commons/a/ac/No_image_available.svg"
                   class="img-thumbnail"
                   style="width:75px;"/>
          @endif
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

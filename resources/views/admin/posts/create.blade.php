@extends('layouts.admin')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Scrivi un nuovo post</div>

          <div class="card-body">

            <form action="{{ route('admin.posts.store') }}" method="post">
              @csrf

              <div class="form-group">
                <label>Titolo</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                  placeholder="Inserisci il titolo del post">
              </div>

              <div class="form-group">
                <label>Testo</label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                  placeholder="Inserisci il contenuto del post"></textarea>
              </div>

              <div class="form-group">
                <label>Tag</label>
                @foreach ($tags as $tag)
                  <div class="form-check">
                    <input class="form-check-input" name="tags[]" type="checkbox" value="{{ $tag->id }}">
                    <label class="form-check-label" for="{{ $tag->slug }}">
                      {{ $tag->name }}
                    </label>
                  </div>
                @endforeach
              </div>

              <div class="form-group">
                <label>Categoria</label>
                <select name="category_id" class="form-control @error('content') is-invalid @enderror">
                  <option value="" selected disabled hidden>Seleziona una categoria</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group">
                <input type="submit" class="btn btn-success" value="Crea post">
              </div>

            </form>

          </div>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-info mt-4">Indietro</a>
      </div>
    </div>
  </div>
@endsection

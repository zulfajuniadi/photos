@extends('layout')

@section('content')
<div class="container-fluid albums">
  @foreach($albums as $album)
    <div><a href="{{action('AlbumsController@getView', $album->id)}}">
      <?php
        $latest = $album->latest();
      ?>
      @if(isset($latest))
        <img src="{{$latest->thumb_path}}" alt="">
      @endif
      <footer>
        {{$album->name}}
      </footer>
    </a></div>
  @endforeach
</div>

@stop
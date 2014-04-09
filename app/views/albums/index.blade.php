@extends('layout')

@section('content')
<style>
.albums > div {
  height: 180px;
  position: relative;
  width: 180px;
  display: inline-block;
  vertical-align: top;
  overflow: hidden;
  text-align: center;
}
.albums > div img {
  height: 180px;
}
.albums > div footer {
  position: absolute;
  bottom: 0px;
  left: 0px;
  width: 100%;
  text-align: center;
  background-color: rgba(255, 255, 255, 0.8);
}
</style>

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
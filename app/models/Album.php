<?php

class Album extends Eloquent
{

  public function pictures()
  {
    return $this->hasMany('Picture');
  }

  public function latest()
  {
    return Picture::find(Picture::where('album_id', '=', $this->id)->max('id'));
  }
}
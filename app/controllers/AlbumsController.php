<?php

class AlbumsController extends BaseController
{

  public function getIndex()
  {
    return View::make('albums.index')
    -> with([
      'albums' => Album::all()
      ]);
  }

  public function postNew()
  {
    $album = new Album;
    $album->name = trim(Input::get('album_name'));
    $album->save();
    return Redirect::to('/albums');
  }

  public function getView($id)
  {
    return View::make('albums.view')
    -> with([
      'album' => Album::find($id)
      ->load ('pictures')
      ]) ;
  }

  public function deletePicture($id) {
    return Response::json('OK', 200);
  }

  public function postNewImage($album_id)
  {

    $file = Input::file('file');
    $file_name = $file->getClientOriginalName();

    if(Picture::where('file_name', '=', $file_name)->where('album_id', '=', $album_id)->count() > 0)
      return Response::json($file_name . ' already exists', 400);

    try {
      mkdir(public_path() . 'uploads');
      mkdir(public_path() . 'uploads/originals');
      mkdir(public_path() . 'uploads/mediums');
      mkdir(public_path() . 'uploads/thumbs');
    } catch (Exception $e) {

    }

    $file_type = $file->getMimeType();
    $file_size = $file->getSize();

    $file_directory = public_path() . 'uploads/originals';
    $medium_directory = public_path() . 'uploads/mediums';
    $thumb_directory = public_path() . 'uploads/thumbs';
    $extension = $file->getClientOriginalExtension();
    $masked_name = sha1(time().microtime()).".{$extension}";

    $upload_success = $file->move($file_directory, $masked_name);
    $file_path = $file_directory . '/' . $masked_name;
    $thumb_path = $thumb_directory . '/' . $masked_name;
    $medium_path = $medium_directory . '/' . $masked_name;

    $exif_data = false;
    try {
      $exif_data = shell_exec('node exifreader.js "' . $file_path . '"');
    } catch (Exception $e) {}

    $dimension = getimagesize($file_path);

    $picture = new Picture();
    $picture->album_id = $album_id;
    $picture->file_name = $file_name;
    $picture->file_size = $file_size;
    $picture->file_path = str_replace('public', '', $file_path);
    $picture->thumb_path = str_replace('public', '', $thumb_path);
    $picture->medium_path = str_replace('public', '', $medium_path);
    $picture->file_type = $file_type;
    $picture->exif_data = $exif_data;
    $picture->width = $dimension[0];
    $picture->height = $dimension[1];

    $picture->save();

    try {
      $this->fix_orientation($file_path);
    } catch (Exception $e) {

    }

    /* thumbnail */

    $thumb = new Imagick($file_path);
    $thumb->resizeImage(180,0,Imagick::FILTER_LANCZOS,1);
    $thumb->writeImage($thumb_path);

    $thumb = new Imagick($file_path);
    $thumb->resizeImage(0,1000,Imagick::FILTER_LANCZOS,1);
    $thumb->writeImage($medium_path);

    if( $upload_success ) {
      return Response::json($picture, 200);
    } else {
      return Response::json('error', 400);
    }
  }

  private function fix_orientation($file_path) {
        //This line reads the EXIF data and passes it into an array
    $exif = read_exif_data($file_path);

        //We're only interested in the orientation
    $exif_orient = isset($exif['Orientation'])?$exif['Orientation']:0;
    $rotateImage = 0;

        //We convert the exif rotation to degrees for further use
    if (6 == $exif_orient) {
      $rotateImage = 90;
      $imageOrientation = 1;
    } elseif (3 == $exif_orient) {
      $rotateImage = 180;
      $imageOrientation = 1;
    } elseif (8 == $exif_orient) {
      $rotateImage = 270;
      $imageOrientation = 1;
    }

    $imagick = new Imagick();
    $imagick->readImage($file_path);
    $imagick->rotateImage(new ImagickPixel(), $rotateImage);
    $imagick->setImageOrientation($imageOrientation);
    $imagick->writeImage($file_path);
    $imagick->clear();
    $imagick->destroy();
  }
}
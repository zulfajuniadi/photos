@extends('layout')

@section('content')
  <style>
    #uploadImage {
      width:180px;
      height:150px;
      text-align:center;
      cursor:pointer;
      padding-top: 58px;
      border: 1px solid #DDD;
    }
    #slideShow {
      width:180px;
      height:150px;
      text-align:center;
      cursor:pointer;
      padding-top: 58px;
      border: 1px solid #DDD;
    }

    .slider {
      max-width: 100%;
      max-height: 100%;
      overflow: hidden;
    }

    .slider .modal-dialog{
      width: 100%;
      height: 100%;
      margin: 0;
    }

    .slider .modal-body {
      padding: 0px;
      min-height: 100%;
    }

    .slider .bootbox-body {
      min-height: 100%;
    }

    .slider .modal-footer {
      display: none;
    }

    .slider .modal-content {
      background-color: rgba(0, 0, 0, 0.2);
      text-align: center;
      background-color: rgba(0,0,0,0.2);
      min-height: 100%;
    }

    .slider .gallery {
      overflow-x: scroll;
      height: calc(100% - 20px);
    }

    .slider .gallery img {
      height: calc(100% - 20px);
    }

    .slider .bootbox-close-button {
      display: none;
    }

    #left_ctrl {
      left: 10px;
    }

    #right_ctrl {
      right: 10px;
    }

    #stopSlider {
      top:10px;
      right:10px;
    }

    #download {
      top:51px;
      right:10px;
      color:#333;
    }

    #download:hover {
      text-decoration: none;
    }

    #deletePicture {
      top:92px;
      right:10px;
    }

    .slider .controls {
      position: absolute;
      top: 50%;
      height: 40px;
      width: 40px;
      background-color: rgba(255, 255, 255, 0.55);
      padding-top: 11px;
      cursor: pointer;
    }

    .slider .controls:hover {
      background-color: rgba(255, 255, 255, 1);
    }

    #loading {
      position: absolute;
      top: 50%;
      left: 50%;
    }

  </style>
  <div id="pictures">
    @foreach($album->pictures as $picture)
      <span
        @foreach($picture->toArray() as $key => $value)
          data-{{$key}}='{{$value}}'
        @endforeach
      ><img src="{{$picture->thumb_path}}" alt="" /></span>
    @endforeach
    <div id="uploadImage" style="">
      Add Image to<br />
      {{$album->name}}
    </div>
  </div>
  <script>
    var pictureDiv = $('#pictures');

    pictureDiv.imagesLoaded(function(){
      pictureDiv.isotope();
    });

    $('body').on('click', '#right_ctrl,#left_ctrl', function(){
      var images = $('img', '#pictures');
      if(images.length < 1)
        return $('.modal').modal('hide');
      var src = $('img',".gallery").data('thumb_path');
      var imageNow = images.filter(function(idx, el){
        return el.src.toString().indexOf(src) > -1;
      });
      var index = images.index(imageNow);
      if(index > -1) {
        if(this.id === 'right_ctrl') {
          if((index + 1) === images.length) {
            /* last */
            galleryImageLoad(0);
          }
          else {
            galleryImageLoad(index + 1);
          }
        } else {
          if(index === 0) {
            /* last */
            galleryImageLoad(images.length - 1);
          }
          else {
            galleryImageLoad(index - 1);
          }
        }
      }
    });

    $('body').on('click', '#stopSlider', function(){
      $('.modal').modal('hide');
    });

    $('body').on('click', '#deletePicture', function(){
      var id = $(this).data('picid');
      var removeSpan = $('#pictures span').filter(function(i, el){
        return $(el).data().id === id;
      });

      if(removeSpan.length) {
        bootbox.confirm('Are you sure you want to delete this picture?<hr /><img src="'+  removeSpan.data('thumb_path') +'" />', function(res){
          if(res) {
            $.ajax('/albums/picture/' + id, {
              method: 'DELETE',
              success: function() {
                $('#right_ctrl').trigger('click');
                removeSpan.remove();
                $('#pictures').isotope( 'reloadItems' ).isotope();
                var images = $('img', '#pictures');
                if(images.length < 1) {
                  $.ajax('/albums/index/' + window.location.pathname.split('/').pop(), {
                    method: 'DELETE',
                    success: function() {
                      window.location.href = '/albums';
                    }
                  });
                }
              }
            })
          }
        });
      }
    });

    var galleryImageLoad = function(index) {
      var image = new Image;
      var selected = $('span', '#pictures').get(index);
      var data = $(selected).data();

      $('#loading').show();
      $('.gallery').fadeOut(function(){
        image.src = data.medium_path;
        image.onload = function(){
          $('#download').attr('href', data.file_path);
          $('#deletePicture').data('picid', data.id);
          $(image).data('thumb_path', data.thumb_path);
          galleryImageLoaded(image);
        };
      });
    }

    var galleryImageLoaded = function(image) {
      $('#loading').hide();
      $('.gallery').html(image).fadeIn();
    };

    $('#pictures').on('click', 'span', function(){
      var data = $(this).data();
      bootbox.confirm('<div id="loading"><img src="/loading.gif" /></div><div class="gallery"></div><div class="controls" id="left_ctrl">PREV</div><div class="controls" id="right_ctrl">NEXT</div><div id="stopSlider" data-picid="' + data.id + '" class="controls">EXIT</div><div id="deletePicture" data-picid="' + data.id + '" class="controls">DEL</div><a href="' + data.file_path + '" download="' + data.file_name + '" id="download" class="controls">DNLD</a>', function(){});
      $('.btn.btn-default[type=button][data-bb-handler=cancel]').remove();
      $('.modal').addClass('slider');
      var image = new Image;
      image.src = data.medium_path;
      image.onload = function(){
        $(image).data('thumb_path', data.thumb_path);
        galleryImageLoaded(image);
      };
    })

    $('#uploadImage').click(function(){
      bootbox.confirm('<hr/><div id="dropzone"></div>', function(){});
      $('.btn.btn-default[type=button][data-bb-handler=cancel]').remove();
      $('#dropzone')
        .addClass('dropzone')
        .dropzone({
          url: "{{action('AlbumsController@postNewImage', $album->id)}}",
          addRemoveLinks: true,
          clickable: true,
          acceptedFiles: 'image/*',
          success: function(file, result){
            var image = new Image;
            var span = $('<span/>');
            image.onload = function(){
              pictureDiv.isotope( 'reloadItems' ).isotope();
            };
            image.src = result.thumb_path;
            span.data(result);
            span.append(image);
            pictureDiv.append(span);
            return true;
          }
        });
    })

    Dropzone.autoDiscover = false;
  </script>
@stop
<div class="container-fluid">
  <div class="header">
    <ul class="list-unstyled list-inline">
      <li><a class="active" href="/albums">Albums</a></li>
      <li><a id="newAlbum">+</a></li>
    </ul>
  </div>
</div>

<script>
  $('#newAlbum').popover({
    html: true,
    content: '<form method="post" class="form-inline" action="{{action("AlbumsController@postNew")}}"><input type="text" name="album_name" placeholder="Album Name" autofocus required> <button>Save</button></form>',
    title: 'New Album',
    placement: 'auto'
  })
</script>
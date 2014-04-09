<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('albums');
		Schema::create('albums', function($table)
		{
			$table->increments('id');
			$table->string('name');
			$table
				-> text('locations')
				-> nullable();
			$table
				-> date('start_date')
				-> nullable();
			$table
				-> date('end_date')
				-> nullable();
      $table
        -> text('description')
        -> nullable();
      $table
        -> integer('latest_picture_id')
        -> nullable();
      $table
        -> timestamps();
		});

    $album = new Album;
    $album->name = 'Umrah 2014';
    $album->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('albums');
	}

}

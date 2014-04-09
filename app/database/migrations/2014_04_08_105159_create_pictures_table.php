<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('pictures');
		Schema::create('pictures', function($table){
      $table->increments('id');
			$table->integer('album_id');
			$table->string('file_name');
			$table->integer('file_size');
			$table->string('file_type');
			$table->integer('width')
        -> nullable();
			$table->integer('height')
        -> nullable();
			$table
        -> text('exif_data')
        -> nullable();
      $table
        -> string('file_path')
        -> nullable();
      $table
        -> string('medium_path')
        -> nullable();
      $table
        -> string('thumb_path')
        -> nullable();
      $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pictures');
	}

}

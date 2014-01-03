<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileLibraryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_library', function(Blueprint $table)
		{
			$table->increments('file_library_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->text('file_name');
			$table->text('file_storage_name');
			$table->text('file_path');
			$table->string('file_extension', 10);
			$table->string('mime_type', 20);
			$table->text('file_thumbnail');
			$table->integer('download_count')->unsigned();
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
		Schema::drop('file_library');
	}

}

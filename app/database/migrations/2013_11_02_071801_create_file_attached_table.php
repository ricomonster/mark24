<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileAttachedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('file_attached', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->foreign('post_id')->references('post_id')->on('posts');

			$table->integer('file_id')->unsigned();
			$table->foreign('file_id')
				->references('file_library_id')
				->on('file_library');

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
		Schema::drop('file_attached');
	}

}

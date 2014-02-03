<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostRecipientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post_recipients', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned();
			$table->foreign('post_id')->references('post_id')->on('posts');
			$table->integer('recipient_id')->unsigned();
			$table->string('recipient_type', 10);
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
		Schema::drop('post_recipients');
	}

}

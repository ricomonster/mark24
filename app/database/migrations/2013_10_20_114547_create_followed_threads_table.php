<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowedThreadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followed_forum_threads', function(Blueprint $table)
		{
			$table->increments('followed_forum_thread_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				->references('id')
				->on('users');

			$table->integer('forum_thread_id')->unsigned();
			$table->foreign('forum_thread_id')
				->references('forum_thread_id')
				->on('forum_threads');

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
		Schema::drop('followed_forum_threads');
	}

}

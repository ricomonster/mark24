<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumThreadReplies extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_thread_replies', function(Blueprint $table)
		{
			$table->increments('forum_thread_reply_id');
			$table->integer('forum_thread_id')->unsigned();
			$table->foreign('forum_thread_id')
				->references('forum_thread_id')
				->on('forum_threads');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			$table->text('reply');
			$table->bigInteger('reply_timestamp')->unsigned();

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
		Schema::drop('forum_thread_replies');
	}

}

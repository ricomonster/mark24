<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumThreadViews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_thread_views', function(Blueprint $table)
		{
			$table->increments('forum_thread_view_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('forum_thread_id')->unsigned();
			$table->foreign('forum_thread_id')
				->references('forum_thread_id')
				->on('forum_threads');
			$table->bigInteger('view_timestamp')->unsigned();
			$table->date('last_viewed');
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
		Schema::drop('forum_thread_views');
	}

}

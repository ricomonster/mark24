<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumThreadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_threads', function(Blueprint $table)
		{
			$table->increments('forum_thread_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')
				->references('forum_category_id')
				->on('forum_categories');
			$table->string('title', 255);
			$table->text('description');
			$table->string('status', 20)->default('OPEN');
			$table->string('seo_url', 255);
			$table->integer('views')->unsigned();
			$table->integer('replies')->unsigned();
			$table->string('sticky_post', 5)->default('false');
			$table->bigInteger('thread_timestamp')->unsigned();
			$table->bigInteger('last_reply_timestamp')->unsigned();
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
		Schema::drop('forum_threads');
	}

}

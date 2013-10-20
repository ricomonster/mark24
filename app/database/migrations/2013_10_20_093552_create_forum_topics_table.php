<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumTopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_topics', function(Blueprint $table)
		{
			$table->increments('forum_topic_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')
				->references('forum_category_id')
				->on('forum_categories');
			$table->string('title');
			$table->text('description');
			$table->string('status', 20)->default('OPEN');
			$table->integer('views')->unsigned()->default(0);
			$table->integer('replies')->unsigned()->default(0);
			$table->bigInteger('timestamp')->unsigned();

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
		Schema::drop('forum_topics');
	}

}

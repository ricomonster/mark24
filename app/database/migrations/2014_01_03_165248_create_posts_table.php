<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('post_id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('post_type', 15)->nullable();
			$table->string('note_content', 255)->nullable();
			$table->string('alert_content', 140)->nullable();
			$table->integer('quiz_id')->unsigned()->nullable();
			$table->date('quiz_due_date')->nullable();
			$table->integer('assignment_id')->unsigned()->nullable();
			$table->date('assignment_due_date')->nullable();
			$table->string('post_attached_files', 5)->default('false');
			$table->bigInteger('post_timestamp')->unsigned();
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
		Schema::drop('posts');
	}

}

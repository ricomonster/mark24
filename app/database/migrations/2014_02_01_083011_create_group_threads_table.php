<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupThreadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_forum_threads', function(Blueprint $table)
		{
			$table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('group_id')->on('groups');
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
		Schema::drop('group_forum_threads');
	}

}
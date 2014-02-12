<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameForumDescription extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forum_threads', function(Blueprint $table)
		{
			$table->renameColumn('description', 'forum_description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forum_threads', function(Blueprint $table)
		{
			$table->renameColumn('forum_description', 'description');
		});
	}

}
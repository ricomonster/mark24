<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStickyPost extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forum_threads', function(Blueprint $table)
		{
			$table->string('sticky_post', 5)->default('FALSE')->after('replies');
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
			$table->dropColumn('sticky_post');
		});
	}

}

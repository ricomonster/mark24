<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThreadSourceColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forum_threads', function(Blueprint $table)
		{
			$table->integer('thread_source')->unsigned()->after('sticky_post');
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
			$table->dropColumn('thread_source');
		});
	}

}
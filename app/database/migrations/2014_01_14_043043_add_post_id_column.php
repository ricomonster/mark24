<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostIdColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz_takers', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned()->after('quiz_id');
			$table->foreign('post_id')
				->references('post_id')
				->on('posts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quiz_takers', function(Blueprint $table)
		{
			$table->dropColumn('post_id');
		});
	}

}

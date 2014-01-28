<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostIdColumnResponses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('assignment_responses', function(Blueprint $table)
		{
			$table->integer('post_id')->unsigned()->after('assignment_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('assignment_responses', function(Blueprint $table)
		{
			$table->dropColumn('post_id');
		});
	}

}

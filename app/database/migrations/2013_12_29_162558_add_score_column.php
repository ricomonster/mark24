<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('assignment_responses', function(Blueprint $table)
		{
			$table->integer('score')->unsigned()->after('response');
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
			$table->dropColumn('score');
		});
	}

}

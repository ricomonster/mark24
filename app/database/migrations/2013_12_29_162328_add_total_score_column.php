<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalScoreColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('assignments', function(Blueprint $table)
		{
			$table->integer('total_score')->unsigned()->after('description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('assignments', function(Blueprint $table)
		{
			$table->dropColumn('total_score');
		});
	}

}

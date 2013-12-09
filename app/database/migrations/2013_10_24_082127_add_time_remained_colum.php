<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeRemainedColum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz_takers', function(Blueprint $table)
		{
			$table->integer('time_remaining')->unsigned()->after('no_items_correct');
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
			$table->dropColumn('time_remaining');
		});
	}

}

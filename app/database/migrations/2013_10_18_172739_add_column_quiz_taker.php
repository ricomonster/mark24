<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnQuizTaker extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz_takers', function(Blueprint $table)
		{
			$table->integer('no_items_correct')
				->default(0)
				->unsigned()
				->after('score');
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
			$table->dropColumn('no_items_correct');
		});
	}

}

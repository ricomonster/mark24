<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuizActiveColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz', function(Blueprint $table)
		{
			$table->integer('quiz_active')
				->unsigned()
				->default(1)
				->after('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quiz', function(Blueprint $table)
		{
			$table->dropColumn('quiz_active');
		});
	}

}
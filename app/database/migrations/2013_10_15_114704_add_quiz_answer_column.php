<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuizAnswerColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz_answers', function(Blueprint $table)
		{
			$table->integer('points')
				->unsigned()
				->after('is_correct');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quiz_answers', function(Blueprint $table)
		{
			$table->dropColumn('points');
		});
	}

}

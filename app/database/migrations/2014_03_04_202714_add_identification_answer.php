<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentificationAnswer extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('quiz_answers', function(Blueprint $table)
		{
			$table->string('identification_answer')
				->nullable()
				->after('short_answer_text');
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
			$table->dropColumn('identification_answer');
		});
	}

}
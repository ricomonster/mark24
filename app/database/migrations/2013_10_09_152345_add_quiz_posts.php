<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuizPosts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->integer('quiz_id')
				->unsigned()
				->nullable()
				->after('alert_content');

			$table->date('quiz_due_date')
				->nullable()
				->after('quiz_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('posts', function(Blueprint $table)
		{
			$table->dropColumn('quiz_id');
			$table->dropColumn('quiz_due_date');
		});
	}

}
